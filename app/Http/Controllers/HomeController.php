<?php

namespace App\Http\Controllers;

use DB;
use Str;
use Validator;
use App\Models\Coin;
use App\Models\News;
use GuzzleHttp\Client;
use App\Models\Equipment;
use App\Models\PoolStats;
use Illuminate\Http\Request;
use Sendpulse\RestApi\ApiClient;
use App\ViewModels\NewsViewModel;
use Butschster\Head\Facades\Meta;
use App\ViewModels\RatingViewModel;
use App\ViewModels\EquipmentViewModel;
use App\ViewModels\NewsCategoryViewModel;
use Symfony\Component\DomCrawler\Crawler;
use Sendpulse\RestApi\Storage\FileStorage;

class HomeController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'HTTP_USER_AGENT' => 'Mozilla/5.0',
            'http_errors' => false
        ]);
    }

    public function index()
    {
        Meta::includePackages('owl-carousel', 'datatables', 'fixedcolumns');

        return view('home', [
            'news' => NewsViewModel::latest(),
            'news_reviews' => NewsViewModel::reviews(),
            'news_people' => NewsViewModel::people(),
            'news_investment' => NewsViewModel::investment(),
            'news_events' => NewsViewModel::events(),
            'news_hot_topic' => News::active()->find(setting('news_hot_topic')),
            'news_categories' => NewsCategoryViewModel::primary(),
            'equipments' => EquipmentViewModel::home(),
            'coins' => Coin::with('media')->active()->whereIn('code', config('app_data.pool_stats_coins'))->oldest()->has('poolStats')->get(),
        ]);
    }

    public function subscribe()
    {
        abort_if(!request()->ajax(), 404);

        return view('inc.subscribe');
    }

    public function subscribeSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        define('API_USER_ID', '3ed8d040cc922feb8fe575b0517faf3a');
        define('API_SECRET', 'cfa4e1bdabc6ce6b6f7156450dd3f6c9');

        $sp_api_client = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

        $emails = [
            ['email' => $request->input('email')]
        ];

        $response = $sp_api_client->addEmails('474368', $emails);

        if (isset($response->result) && $response->result == true) {
            return response()->json([
                'success' => __('subscribe.success')
            ]);
        }
    }

    public function poolStats(Request $request)
    {
        abort_if(!request()->ajax(), 404);

        $coin = Coin::active()->where('code', $request->input('code'))->firstOrFail();

        $latest_block = $coin->poolStats()->latest('time')->first();

        return view('ratings._pool_stats', compact('coin', 'latest_block') + [
            'pool_stats' => RatingViewModel::poolStats($coin)
        ]);
    }

    public function poolStatsPeriod(Request $request)
    {
        abort_if(!request()->ajax(), 404);

        $coin = Coin::active()->where('code', $request->input('code'))->firstOrFail();

        return view('ratings._pool_stats_period', [
            'pool_stats' => RatingViewModel::poolStats($coin)
        ]);
    }

    /* Получение данных для раздела "Рейтинг пулов" */
    public function parsePoolData()
    {
        // Получаем параметр для дальнейшей работы
        if (preg_match('/var last_time = "(\d+)";var link_base/', file_get_contents('https://miningpoolstats.stream'), $matches)) {

            foreach (Coin::active()->with('ratings')->has('ratings')->get() as $coin) {
                $response = $this->client->get('https://data.miningpoolstats.stream/data/' . preg_replace('/\s+/', '', mb_strtolower($coin->title)) . '.js?t=' . $matches[1]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (!empty($result['data']) && count($result['data'])) {
                    $data = array_combine(array_column($result['data'], 'url'), $result['data']);

                    foreach ($coin->ratings as $pool) {
                        $link = rtrim($pool->link, '/');

                        if (isset($data[$link])) {
                            unset($data[$link]['history']);
                            $pool->coins()->updateExistingPivot($coin->id, [
                                'pool_data' => $data[$link],
                                'hashrate' => $data[$link]['hashrate'] ?? null,
                            ]);
                        }
                    }
                }
            }
        }
    }

    /* Получение данных для раздела "Оборудование" для расчету прибыли по майнеру и монете */
    public function parseProfitData()
    {
        function coinGeckoRate($coin_name, $client)
        {
            $coin_name = strtolower($coin_name);

            $response = $client->get("https://api.coingecko.com/api/v3/coins/$coin_name?localization=false&tickers=false&market_data=true&community_data=false&developer_data=false&sparkline=false");

            $result = json_decode($response->getBody()->getContents(), true);
            if (!isset($result['market_data'])) return;

            return [
                'id' => $coin_name,
                'exchange_rate' => $result['market_data']['current_price']['usd'],
                'exchange_rate24' => $result['market_data']['current_price']['usd'] + $result['market_data']['price_change_24h'],
                'not_mining' => true,
            ];
        }

        foreach (Coin::active()->get() as $coin) {
            if ($coin->whattomine_coin_id && $coin->parse_time < now()) {

                // Если монета не майниться, тянем курс с другого ресурсу
                if ($coin->code == 'eth') {
                    $result = coinGeckoRate($coin->title, $this->client);

                    // Для расчета прибыли по отдельной монете
                } else {
                    $response = $this->client->get("https://whattomine.com/coins/{$coin->whattomine_coin_id}.json?hr=10000&p=0&cost=0&span_br=24&span_d=24&commit=Calculate");

                    $result = json_decode($response->getBody()->getContents(), true);
                }

                $coin->profit_per_unit = isset($result['id']) ? $result : null;
                // end

                $response = $this->client->get("https://whattomine.com/coins/{$coin->whattomine_coin_id}");

                $crawler = new Crawler($response->getBody()->getContents());

                $coin->parse_time = now()->addMinute(20);
                $coin->whattomine_unit = $crawler->filter('#hr + .input-group-text')->text();
                $coin->update();
            }
        }

        function whattomineData(Equipment $equipment): string
        {
            return "https://whattomine.com/coins/{$equipment->coin->whattomine_coin_id}.json?hr={$equipment->hashrate}&p={$equipment->power}&cost=0.1&span_br=24&span_d=24&commit=Calculate";
        }

        foreach (Equipment::with('coin')->active()->get() as $equipment) {
            if ($equipment->coin->whattomine_coin_id && $equipment->parse_time < now()) {
                $response = $this->client->get(whattomineData($equipment));

                $result = json_decode($response->getBody()->getContents(), true);

                $equipment->profit_data = isset($result['id']) ? $result : null;
                $equipment->parse_time = now()->addMinute(20);
                $equipment->update();

                sleep(1);
            }
        }
    }

    /* Получение курсу основных валют для раздела "Конвертер криптовалют" */
    public function getCurrencyRate()
    {
        $this->getCoinByExchange();
        $this->getCoinChartData();
        $this->getPoolStats();
        $this->getStockMarket();

        $response = $this->client->get("https://cdn.cur.su/api/latest.json");

        $result = json_decode($response->getBody()->getContents());

        if (isset($result->rates)) {
            $data = array_intersect_key((array)$result->rates, array_flip(['USD', 'EUR', 'RUB', 'GBP']));

            setting(['currency_rate' => $data])->save();
        }
    }

    /* Получение информации по фондовом рынке для блока "Investment" */
    public function getStockMarket()
    {
        $market = [
            'Canaan' => 'CAN',
            'Bitfarms' => 'BITF',
            'Riot Platforms' => 'RIOT',
            'Hive Blockchain' => 'HIVE',
            'Hut 8 Mining Corp.' => 'HUT',
            'Core Scientific' => 'CRZWQ',
        ];

        $data = [];

        foreach ($market as $key => $item) {
            $response = $this->client->get("https://query2.finance.yahoo.com/v8/finance/chart/" . $item);

            $result = json_decode($response->getBody()->getContents());

            if (isset($result->chart->result[0]->meta)) {
                $meta = $result->chart->result[0]->meta;

                $data[$key] = [
                    'value' => $meta->regularMarketPrice,
                    'change' => $meta->regularMarketPrice - $meta->chartPreviousClose,
                ];
            }
        }

        setting(['stock_market' => $data])->save();
    }

    /* Получение данных по монете, для построение графика изменения цены за 1h */
    public function getCoinChartData()
    {
        foreach (Coin::active()->whereIn('code', config('app_data.pool_stats_coins'))->oldest()->get() as $coin) {
            $response = $this->client->get("https://query1.finance.yahoo.com/v8/finance/chart/" . strtoupper($coin->code) . "-USD?interval=15m");

            $result = json_decode($response->getBody()->getContents());

            $coin->chart_data = null;

            if (isset($result->chart->result[0])) {
                $new_result = $result->chart->result[0];

                $times = array_map(function ($value) {
                    return now()->parse($value)->setTimezone(config('app.timezone'))->format('Y-m-d H:i');
                }, $new_result->timestamp);

                $values = array_map(function ($value) {
                    return crypto_number_format($value, '');
                }, $new_result->indicators->quote[0]->open);

                $coin->chart_data = array_combine($times, $values);
            }

            $coin->update();
        }
    }

    /* Получение курсу валют по разным биржам */
    public function getCoinByExchange()
    {
        foreach (Coin::active()->whereIn('code', config('app_data.pool_stats_coins'))->oldest()->get() as $coin) {
            $response = $this->client->get("https://api.coinmarketcap.com/data-api/v3/cryptocurrency/market-pairs/latest?slug=" . Str::slug($coin->title) . "&start=1&quoteCurrencyId=825&limit=6&category=spot&centerType=cex&sort=cmc_rank_advanced");

            $result = json_decode($response->getBody()->getContents());

            $old_data = $coin->cost_by_exchange;

            $coin->cost_by_exchange = null;

            if (isset($result->data->marketPairs[0])) {
                $new_result = [];

                foreach ($result->data->marketPairs as $item) {
                    $new_result[$item->exchangeName] = [
                        'price' => $item->price,
                        'old' => $old_data[$item->exchangeName]['price'] ?? 0,
                    ];
                }

                $coin->cost_by_exchange = $new_result;
            }

            $coin->update();
        }
    }

    /* Получение данных по майнинг пулам с https://blockchair.com */
    public function getPoolStats()
    {
        $per_page = 100;

        foreach (Coin::active()->whereIn('code', config('app_data.pool_stats_coins'))->oldest()->get() as $coin) {
            $time_last_data = $coin->poolStats()->latest('time')->first()->time ?? now()->subYear()->format('Y-m-d');

            $query = "https://api.blockchair.com/" . Str::slug($coin->title) . "/blocks?s=time(asc)&q=time($time_last_data..)&limit=$per_page";

            $response = $this->client->get($query);

            $result = json_decode($response->getBody()->getContents());

            if (isset($result->context->total_rows) && $result->context->total_rows > 0) {
                $pages = round($result->context->total_rows / $per_page);

                for ($i = 0; $i <= $pages && $i <= 100; $i++) { // max offset 10000
                    $response = $this->client->get($query . '&offset=' . $i * $per_page);

                    $result = json_decode($response->getBody()->getContents());

                    if (!empty($result->data)) {
                        $data_to_save = [];

                        foreach ($result->data as $item) {
                            $data_to_save[] = [
                                'coin_id' => $coin->id,
                                'block_height' => $item->id,
                                'miner' => $item->guessed_miner ?? 'Unknown',
                                'difficulty' => $item->difficulty,
                                'time' => now()->parse($item->time),
                            ];
                        }

                        DB::table('pool_stats')->insert($data_to_save);
                    } else {
                        $i--;
                    }

                    // Current limits are 30 requests per minute and 1800 per hour.
                    sleep(2);
                }
            }

            // Удаление дубликатов
            $duplicated = $coin->poolStats()
                ->select('*', DB::raw('count(block_height) as block_height_count'))
                ->groupBy('miner', 'block_height')
                ->having('block_height_count', '>', 1)
                ->orderByDesc('block_height_count')
                ->get();

            foreach ($duplicated as $duplicate) {
                $coin->poolStats()->where('id', '<>', $duplicate->id)->where('block_height', $duplicate->block_height)->delete();
            }
        }

        // Удаление записей позднее года
        PoolStats::where('time', '<', now()->subYear(1)->setTimezone('UTC'))->delete();
    }
}

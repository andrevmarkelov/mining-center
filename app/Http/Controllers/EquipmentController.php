<?php

namespace App\Http\Controllers;

use Meta;
use Validator;
use Notification;
use App\Models\Coin;
use App\Models\Rating;
use App\Models\Algorithm;
use App\Models\Equipment;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Notifications\EquipmentForm;

class EquipmentController extends Controller
{
    public function index($coin = null)
    {
        if ($coin) {
            $coin = Coin::active()->where('alias', $coin)->firstOrFail();

            $metadata = $coin->getMeta('equipments');

            Meta::setTitle($metadata[app()->getLocale()]['meta_title'] ?? '');
            Meta::setDescription($metadata[app()->getLocale()]['meta_description'] ?? '');
        }

        $equipments = Equipment::with('coin')->active()
            ->when($coin, function ($query) use ($coin) {
                $query->where('coin_id', $coin->id);
            })
            ->when(request()->input('algorithm'), function ($query) {
                $query->whereHas('coin', function ($query) {
                    $query->whereHas('algorithm', function ($query) {
                        $query->where('title', request()->input('algorithm'));
                    });
                });
            })
            ->when(request()->input('manufacturer'), function ($query) {
                $query->whereHas('manufacturer', function ($query) {
                    $query->where('title', request()->input('manufacturer'));
                });
            })
            ->when(request()->input('hashrate_from'), function ($query) {
                $query->where('hashrate', '>=', request()->input('hashrate_from'));
            })
            ->when(request()->input('hashrate_to'), function ($query) {
                $query->where('hashrate', '<=', request()->input('hashrate_to'));
            })
            ->when(request()->input('power_from'), function ($query) {
                $query->where('power', '>=', request()->input('power_from'));
            })
            ->when(request()->input('power_to'), function ($query) {
                $query->where('power', '<=', request()->input('power_to'));
            })
            ->orderByDesc('id')->get();

        $equipments_total = Equipment::with('coin')->active()
            ->select('power', 'hashrate')->when($coin, function ($query) use ($coin) {
                $query->where('coin_id', $coin->id);
            })->get();

        Meta::includePackages('datatables', 'fixedcolumns', 'nouislider', 'select2');

        return view('equipments.index', compact('equipments', 'equipments_total', 'coin') + [
            'coins' => Coin::with('media')->active()->has('equipments')->get(),
            'algorithms' => Algorithm::pluck('title', 'id'),
            'manufacturers' => Manufacturer::pluck('title', 'id'),
            'page_title' => $metadata[app()->getLocale()]['meta_h1'] ?? '',
            'page_subtitle' => $metadata[app()->getLocale()]['subtitle'] ?? '',
            'page_description' => $metadata[app()->getLocale()]['description'] ?? '',
        ]);
    }

    public function show($alias)
    {
        $equipment = Equipment::active()->where('alias', $alias)->firstOrFail();

        static::openGraph($equipment);
        Meta::setTitle($equipment->meta_title ?: $equipment->title);
        Meta::setDescription($equipment->meta_description);
        Meta::includePackages('slick', 'owl-carousel', 'datatables', 'fixedcolumns');

        $related = Equipment::with('media')
            ->whereHas('coin', function ($query) use ($equipment) {
                $query->where('coin_id', $equipment->coin_id);
            })
            ->active()
            ->where('id', '<>', $equipment->id)
            ->limit(8)->get();

        $featured_pools = Rating::with('media')->active()->whereIn('id', setting('featured_pools') ?? [])->get();

        $coins_one_algorithm = Coin::active()
            ->whereHas('algorithm', function ($query) use ($equipment) {
                $query->where('algorithm_id', $equipment->coin->algorithm_id);
            })->get();

        return view('equipments.show', compact('equipment', 'related', 'featured_pools', 'coins_one_algorithm'));
    }

    public function quickView(Request $request)
    {
        abort_if(!$request->ajax(), 404);

        return view('equipments._quick_view', [
            'equipment' => Equipment::findOrFail($request->input('id'))
        ]);
    }

    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'telegram' => 'nullable|min:2',
            'comment' => 'nullable|min:2',
            'miner_id' => 'nullable|integer|exists:App\Models\\' . ($request->input('form_type') == 'data_centers' ? 'DataCenter' : 'Equipment') . ',id',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'errors' => $errors->all()
            ]);
        }

        Notification::route('mail', env('MAIL_TO'))->notify(new EquipmentForm($validator->validated()));

        if (empty($request->input('form_type'))) {
            $this->addLeadToBitrix24($validator->validated());
        }

        return response()->json([
            'success' => __('equipments.success')
        ]);
    }

    protected function addLeadToBitrix24($data)
    {
        $query_url = 'https://interhash.bitrix24.ru/rest/88/7kgi2p1r4mbxy3hu/crm.lead.add.json';
        $query_data = http_build_query([
            'fields' => [
                'TITLE' => 'Узнать стоимость оборудования',
                'NAME' => $data['name'],
                'COMMENTS' => $data['comment'] ?? '',
                'SOURCE_DESCRIPTION' => !empty($data['miner_id']) ? route('equipments.show', Equipment::find($data['miner_id'])->alias) : '',
                'EMAIL' => [
                    'n0' => [
                        "VALUE" => $data['email'],
                        "VALUE_TYPE" => "WORK"
                    ]
                ],
                'IM' => [
                    "n0" => [
                        "VALUE" => $data['telegram'] ?? '',
                        "VALUE_TYPE" => "TELEGRAM"
                    ]
                ]
            ],
            'params' => ["REGISTER_SONET_EVENT" => "Y"]
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $query_url,
            CURLOPT_POSTFIELDS => $query_data,
        ));
        $result = curl_exec($curl);
        curl_close($curl);
    }
}

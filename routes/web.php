<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataCenterController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\FirmwareController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\WikiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CryptoCalcController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('parse-pool-data', [HomeController::class, 'parsePoolData']);
Route::get('parse-profit-data', [HomeController::class, 'parseProfitData']);
Route::get('currency-rate', [HomeController::class, 'getCurrencyRate']);
Route::get('sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localizationRedirect', 'localeViewPath']
], function () {

    Route::get('subscribe', [HomeController::class, 'subscribe'])->name('subscribe');
    Route::post('subscribe', [HomeController::class, 'subscribeSend'])->name('subscribe.send');

    Route::get('pool-stats', [HomeController::class, 'poolStats'])->name('pool_stats');
    Route::get('pool-stats-period', [HomeController::class, 'poolStatsPeriod'])->name('pool_stats_period');

    Route::get('rss', [App\Http\Controllers\RssFeedController::class, 'yandex'])->name('rss_feed');
    Route::get('rss-google', [App\Http\Controllers\RssFeedController::class, 'google'])->name('rss_google_feed');

    // Страницы сайта
    Route::group([
        'middleware' => ['pageSpeed'] // languageByDefault
    ], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');

        Route::get('news', [NewsController::class, 'index'])->name('news');
        Route::get('news/category/{alias}', [NewsController::class, 'category'])->name('news.category');
        Route::get('news/{alias}', [NewsController::class, 'show'])->name('news.show');

        Route::post('ratings', [RatingController::class, 'send'])->name('ratings.send');
        Route::get('ratings/{alias}', [RatingController::class, 'show'])->name('ratings.show');

        Route::get('cloud-mining', [App\Http\Controllers\MiningController::class, 'index'])->name('mining');

        Route::get('firmwares', [FirmwareController::class, 'index'])->name('firmwares');
        Route::get('firmwares/{category}/{alias}', [FirmwareController::class, 'show'])->name('firmwares.show');
        Route::get('firmwares/{alias}', [FirmwareController::class, 'index'])->name('firmwares.category');
        Route::post('firmwares', [FirmwareController::class, 'send'])->name('firmwares.send');

        Route::get('data-centers', [DataCenterController::class, 'index'])->name('data_centers');
        Route::get('data-centers/country/{alias}', [DataCenterController::class, 'category'])->name('data_centers.country');
        Route::get('data-centers/city/{alias}', [DataCenterController::class, 'category'])->name('data_centers.city');
        Route::get('data-centers/{alias}', [DataCenterController::class, 'show'])->name('data_centers.show');
        Route::post('data-centers', [DataCenterController::class, 'send'])->name('data_centers.send');
        Route::post('data-centers/support', [DataCenterController::class, 'support'])->name('data_centers.support');

        Route::get('services', [ServiceController::class, 'index'])->name('services');
        Route::get('services/country/{alias}', [ServiceController::class, 'category'])->name('services.country');
        Route::get('services/city/{alias}', [ServiceController::class, 'category'])->name('services.city');
        Route::get('services/{alias}', [ServiceController::class, 'show'])->name('services.show');

        Route::get('hardware', [EquipmentController::class, 'index'])->name('equipments');
        Route::get('hardware/quick-view', [EquipmentController::class, 'quickView'])->name('equipments.quick_view');
        Route::get('hardware/coin/{alias}', [EquipmentController::class, 'index'])->name('equipments.coin');
        Route::get('hardware/{alias}', [EquipmentController::class, 'show'])->name('equipments.show');
        Route::post('hardware', [EquipmentController::class, 'send'])->name('equipments.send');

        Route::get('wiki', [WikiController::class, 'index'])->name('wiki');
        Route::get('wiki/category/{alias}', [WikiController::class, 'index'])->name('wiki.category');
        Route::get('wiki/{alias}', [WikiController::class, 'show'])->name('wiki.show');

        Route::get('contacts', [ContactController::class, 'index'])->name('contacts');
        Route::post('contacts', [ContactController::class, 'send'])->name('contacts.send');

        Route::get('crypto-calc', [CryptoCalcController::class, 'index'])->name('crypto_calc');
        Route::get('crypto-calc/{alias}', [CryptoCalcController::class, 'index'])->name('crypto_calc.coin');

        Route::get('pages/{alias}', [PageController::class, 'show'])->name('pages.show');
        Route::get('{alias}', [PageController::class, 'show'])->name('pages.show_short_url');
    });

    require_once __DIR__ . '/admin.php';
});

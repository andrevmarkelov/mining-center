<?php

use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CoinController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\CityController;

Route::group([
    'middleware' => 'auth',
    'prefix' => 'cabinet',
    'as' => 'admin.'
], function () {
    Route::get('home', [HomeController::class, 'index'])->name('home');
    Route::delete('destroy-gallery/{name}', [HomeController::class, 'destroyGallery'])->name('destroy_gallery');
    Route::post('upload-file', [HomeController::class, 'uploadFile'])->name('upload_file');

    Route::post('upload-editor', [HomeController::class, 'uploadEditor']);
    Route::post('destroy-editor', [HomeController::class, 'destroyEditor']);

    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show', 'create', 'store']);
    Route::resource('permissions', App\Http\Controllers\Admin\PermissionController::class)->except(['show']);
    Route::resource('roles', App\Http\Controllers\Admin\RoleController::class)->except(['show']);

    Route::get('profile/{user}/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('algorithms', App\Http\Controllers\Admin\AlgorithmController::class)->except(['show']);

    Route::resource('coins', CoinController::class)->except(['show']);
    Route::get('coin-metadata/{coin}/edit', [CoinController::class, 'editMetadata'])->name('coin_metadata.edit');
    Route::put('coin-metadata/{coin}', [CoinController::class, 'updateMetadata'])->name('coin_metadata.update');

    Route::resource('ratings', App\Http\Controllers\Admin\RatingController::class)->except(['show']);
    Route::resource('mining', App\Http\Controllers\Admin\MiningController::class)->except(['show']);

    Route::resource('firmwares', App\Http\Controllers\Admin\FirmwareController::class)->except(['show']);
    Route::resource('firmware-categories', App\Http\Controllers\Admin\FirmwareCategoryController::class)->names('firmware_categories')->except(['show']);

    Route::resource('countries', CountryController::class)->except(['show']);
    Route::get('country-metadata/{country}/edit', [CountryController::class, 'editMetadata'])->name('country_metadata.edit');
    Route::put('country-metadata/{country}', [CountryController::class, 'updateMetadata'])->name('country_metadata.update');

    Route::resource('cities', CityController::class)->except(['show']);
    Route::get('city-metadata/{city}/edit', [CityController::class, 'editMetadata'])->name('city_metadata.edit');
    Route::put('city-metadata/{city}', [CityController::class, 'updateMetadata'])->name('city_metadata.update');

    Route::resource('data-centers', App\Http\Controllers\Admin\DataCenterController::class)->names('data_centers')->except(['show']);
    Route::resource('services', App\Http\Controllers\Admin\ServiceController::class)->names('services')->except(['show']);

    Route::resource('wiki', App\Http\Controllers\Admin\WikiController::class)->except(['show']);
    Route::resource('wiki-categories', App\Http\Controllers\Admin\WikiCategoryController::class)->names('wiki_categories')->except(['show']);

    Route::resource('equipments', App\Http\Controllers\Admin\EquipmentController::class)->except(['show']);
    Route::resource('manufacturers', App\Http\Controllers\Admin\ManufacturerController::class)->except(['show']);

    Route::resource('news', App\Http\Controllers\Admin\NewsController::class)->except(['show']);
    Route::resource('news-categories', App\Http\Controllers\Admin\NewsCategoryController::class)->names('news_categories')->except(['show']);

    Route::resource('pages', App\Http\Controllers\Admin\PageController::class)->except(['show']);
    Route::resource('advertisings', App\Http\Controllers\Admin\AdvertisingController::class)->except(['show']);

    Route::get('settings/edit', [App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

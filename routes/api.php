<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('news', [App\Http\Controllers\Api\NewsController::class, 'index'])->name('api.news.index');
Route::get('news-categories', [App\Http\Controllers\Api\NewsCategoryController::class, 'index'])->name('api.news_categories.index');

Route::get('equipments', [App\Http\Controllers\Api\EquipmentController::class, 'index'])->name('api.equipments.index');
Route::get('coins', [App\Http\Controllers\Api\CoinController::class, 'index'])->name('api.coins.index');

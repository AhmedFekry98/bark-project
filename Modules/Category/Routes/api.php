<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;
use Modules\Category\Http\Controllers\ProviderController;
use Modules\Category\Http\Controllers\ServiceController;
use Modules\Category\Http\Controllers\ServiceRequestController;

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

Route::group([
    'prefix' => '/categories'
], function () {

    Route::group([
        'prefix' => 'services'
    ], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/create', [ServiceController::class, 'store']);
        Route::post('/requests/create', [ServiceRequestController::class, 'store']);
        Route::get('/{id}', [ServiceController::class, 'show']);
        Route::get('/{id}/providers', [ProviderController::class, 'categoryProviders']);
        Route::post('/{id}/update', [ServiceController::class, 'update']);
        Route::post('/{id}/delete', [ServiceController::class, 'destroy']);
    });

    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/create', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::post('/{id}/update', [CategoryController::class, 'update']);
    Route::post('/{id}/delete', [CategoryController::class, 'destroy']);
});

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

        Route::get('/', [ServiceController::class, 'index']);

        Route::post('/create', [ServiceController::class, 'store'])
            ->middleware(['auth:sanctum', "role:admin"]);

        Route::get('/requests', [ServiceRequestController::class, 'index'])->middleware(['auth:sanctum'])
            ->middleware(['auth:sanctum', "role:provider"]);

        Route::post('/requests/create', [ServiceRequestController::class, 'store'])->middleware(['auth:sanctum'])
            ->middleware(['auth:sanctum', "role:customer"]);

        //  deprcated now
        Route::post('/requests/{id}/hire', [ServiceRequestController::class, 'hire'])->middleware(['auth:sanctum']);
        Route::get('/requests/{id}/providers', [ProviderController::class, 'categoryProviders']);

        Route::get('/{id}', [ServiceController::class, 'show']);

        Route::post('/{id}/update', [ServiceController::class, 'update'])
            ->middleware(['auth:sanctum', "role:admin"]);

        Route::post('/{id}/delete', [ServiceController::class, 'destroy'])
            ->middleware(['auth:sanctum', "role:admin"]);
    });

    Route::get('/', [CategoryController::class, 'index']);

    Route::post('/create', [CategoryController::class, 'store'])
        ->middleware(['auth:sanctum', "role:admin"]);

    Route::get('/{id}', [CategoryController::class, 'show']);

    Route::post('/{id}/update', [CategoryController::class, 'update'])
        ->middleware(['auth:sanctum', "role:admin"]);

    Route::post('/{id}/delete', [CategoryController::class, 'destroy'])
        ->middleware(['auth:sanctum', "role:admin"]);
});

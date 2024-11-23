<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Badge\Http\Controllers\BadgeController;

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
    'prefix'        => '/badges',
    // 'middleware'    => ['auth:sanctum', 'role:admin']
], function () {

    Route::get('/'         , [BadgeController::class, 'index']);
    Route::post('/create'       , [BadgeController::class, 'store']);
    Route::post('/assign'  , [BadgeController::class, 'assign']);
    Route::post('/{badge}/update'    , [BadgeController::class, 'update']);
    Route::post('/{badge}/delete'    , [BadgeController::class, 'destroy']);

});

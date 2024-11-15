<?php

use Illuminate\Support\Facades\Route;
use Modules\Credits\Http\Controllers\CreditPricingPlanController;

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

Route::prefix('credits')->group(function() {

    Route::get('/pricing-plans', [CreditPricingPlanController::class, 'index']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function() {
        
        Route::post('/pricing-plans/create', [CreditPricingPlanController::class, 'store']);
        Route::post('/pricing-plans/{plan}/update', [CreditPricingPlanController::class, 'update']);
        Route::post('/pricing-plans/{plan}/delete', [CreditPricingPlanController::class, 'destroy']);
    });
});
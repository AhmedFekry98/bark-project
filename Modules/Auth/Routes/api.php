<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\ProfessionController;
use Modules\Auth\Http\Controllers\ProfileController;

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

Route::get('/professions', ProfessionController::class);

Route::group([
    'prefix' => 'auth',
], function () {
    Route::post('/register/{role}', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('check-otp', [AuthController::class, 'checkOTP']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::post('change-password', [AuthController::class, 'changePassword'])->middleware("auth:sanctum");
    Route::post('/verified/send', [AuthController::class, 'sendVerifiedCode'])->middleware('auth:sanctum');
    Route::post('/verified/{code}', [AuthController::class, 'verified'])->middleware('auth:sanctum');
});

Route::get('/profiles/{role}', [ProfileController::class, 'index'])->middleware(['auth:sanctum'])
    ->where('role', implode('|', array_keys(config('roles', ['admin' => ['*']]))));

Route::group([
    'prefix'    => 'profile',
    'middleware' => "auth:sanctum"
], function () {

    Route::get('/', [ProfileController::class, 'show']);
    Route::post('/update', [ProfileController::class, 'update']);
});

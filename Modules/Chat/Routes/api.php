<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Chat\Http\Controllers\UserChatController;

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

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user/chats', [UserChatController::class, 'index']);
    Route::post('/user/send-message', [UserChatController::class, 'store']);
    Route::get('/users/{user}/chat', [UserChatController::class, 'show']);
    Route::delete('/users/{user}/chat', [UserChatController::class, 'destroy']);
});

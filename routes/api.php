<?php

use App\Http\Controllers\API\V1\Auth\TokenBasedAuth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::prefix("v1")->group(function () {
    Route::post('auth/login', \App\Http\Controllers\API\V1\Auth\LoginController::class)->middleware('guest');
    Route::post('auth/create-token',  [TokenBasedAuth::class, 'createToken'])->middleware('guest');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/user', App\Http\Controllers\API\V1\Auth\GetCurrentUser::class);
        Route::post('auth/logout', App\Http\Controllers\API\V1\Auth\LogoutController::class);
        Route::post('auth/revoke-token', [TokenBasedAuth::class, 'revokeToken']);

        Route::resource('user', App\Http\Controllers\API\V1\UserController::class);
    });


});

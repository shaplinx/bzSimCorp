<?php

use App\Http\Controllers\API\V1\Auth\TokenBasedAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\Auth\LadderController;
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
        Route::get('auth/all-roles', [LadderController::class, "getAllRoles"] );
        Route::get('auth/all-permissions', [LadderController::class, "getAllPermissions"] );

        Route::resource('user', App\Http\Controllers\API\V1\UserController::class);
        Route::prefix("finance")->group(function() {
            Route::resource('bank', App\Http\Controllers\API\V1\Finance\BankController::class);
            Route::resource('transaction-category', App\Http\Controllers\API\V1\Finance\TransactionCategoryController::class);
            Route::resource('transaction', App\Http\Controllers\API\V1\Finance\TransactionController::class);
            Route::resource('loan', App\Http\Controllers\API\V1\Finance\LoanController::class);
            Route::get('loan/{loan}/payment', [App\Http\Controllers\API\V1\Finance\LoanController::class,"listPayment"]);
            Route::post('loan/{loan}/payment', [App\Http\Controllers\API\V1\Finance\LoanController::class,"addPayment"]);
            Route::patch('loan/{loan}/payment/{bank_mutation}', [App\Http\Controllers\API\V1\Finance\LoanController::class,"updatePayment"]);
            Route::delete('loan/{loan}/payment/{bank_mutation}', [App\Http\Controllers\API\V1\Finance\LoanController::class,"deletePayment"]);
        });
        Route::prefix("document")->group(function() {
            Route::resource('document', App\Http\Controllers\API\V1\Document\DocumentController::class);
            Route::resource('document-category', App\Http\Controllers\API\V1\Document\DocumentCategoryController::class);
        });

    });


});

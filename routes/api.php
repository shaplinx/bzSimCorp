<?php

use App\Http\Controllers\Auth\TokenBasedAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LadderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Documents\InstitutionController;
use App\Http\Controllers\Documents\ClassificationController;
use App\Http\Controllers\Documents\LetterController;
use App\Http\Middleware\EnsureCanExport;

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
    Route::post('auth/login', LoginController::class)->middleware('guest');
    Route::post('auth/create-token', [TokenBasedAuth::class, 'createToken'])->middleware('guest');
    Route::get('documents/letters/{letter}/download', [LetterController::class, 'download']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('auth/user', App\Http\Controllers\Auth\GetCurrentUser::class);
        Route::post('auth/logout', App\Http\Controllers\Auth\LogoutController::class);
        Route::post('auth/revoke-token', [TokenBasedAuth::class, 'revokeToken']);
        Route::get('auth/all-roles', [LadderController::class, "getAllRoles"]);
        Route::get('auth/all-permissions', [LadderController::class, "getAllPermissions"]);
        Route::middleware(EnsureCanExport::class)->get('user/export', [UserController::class,'export']);
        Route::resource('user', UserController::class);


        Route::prefix('documents')->group(function () {
            Route::middleware(EnsureCanExport::class)->get('institutions/export', [InstitutionController::class,'export']);
            Route::middleware(EnsureCanExport::class)->get('classifications/export', [ClassificationController::class,'export']);
            Route::middleware(EnsureCanExport::class)->get('letters/export', [LetterController::class,'export']);
            Route::apiResource('institutions', InstitutionController::class);
            Route::apiResource('classifications', ClassificationController::class);
            Route::apiResource('letters', LetterController::class);
        });
    });
});

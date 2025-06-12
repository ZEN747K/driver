<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\AdminAuthController;

Route::post('/login', [AdminAuthController::class, 'login'])
    ->middleware('verify.admin.credentials');

Route::prefix('data')->group(function () {
    Route::post('/api/login', [AdminAuthController::class, 'login'])
        ->middleware('verify.admin.credentials');

    Route::prefix('api')->middleware('auth.api')->group(function () {
        Route::get('/drivers', [DriverController::class, 'index']);
        Route::post('/drivers', [DriverController::class, 'store']);
        Route::get('/drivers/{driver}', [DriverController::class, 'show']);
        Route::get('/driver{driver}', [DriverController::class, 'show'])->whereNumber('driver');
    });
});

Route::middleware('auth.api')->group(function () {
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::post('/drivers', [DriverController::class, 'store']);
    Route::get('/drivers/{driver}', [DriverController::class, 'show']);
});


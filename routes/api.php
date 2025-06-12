<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\AdminAuthController;

Route::post('/login', [AdminAuthController::class, 'login'])
    ->middleware('verify.admin.credentials');

Route::middleware('auth.api')->group(function () {
    Route::get('/drivers', [DriverController::class, 'index']);
    Route::post('/drivers', [DriverController::class, 'store']);
    Route::get('/drivers/{driver}', [DriverController::class, 'show']);
});


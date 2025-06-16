<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\get_userAPI;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\api\DriverAPICtrl;

// Admin API
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

Route::middleware(['auth:sanctum', 'admin.auth'])->prefix('admin')->group(function () {
    Route::post('/logout', [AdminAuthController::class, 'logout']);

    Route::get('/user', [get_userAPI::class, 'index']);

    Route::get('/user/{id}', [get_userAPI::class,'show']);
});

// End Admin API

// Driver API
Route::prefix('driver')->group(function () {
    // Public routes
    Route::post('/login', [DriverAuthController::class, 'login']);
    Route::post('/newUser', [DriverAPICtrl::class, 'store']);


    // Protected routes (token required)
    Route::get('/profile', [DriverAuthController::class, 'getProfile']);
    Route::get('/status', [DriverAuthController::class, 'checkStatus']);
    // Route::get('/list', [DriverAPICtrl::class, 'index']);
    // Route::get('/{id}', [DriverAPICtrl::class, 'show']);
    // Route::put('/{id}/status', [DriverAPICtrl::class, 'updateStatus']);
});

// End Driver API

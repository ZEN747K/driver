<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\get_userAPI;

Route::middleware('jwt.auth')->group(function () {
    Route::get('/user', [get_userAPI::class, 'index']);
    Route::get('/user/{id}', [get_userAPI::class, 'show']);
});

Route::post('/login', [App\Http\Controllers\AdminAuthController::class, 'login'])
    ->middleware('verify.admin.credentials');


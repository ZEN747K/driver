<?php

use App\Http\Controllers\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::get('/we', function () {
    return view('welcome');
});

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->middleware('verify.admin.credentials')
    ->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth:admin', 'validate.request'])->group(function () {
    Route::resource('drivers', App\Http\Controllers\DriverController::class);
    Route::put('drivers/{driver}/approve', [App\Http\Controllers\DriverController::class, 'approve'])->name('drivers.approve');
    Route::get('drivers/{driver}/download/{field}', [App\Http\Controllers\DriverController::class, 'download'])->name('drivers.download');

    Route::resource('admins', App\Http\Controllers\AdminController::class);
});

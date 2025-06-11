<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('drivers.index');
});

Route::get('/we', function () {
    return view('welcome');
});

Route::resource('drivers', App\Http\Controllers\DriverController::class);
Route::put('drivers/{driver}/approve', [App\Http\Controllers\DriverController::class, 'approve'])->name('drivers.approve');

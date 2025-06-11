<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('drivers.index');
});

Route::resource('drivers', App\Http\Controllers\DriverController::class);
Route::put('drivers/{driver}/approve', [App\Http\Controllers\DriverController::class, 'approve'])->name('drivers.approve');
Route::get('drivers/{driver}/download/{field}', [App\Http\Controllers\DriverController::class, 'download'])->name('drivers.download');

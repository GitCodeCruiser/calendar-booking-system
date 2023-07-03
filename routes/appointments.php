<?php
// appointments.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;

Route::prefix('appointments')->group(function () {
    Route::get('/get', [AppointmentController::class, 'get'])->name('appointment.get');
    Route::post('/store', [AppointmentController::class, 'store'])->name('appointment.store');
});

<?php

use App\Http\Controllers\AuthenticateController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthenticateController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::controller(BookingController::class)->group(function () {
        Route::post('/booking', 'booking')->name('user.booking');
        Route::post('/bookings', 'bookings')->name('user.bookings');
    });
});

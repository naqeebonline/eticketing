<?php

use App\Http\Controllers\Api\V1\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookingController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\RouteSearchController;
use App\Http\Controllers\Api\V1\SeatController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Public
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/otp/send', [AuthController::class, 'sendOtp']);
    Route::post('auth/otp/verify', [AuthController::class, 'verifyOtp']);

    Route::get('routes/search', [RouteSearchController::class, 'search']);
    Route::get('routes/cities', [RouteSearchController::class, 'cities']);
    Route::get('schedules/{schedule}/seats', [SeatController::class, 'index']);
    Route::post('schedules/{schedule}/seats/hold', [SeatController::class, 'hold']);
    Route::post('bookings/verify-qr', [BookingController::class, 'verifyQr']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);

        Route::apiResource('bookings', BookingController::class)->only(['index', 'store', 'show']);
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);
        Route::post('bookings/{booking}/payments', [PaymentController::class, 'store']);

        Route::middleware('role:super_admin,admin')->prefix('admin')->group(function () {
            Route::get('dashboard', [AdminDashboardController::class, 'index']);
        });
    });
});

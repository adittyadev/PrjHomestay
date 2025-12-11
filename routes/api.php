<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;
//use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\CheckoutController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API Routes untuk Tamu
Route::apiResource('tamu', TamuController::class);
//Route::apiResource('rooms', RoomController::class);
Route::apiResource('bookings', BookingController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('checkins', CheckinController::class);
Route::apiResource('checkouts', CheckoutController::class);

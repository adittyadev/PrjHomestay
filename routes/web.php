<?php

use App\Http\Controllers\Web\RoomController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TamuController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tamu', TamuController::class);
Route::resource('rooms', RoomController::class);

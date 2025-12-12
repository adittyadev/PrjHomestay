<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\TamuController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\CheckinController;
use App\Http\Controllers\Web\CheckoutController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Web\UserRoomController;
use App\Http\Controllers\Web\UserBookingController;
use App\Http\Controllers\Web\UserPaymentController;
use App\Http\Controllers\Web\AdminDashboardController;


/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT
|--------------------------------------------------------------------------
*/

// FORM LOGIN
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');

// PROSES LOGIN
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// LOGOUT
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
Route::post('/register-process', [RegisterController::class, 'register'])->name('register.process');


/*
|--------------------------------------------------------------------------
| REDIRECT SETELAH LOGIN
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {

    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
});


/*
|--------------------------------------------------------------------------
| AREA ADMIN (TANPA MIDDLEWARE)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard')
        ->middleware('auth');

    // CRUD TAMU
    Route::resource('tamu', TamuController::class)->middleware('auth');

    // CRUD KAMAR
    Route::resource('rooms', RoomController::class)->middleware('auth');

    // CRUD BOOKING
    Route::resource('bookings', BookingController::class)->middleware('auth');

    // PEMBAYARAN
    Route::resource('payments', PaymentController::class)->middleware('auth');
    Route::post('/payments/{id}/status', [PaymentController::class, 'updateStatus'])
        ->name('payments.status')
        ->middleware('auth');

    // CHECKIN
    Route::get('/checkin', [CheckinController::class, 'index'])
        ->name('admin.checkin.index')
        ->middleware('auth');

    Route::post('/checkin/proses/{id}', [CheckinController::class, 'proses'])
        ->name('admin.checkin.proses')
        ->middleware('auth');

    // CHECKOUT
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('admin.checkout.index')
        ->middleware('auth');

    Route::post('/checkout/proses/{id}', [CheckoutController::class, 'proses'])
        ->name('admin.checkout.proses')
        ->middleware('auth');
});



/*
|--------------------------------------------------------------------------
| AREA USER (TANPA MIDDLEWARE)
|--------------------------------------------------------------------------
*/

Route::prefix('user')->group(function () {

    Route::get('/dashboard', function () {

        if (!Auth::check() || Auth::user()->role !== 'user') {
            return redirect('/login')->with('error', 'Akses ditolak!');
        }

        return view('user.dashboard');
    })->name('user.dashboard');

    // Melihat kamar
    Route::get('/rooms', [UserRoomController::class, 'index'])
        ->name('user.rooms')
        ->middleware('auth');

    // Booking
    Route::get('/booking', [UserBookingController::class, 'index'])
        ->name('user.booking')
        ->middleware('auth');

    Route::post('/booking/simpan', [UserBookingController::class, 'store'])
        ->name('user.booking.store')
        ->middleware('auth');

    // Pembayaran
    Route::get('/payments', [UserPaymentController::class, 'index'])
        ->name('user.payments')
        ->middleware('auth');
});

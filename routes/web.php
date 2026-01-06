<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Web\RoomController;
use App\Http\Controllers\Web\TamuController;
use App\Http\Controllers\Web\BookingController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\CheckinController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\AdminDashboardController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\BookingController as UserBookingController;
use App\Http\Controllers\User\PaymentController as UserPaymentController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT
|--------------------------------------------------------------------------
*/

// FORM LOGIN
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::get('/admin/login', [LoginController::class, 'showLogin'])->name('admin.login');

// PROSES LOGIN
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.process');

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
    Route::post('/bookings/{id}/status', [BookingController::class, 'updateStatus'])
        ->name('bookings.updateStatus');

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

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('user.dashboard')
        ->middleware('auth');

    // BOOKING KAMAR
    Route::get('/booking', [UserBookingController::class, 'create'])
        ->name('booking.create');

    Route::post('/booking', [UserBookingController::class, 'store'])
        ->name('booking.store');

    // PEMBAYARAN
    Route::get('/payment/{booking}', [UserPaymentController::class, 'create'])
        ->name('payment.create');

    Route::post('/payment/{booking}', [UserPaymentController::class, 'store'])
        ->name('payment.store');

    // PROFIL USER
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('user.profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('user.profile.edit');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('user.profile.update');
});

Route::get('/test-wa-token', function () {
    return env('WA_TOKEN');
});
Route::get('/test-wa-config', function () {
    return config('whatsapp.token');
});

Route::get('/test-wa', [PaymentController::class, 'testWa']);

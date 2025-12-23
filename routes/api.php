    <?php

    use App\Http\Controllers\Api\AuthController;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Api\DashboardController;
    use App\Http\Controllers\Api\RoomController;
    use App\Http\Controllers\Api\BookingController;
    use App\Http\Controllers\Api\PaymentController;
    use App\Http\Controllers\Api\ProfileController; 
    use App\Http\Controllers\Api\RegisterController;

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard', [DashboardController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->get('/user/dashboard', function (Request $request) {
        $user = $request->user();
        $tamu = $user->tamu;

        $bookingAktif = \App\Models\Booking::where('tamu_id', $tamu->id)
            ->whereIn('status_booking', ['confirmed', 'checkin'])
            ->count();



        $menungguPembayaran = \App\Models\Booking::where('tamu_id', $tamu->id)
            ->whereDoesntHave('payment')
            ->count();

        return response()->json([
            'booking_aktif' => $bookingAktif,
            'menunggu_pembayaran' => $menungguPembayaran
        ]);
    });

    Route::middleware('auth:sanctum')->get('/rooms', [RoomController::class, 'index']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/user/bookings', [BookingController::class, 'store']);
        Route::get('/user/bookings', [BookingController::class, 'index']);
        Route::get('/booking/{id}', [BookingController::class, 'show']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/payments', [PaymentController::class, 'store']);
        Route::post('/payments/{id}/upload-bukti', [PaymentController::class, 'uploadBuktiTransfer']);
        Route::get('/payments/user', [PaymentController::class, 'userPayments']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [ProfileController::class, 'index']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
        Route::post('/logout', [ProfileController::class, 'logout']);
    });

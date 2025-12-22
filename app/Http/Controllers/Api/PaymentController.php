<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'metode' => 'required|in:transfer,dana,ovo,gopay',
            'jumlah' => 'required|numeric',
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_transfer')->store('payments', 'public');

        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'metode' => $request->metode,
            'jumlah' => $request->jumlah,
            'bukti_transfer' => $path,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pembayaran berhasil dikirim',
            'data' => $payment,
        ]);
    }


    public function userPayments(Request $request)
    {
        $user = $request->user();

        $payments = Payment::with('booking.room')
            ->whereHas('booking', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return response()->json($payments);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Booking $booking)
    {
        // Pastikan booking milik user
        if ($booking->tamu_id !== Auth::user()->tamu->id) {
            abort(403);
        }

        return view('user.payment.create', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Pastikan booking milik user
        if ($booking->tamu_id !== Auth::user()->tamu->id) {
            abort(403);
        }

        $request->validate([
            'metode' => 'required',
            'bukti_transfer' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $path = $request->file('bukti_transfer')
            ->store('bukti_pembayaran', 'public');

        Payment::create([
            'booking_id' => $booking->id,
            'metode' => $request->metode,
            'jumlah' => $booking->total_bayar,
            'bukti_transfer' => $path,
            'status' => 'pending'
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Bukti pembayaran berhasil dikirim');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function create()
    {
        // Ambil kamar yang tersedia
        $rooms = Room::where('status', 'available')->get();

        return view('user.booking.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($request->room_id);
        $tamu = Auth::user()->tamu;

        // Hitung jumlah hari
        $days = Carbon::parse($request->check_in)
            ->diffInDays(Carbon::parse($request->check_out));

        $total = $days * $room->harga;

        Booking::create([
            'tamu_id' => $tamu->id,
            'room_id' => $room->id,
            'tanggal_booking' => now(),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_bayar' => $total,
            'status_booking' => 'pending'
        ]);

        return redirect()->route('user.dashboard')
            ->with('success', 'Booking berhasil, silakan lakukan pembayaran');
    }
}

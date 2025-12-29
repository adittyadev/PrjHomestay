<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use App\Models\Tamu;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // list booking user
    public function index(Request $request)
    {
        $bookings = Booking::with('room')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $bookings
        ]);
    }

    // simpan booking
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $user = $request->user();
        $tamu = $user->tamu;

        $room = Room::findOrFail($request->room_id);

        $days = Carbon::parse($request->check_in)
            ->diffInDays(Carbon::parse($request->check_out));

        $total = $days * $room->harga;

        $booking = Booking::create([
            'user_id' => $user->id, // ✅ WAJIB
            'tamu_id' => $tamu->id,
            'room_id' => $room->id,
            'tanggal_booking' => now(),
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_bayar' => $total,
            'status_booking' => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking berhasil',
            'booking' => $booking,
        ], 201);
    }


    public function show($id)
    {
        $booking = Booking::with(['room', 'user', 'tamu']) // ✅ TAMBAHKAN INI
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $booking
        ]);
    }
}

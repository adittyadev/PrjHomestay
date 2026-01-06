<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['tamu', 'room'])->get();
        return view('bookings.index', compact('bookings'));
    }

    public function create()
    {
        $tamu = Tamu::all();
        $rooms = Room::where('status', 'available')->get();
        return view('bookings.create', compact('tamu', 'rooms'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'room_id' => 'required',
            'checkin' => 'required|date',
            'checkout' => 'required|date|after:checkin',
        ]);

        $user = Auth::user();
        $tamu = $user->tamu;

        if (!$tamu) {
            return response()->json([
                'message' => 'Data tamu belum tersedia'
            ], 422);
        }

        $room = Room::findOrFail($r->room_id);

        $hari = (strtotime($r->checkout) - strtotime($r->checkin)) / 86400;
        if ($hari <= 0) $hari = 1;

        $total = $hari * $room->harga;

        $booking = Booking::create([
            'tamu_id' => $tamu->id, // ✅ AMAN
            'room_id' => $room->id,
            'check_in' => $r->checkin,
            'check_out' => $r->checkout,
            'total_bayar' => $total,
            'status_booking' => 'pending'
        ]);

        $room->update(['status' => 'booked']);

        return response()->json([
            'message' => 'Booking berhasil',
            'data' => $booking
        ]);
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $tamu = Tamu::all();
        $rooms = Room::all();

        return view('bookings.edit', compact('booking', 'tamu', 'rooms'));
    }

    public function update(Request $r, $id)
    {
        $booking = Booking::findOrFail($id);

        $booking->update($r->all());

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Booking berhasil dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status_booking' => 'required|in:selesai,cancelled'
        ]);

        $booking->update([
            'status_booking' => $request->status_booking
        ]);

        // 🔥 Jika booking selesai / cancelled → kamar jadi available lagi
        if (in_array($request->status_booking, ['selesai', 'cancelled'])) {
            if ($booking->room) {
                $booking->room->update([
                    'status' => 'available'
                ]);
            }
        }

        return back()->with('success', 'Status booking berhasil diperbarui');
    }

    // BookingController.php
    public function show($id)
    {
        $booking = Booking::with(['tamu', 'room'])->findOrFail($id);
        return view('bookings.show', compact('booking'));
    }
}

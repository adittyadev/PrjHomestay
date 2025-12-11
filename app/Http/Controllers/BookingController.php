<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::with(['guest', 'room'])->get();
    }

    public function store(Request $request)
    {
        $room = Room::findOrFail($request->room_id);

        // Hitung total bayar (per malam)
        $days = (new \Carbon\Carbon($request->check_in))
            ->diffInDays(new \Carbon\Carbon($request->check_out));

        $total = $days * $room->harga;

        $booking = Booking::create([
            'guest_id' => $request->guest_id,
            'room_id'  => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_bayar' => $total,
            'status_booking' => 'pending'
        ]);

        // Update status kamar → booked
        $room->update(['status' => 'booked']);

        return response()->json($booking, 201);
    }

    public function show($id)
    {
        return Booking::with(['guest', 'room'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update($request->all());

        return response()->json($booking);
    }

    public function destroy($id)
    {
        Booking::destroy($id);
        return response()->json(['message' => 'Booking deleted']);
    }
}

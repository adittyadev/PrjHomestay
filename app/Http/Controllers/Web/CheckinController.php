<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;

class CheckinController extends Controller
{
    // Menampilkan semua booking yang siap checkin
    public function index()
    {
        // Booking yang sudah bayar dan status = booked
        $bookings = Booking::where('status_booking', 'booked')->get();

        return view('admin.checkin.index', compact('bookings'));
    }

    // Proses checkin
    public function proses($id)
    {
        $booking = Booking::findOrFail($id);

        // Ubah status booking
        $booking->status_booking = 'checkin';
        $booking->save();

        // Update status kamar
        $room = Room::find($booking->room_id);
        $room->status = 'booked';
        $room->save();

        return redirect()->route('admin.checkin.index')->with('success', 'Tamu berhasil Check-In!');
    }
}

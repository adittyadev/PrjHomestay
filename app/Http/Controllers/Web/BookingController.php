<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Tamu;
use Illuminate\Http\Request;

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
            'tamu_id' => 'required',
            'room_id' => 'required',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($r->room_id);

        // Hitung total bayar
        $hari = (strtotime($r->check_out) - strtotime($r->check_in)) / 86400;
        if ($hari <= 0) $hari = 1;

        $total = $hari * $room->harga;

        Booking::create([
            'tamu_id' => $r->tamu_id,
            'room_id' => $r->room_id,
            'tanggal_booking' => now(),
            'check_in' => $r->check_in,
            'check_out' => $r->check_out,
            'total_bayar' => $total,
            'status_booking' => 'pending'
        ]);

        // Set kamar menjadi booked
        $room->update([
            'status' => 'booked'
        ]);

        return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat!');
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
}

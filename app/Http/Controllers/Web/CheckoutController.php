<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Checkout;

class CheckoutController extends Controller
{
    // Tampilkan daftar booking yang siap checkout
    public function index()
    {
        // Booking yang sudah checkin tapi belum checkout
        $bookings = Booking::where('status_booking', 'checkin')->get();

        return view('admin.checkout.index', compact('bookings'));
    }

    // Proses checkout
    public function proses($id)
    {
        $booking = Booking::findOrFail($id);

        // Simpan riwayat checkout
        Checkout::create([
            'booking_id' => $booking->id,
            'tanggal_checkout' => now(),
        ]);

        // Update status booking
        $booking->update([
            'status_booking' => 'checkout'
        ]);

        // Update room -> kembali available
        $room = Room::find($booking->room_id);
        $room->status = 'available';
        $room->save();

        return redirect()->route('admin.checkout.index')
            ->with('success', 'Tamu berhasil Checkout!');
    }
}

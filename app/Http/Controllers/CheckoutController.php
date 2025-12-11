<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\Booking;
use App\Models\Room;

class CheckoutController extends Controller
{
    public function index()
    {
        return Checkout::with('booking')->get();
    }

    public function store(Request $request)
    {
        $checkout = Checkout::create([
            'booking_id'       => $request->booking_id,
            'tanggal_checkout' => now()
        ]);

        // Update kamar → available lagi
        $booking = Booking::find($request->booking_id);
        $room = Room::find($booking->room_id);
        $room->update(['status' => 'available']);

        return response()->json($checkout, 201);
    }

    public function show($id)
    {
        return Checkout::with('booking')->findOrFail($id);
    }
}

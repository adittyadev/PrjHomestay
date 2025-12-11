<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Checkin;
use App\Models\Booking;

class CheckinController extends Controller
{
    public function index()
    {
        return Checkin::with('booking')->get();
    }

    public function store(Request $request)
    {
        $checkin = Checkin::create([
            'booking_id'      => $request->booking_id,
            'tanggal_checkin' => now()
        ]);

        // Update status booking → selesai check-in
        $booking = Booking::find($request->booking_id);
        $booking->update(['status_booking' => 'selesai']);

        return response()->json($checkin, 201);
    }

    public function show($id)
    {
        return Checkin::with('booking')->findOrFail($id);
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        // Ambil data tamu dari user login
        $tamu = Auth::user()->tamu;

        // Ambil booking milik user saja
        $bookings = Booking::with(['room', 'payment'])
            ->where('tamu_id', $tamu->id)
            ->orderBy('tanggal_booking', 'desc')
            ->get();

        return view('user.dashboard', compact('bookings'));
    }
}

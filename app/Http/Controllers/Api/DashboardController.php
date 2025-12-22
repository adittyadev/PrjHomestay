<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

class DashboardController extends Controller
{
    /**
     * Dashboard tamu
     * GET /api/user/dashboard
     */
    public function dashboard(Request $request)
    {
        // Ambil ID tamu yang sedang login
        $tamuId = Auth::id();

        // Jika tidak login
        if (!$tamuId) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        /**
         * Booking aktif:
         * - pending  -> baru booking
         * - booked   -> sudah dibayar
         * - checkin  -> tamu sedang menginap
         */
        $bookingAktif = Booking::where('tamu_id', $tamuId)
            ->whereIn('status_booking', [
                'pending',
                'booked',
                'checkin'
            ])
            ->count();

        /**
         * Menunggu pembayaran
         * - hanya status pending
         */
        $menungguPembayaran = Booking::where('tamu_id', $tamuId)
            ->where('status_booking', 'pending')
            ->count();

        return response()->json([
            'booking_aktif' => $bookingAktif,
            'menunggu_pembayaran' => $menungguPembayaran
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Tamu;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $rooms = Room::count();
        $tamu = Tamu::count();
        $bookings = Booking::count();
        $pending = Payment::where('status', 'pending')->count();

        // Grafik booking 7 hari terakhir
        $grafik = Booking::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('COUNT(*) as jumlah')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('admin.dashboard', [
            'rooms' => $rooms,
            'tamu' => $tamu,
            'bookings' => $bookings,
            'pending' => $pending,
            'grafik' => [
                'tanggal' => $grafik->pluck('tanggal'),
                'jumlah' => $grafik->pluck('jumlah'),
            ]
        ]);
    }
}

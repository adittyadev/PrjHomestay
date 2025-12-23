<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
    public function index()
    {
        // Tampilkan semua kamar (available dan booked)
        return response()->json(
            Room::all()
        );
    }
}

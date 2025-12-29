<?php

// ============================================
// 1. CONTROLLER: app/Http/Controllers/HomeController.php
// ============================================

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil semua data kamar dengan relasi reviews dan rata-rata rating
        $rooms = Room::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with(['reviews' => function ($query) {
                $query->with(['user', 'replies.user'])
                    ->latest()
                    ->take(3); // Ambil 3 review terbaru per kamar
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('rooms'));
    }
}

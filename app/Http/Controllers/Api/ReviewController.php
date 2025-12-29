<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Ambil semua review berdasarkan kamar
     */
    public function index($roomId)
    {
        $reviews = Review::with(['user:id,name', 'replies.user:id,name'])
            ->where('room_id', $roomId)
            ->latest()
            ->get();

        return response()->json($reviews);
    }

    /**
     * Simpan review + rating
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id, // Sanctum
            'room_id' => $request->room_id,
            'rating'  => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review berhasil dikirim',
            'data' => $review,
        ], 201);
    }
}

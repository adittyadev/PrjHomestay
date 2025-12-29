<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewReplyController extends Controller
{
    /**
     * Simpan balasan komentar
     */
    public function store(Request $request, $reviewId)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        $reply = ReviewReply::create([
            'review_id' => $reviewId,
            'user_id'   => $request->user()->id,
            'reply'     => $request->reply,
        ]);

        return response()->json([
            'message' => 'Balasan berhasil dikirim',
            'data' => $reply,
        ], 201);
    }
}

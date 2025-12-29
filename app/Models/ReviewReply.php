<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'review_id',
        'user_id',
        'reply',
    ];

    /* ================= RELATIONSHIP ================= */

    // Balasan milik 1 review
    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    // Balasan dikirim oleh 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

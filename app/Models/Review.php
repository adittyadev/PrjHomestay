<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'rating',
        'comment',
    ];

    /* ================= RELATIONSHIP ================= */

    // Review milik 1 user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Review untuk 1 kamar
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Review punya banyak balasan
    public function replies()
    {
        return $this->hasMany(ReviewReply::class);
    }
}

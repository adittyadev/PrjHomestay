<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $fillable = [
        'booking_id',
        'tanggal_checkin'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

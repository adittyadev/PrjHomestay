<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    protected $fillable = [
        'booking_id',
        'tanggal_checkout'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}

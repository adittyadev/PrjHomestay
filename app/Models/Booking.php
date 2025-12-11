<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'guest_id',
        'room_id',
        'tanggal_booking',
        'check_in',
        'check_out',
        'total_bayar',
        'status_booking'
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}

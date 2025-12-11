<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'nama_kamar',
        'kapasitas',
        'harga',
        'status',
        'foto'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}

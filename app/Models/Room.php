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

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/no-image.jpg'); // Gambar default
    }
    public function getFormattedRatingAttribute()
    {
        return number_format($this->reviews_avg_rating ?? 0, 1);
    }
}

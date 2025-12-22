<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'metode',
        'jumlah',
        'bukti_transfer',
        'status',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    // Relationship
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Accessor untuk URL bukti transfer
    public function getBuktiTransferUrlAttribute()
    {
        if ($this->bukti_transfer) {
            return url('storage/' . $this->bukti_transfer);
        }
        return null;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat'
    ];
}

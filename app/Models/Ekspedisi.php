<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
    use HasFactory;

    protected $table = 'ekspedisis';

    protected $fillable = [
        'nama_ekspedisi',
        'alamat',
        'no_telp',
        'email',
        'pic',
        'no_hp_pic',
        'keterangan',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Relasi dengan RequestDriver
    public function requestDrivers()
    {
        return $this->hasMany(RequestDriver::class);
    }
}

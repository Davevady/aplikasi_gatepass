<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekspedisi extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi dengan RequestDriver
    public function requestDrivers()
    {
        return $this->hasMany(RequestDriver::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestKaryawan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'departemen_id',
        'keperluan',
        'jam_in',
        'jam_out',
        'acc_lead',
        'acc_hr_ga',
        'acc_security_in',
        'acc_security_out',
    ];

    public function departemen()
    {
        return $this->belongsTo(Departemen::class);
    }
}

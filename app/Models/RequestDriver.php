<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestDriver extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_ekspedisi',
        'nopol_kendaraan',
        'nama_driver',
        'no_hp_driver',
        'nama_kernet',
        'no_hp_kernet',
        'keperluan',
        'jam_in',
        'jam_out',
        'acc_admin',
        'acc_head_unit',
        'acc_security_in',
        'acc_security_out',
    ];
}

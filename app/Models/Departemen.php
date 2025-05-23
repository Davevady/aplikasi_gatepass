<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departemen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function requestKaryawans()
    {
        return $this->hasMany(RequestKaryawan::class);
    }
}

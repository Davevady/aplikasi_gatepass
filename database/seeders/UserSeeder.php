<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin (2 user)
        User::create([
            'departemen_id' => 1,
            'role_id' => 1,
            'name' => 'Super Admin 1',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Raya No. 1',
            'phone' => '081234567891',
        ]);
    }
}
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
        // Buat akun untuk departement 1
        User::create([
            'departemen_id' => 1,
            'role_id' => 1,
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
            'address' => 'Jl. raya',
            'phone' => '081234567890',
            'photo' => 'uploads/users/default.png',
            'is_active' => 1,
        ]);
    }
}

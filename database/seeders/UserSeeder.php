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
        // Super Admin (1 user)
        User::create([
            'departemen_id' => 1,
            'role_id' => 1,
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'),
            'address' => 'Jl. Raya No. 1',
            'phone' => '081234567891',
            'photo' => 'images/users/superadmin.png',
            'is_active' => true,
        ]);

        // HR GA (1 user)
        User::create([
            'departemen_id' => 2,
            'role_id' => 3,
            'name' => 'HR GA',
            'email' => 'hrga@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/hrga.png',
            'is_active' => true,
        ]);

        // Leader (2 user)
        User::create([
            'departemen_id' => 3,
            'role_id' => 2,
            'name' => 'Leader Production',
            'email' => 'leaderproduction@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/leaderproduction.png',
            'is_active' => true,
        ]);
        User::create([
            'departemen_id' => 4,
            'role_id' => 2,
            'name' => 'Leader Finance',
            'email' => 'leaderfinance@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/leaderfinance.png',
            'is_active' => true,
        ]);

        // Checker (1 user)
        User::create([
            'departemen_id' => 5,
            'role_id' => 4,
            'name' => 'Checker',
            'email' => 'checker@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/checker.png',
            'is_active' => true,
        ]);

        // Head Unit (1 user)
        User::create([
            'departemen_id' => 5,
            'role_id' => 5,
            'name' => 'Head Unit',
            'email' => 'headunit@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/headunit.png',
            'is_active' => true,
        ]);

        // Security (1 user)
        User::create([
            'departemen_id' => 6,
            'role_id' => 6,
            'name' => 'Security',
            'email' => 'security@gmail.com',
            'password' => Hash::make('password'),
            'photo' => 'images/users/security.png',
            'is_active' => true,
        ]);
    }
}
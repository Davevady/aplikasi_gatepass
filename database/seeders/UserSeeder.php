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
        ]);

        // HR GA (1 user)
        User::create([
            'departemen_id' => 2,
            'role_id' => 3,
            'name' => 'HR GA',
            'email' => 'hrga@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Leader (2 user)
        User::create([
            'departemen_id' => 3,
            'role_id' => 2,
            'name' => 'Leader Production',
            'email' => 'leaderproduction@gmail.com',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'departemen_id' => 4,
            'role_id' => 2,
            'name' => 'Leader Finance',
            'email' => 'leaderfinance@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Checker (1 user)
        User::create([
            'departemen_id' => 5,
            'role_id' => 4,
            'name' => 'Checker',
            'email' => 'checker@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Head Unit (1 user)
        User::create([
            'departemen_id' => 5,
            'role_id' => 5,
            'name' => 'Head Unit',
            'email' => 'headunit@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Security (1 user)
        User::create([
            'departemen_id' => 6,
            'role_id' => 6,
            'name' => 'Security',
            'email' => 'security@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(DepartemenSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(EkspedisiSeeder::class);
        $this->call(RequestKaryawanSeeder::class);
        $this->call(RequestDriverSeeder::class);
        $this->call(NotificationSeeder::class);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'title' => 'Super Admin',
            'slug' => 'super-admin',
            'description' => 'Super Admin',
            'access' => json_encode(['role', 'users', 'departement', 'gatepass']),
            'color' => '#FF0000',
            'icon' => 'fas fa-crown',
        ]);
    }
}

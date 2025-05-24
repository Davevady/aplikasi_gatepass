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
            'title' => 'Admin',
            'slug' => 'admin',
            'description' => 'Admin',
        ]);

        Role::create([
            'title' => 'Lead',
            'slug' => 'lead',
            'description' => 'Lead',
        ]);

        Role::create([
            'title' => 'HR GA',
            'slug' => 'hr-ga',
            'description' => 'HR GA',
        ]);

        Role::create([
            'title' => 'Checker',
            'slug' => 'checker',
            'description' => 'Checker',
        ]);

        Role::create([
            'title' => 'Head Unit',
            'slug' => 'head-unit',
            'description' => 'Head Unit',
        ]);

        Role::create([
            'title' => 'Security',
            'slug' => 'security',
            'description' => 'Security',
        ]);
    }
}

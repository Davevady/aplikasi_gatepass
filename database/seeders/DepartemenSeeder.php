<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departemen::create([
            'name' => 'Admin',
            'code' => 'ADM',
            'description' => 'Departemen Administrasi',
            'head_of_department' => 'John Doe',
            'email' => 'admin@company.com',
            'phone' => '081234567890',
            'address' => 'Jl. Raya No. 1',
            'is_active' => true,
        ]);

        Departemen::create([
            'name' => 'Production',
            'code' => 'PRD',
            'description' => 'Departemen Produksi',
            'head_of_department' => 'Jane Smith',
            'email' => 'production@company.com',
            'phone' => '089876543210',
            'address' => 'Jl. Industri No. 2',
            'is_active' => true,
        ]);
    }
}

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
        ]);

        Departemen::create([
            'name' => 'Human Resources',
            'code' => 'HRD',
            'description' => 'Departemen Sumber Daya Manusia',
        ]);

        Departemen::create([
            'name' => 'Production',
            'code' => 'PRD',
            'description' => 'Departemen Produksi',
        ]);

        Departemen::create([
            'name' => 'Finance',
            'code' => 'FIN',
            'description' => 'Departemen Keuangan',
        ]);

        Departemen::create([
            'name' => 'Logistics',
            'code' => 'LOG',
            'description' => 'Departemen Logistik',
        ]);

        Departemen::create([
            'name' => 'Security',
            'code' => 'SEC',
            'description' => 'Departemen Security',
        ]);
    }
}

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
        $departemenData = [
            ['name' => 'Admin', 'code' => 'ADM', 'description' => 'Departemen Administrasi'],
            ['name' => 'Human Resources', 'code' => 'HRD', 'description' => 'Departemen Sumber Daya Manusia'],
            ['name' => 'Production', 'code' => 'PRD', 'description' => 'Departemen Produksi'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Departemen Keuangan'],
            ['name' => 'Logistics', 'code' => 'LOG', 'description' => 'Departemen Logistik'],
            ['name' => 'Security', 'code' => 'SEC', 'description' => 'Departemen Security'],
            ['name' => 'IT', 'code' => 'IT', 'description' => 'Departemen Teknologi Informasi'],
            ['name' => 'Analytical Center', 'code' => 'ANC', 'description' => 'Departemen Pusat Analisis'],
            ['name' => 'Quality & Food Safety', 'code' => 'DFS', 'description' => 'Departemen Kualitas & Keamanan Pangan'],
            ['name' => 'SHE', 'code' => 'SHE', 'description' => 'Departemen Keselamatan, Kesehatan, dan Lingkungan'],
            ['name' => 'Supply Chain', 'code' => 'SPC', 'description' => 'Departemen Rantai Pasokan'],
            ['name' => 'Engineering', 'code' => 'EGN', 'description' => 'Departemen Teknik'],
        ['name' => 'Management System', 'code' => 'MS', 'description' => 'Departemen Sistem Manajemen'],
            ['name' => 'PPIC', 'code' => 'PIC', 'description' => 'Departemen Perencanaan Produksi dan Pengendalian Persediaan'],
            ['name' => 'Procurement', 'code' => 'PRC', 'description' => 'Departemen Pengadaan'],
            ['name' => 'Warehouse', 'code' => 'WH', 'description' => 'Departemen Gudang'],
            ['name' => 'Among Karya', 'code' => 'AMK', 'description' => 'Departemen Among Karya'],
            ['name' => 'Nawakara', 'code' => 'NWK', 'description' => 'Departemen Nawakara'],
            ['name' => 'Process Development', 'code' => 'PDV', 'description' => 'Departemen Pengembangan Proses'],
        ];

        foreach ($departemenData as $data) {
            Departemen::create($data);
        }
    }
}

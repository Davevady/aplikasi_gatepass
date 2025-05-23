<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RequestKaryawan;

class RequestKaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RequestKaryawan::create([
            'nama' => 'Budi Santoso',
            'departemen_id' => 1, // pastikan id 1 ada di tabel departemens
            'keperluan' => 'Keperluan keluarga',
            'jam_out' => '13:00',
            'jam_in' => '15:00',
            'acc_lead' => 1, // 1 = menunggu
            'acc_hr_ga' => 1, // 1 = menunggu
            'acc_security_in' => 1, // 1 = menunggu
            'acc_security_out' => 1, // 1 = menunggu
        ]);

        // Menambahkan data contoh lainnya
        RequestKaryawan::create([
            'nama' => 'Ani Wijaya',
            'departemen_id' => 2,
            'keperluan' => 'Rapat dengan klien',
            'jam_out' => '14:00',
            'jam_in' => '16:00',
            'acc_lead' => 2, // 2 = disetujui
            'acc_hr_ga' => 1, // 1 = menunggu
            'acc_security_in' => 1, // 1 = menunggu
            'acc_security_out' => 1, // 1 = menunggu
        ]);
    }
}

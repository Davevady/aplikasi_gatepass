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
        $departemens = \App\Models\Departemen::whereNotIn('id', [1, 2])->get();
        $names = ['Budi Santoso', 'Ani Wijaya', 'Dewi Lestari', 'Rudi Hartono', 'Siti Aminah', 'Ahmad Hidayat', 
                 'Joko Widodo', 'Mega Putri', 'Agus Setiawan', 'Linda Sari', 'Rina Wati', 'Doni Pratama',
                 'Siti Nurhaliza', 'Ahmad Dahlan', 'Nina Sartika', 'Bambang Susilo', 'Maya Indah', 'Rudi Kurniawan'];
        $keperluans = ['Keperluan keluarga', 'Rapat dengan klien', 'Kunjungan ke supplier', 'Meeting internal', 
                      'Urusan pribadi', 'Kunjungan ke customer', 'Konsultasi dengan vendor', 'Survey lokasi',
                      'Training eksternal', 'Seminar industri', 'Kunjungan ke pameran', 'Koordinasi tim'];
        $jamOuts = ['13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        $jamIns = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
        $accStatuses = [1, 2]; // 1 = menunggu, 2 = disetujui

        foreach ($departemens as $departemen) {
            // Buat 3 data random untuk setiap departemen
            for ($i = 0; $i < 3; $i++) {
                RequestKaryawan::create([
                    'nama' => $names[array_rand($names)],
                    'departemen_id' => $departemen->id,
                    'keperluan' => $keperluans[array_rand($keperluans)],
                    'jam_out' => $jamOut = $jamOuts[array_rand($jamOuts)],
                    'jam_in' => $jamIns[array_rand(array_filter($jamIns, function($jam) use ($jamOut) {
                        return strtotime($jam) > strtotime($jamOut);
                    }))],
                    'acc_lead' => $accStatuses[array_rand($accStatuses)],
                    'acc_hr_ga' => 1,
                    'acc_security_in' => 1,
                    'acc_security_out' => 1,
                ]);
            }
        }
    }
}

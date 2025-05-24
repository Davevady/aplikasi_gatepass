<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RequestDriver;

class RequestDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ekspedisis = ['PT. Jaya Abadi', 'PT. Sejahtera', 'PT. Maju Bersama', 'PT. Sukses Makmur', 'PT. Abadi Jaya'];
        $nopols = ['B 1234 ABC', 'B 5678 DEF', 'B 9012 GHI', 'B 3456 JKL', 'B 7890 MNO'];
        $drivers = ['Budi Santoso', 'Joko Susilo', 'Andi Wijaya', 'Rudi Hartono', 'Siti Aminah', 'Ahmad Hidayat'];
        $noHps = ['081234567890', '081112223344', '089876543210', '081234567891', '081234567892', '081234567893'];
        $kernets = ['Andi Wijaya', 'Budi Santoso', 'Joko Susilo', null, null, null];
        $noHpKernets = ['089876543210', '081234567890', '081112223344', null, null, null];
        $keperluans = ['Pengiriman barang ke gudang', 'Pengambilan material', 'Pengiriman dokumen', 
                       'Pengambilan barang', 'Pengiriman paket', 'Pengambilan dokumen'];
        $jamOuts = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00'];
        $jamIns = ['10:00', '11:00', '12:00', '14:00', '15:00', '16:00'];
        $accStatuses = [1, 2]; // 1 = menunggu, 2 = disetujui

        // Buat 10 data random
        for ($i = 0; $i < 10; $i++) {
            $jamOut = $jamOuts[array_rand($jamOuts)];
            $jamIn = $jamIns[array_rand(array_filter($jamIns, function($jam) use ($jamOut) {
                return strtotime($jam) > strtotime($jamOut);
            }))];

            RequestDriver::create([
                'nama_ekspedisi' => $ekspedisis[array_rand($ekspedisis)],
                'nopol_kendaraan' => $nopols[array_rand($nopols)],
                'nama_driver' => $drivers[array_rand($drivers)],
                'no_hp_driver' => $noHps[array_rand($noHps)],
                'nama_kernet' => $kernets[array_rand($kernets)],
                'no_hp_kernet' => $noHpKernets[array_rand($noHpKernets)],
                'keperluan' => $keperluans[array_rand($keperluans)],
                'jam_out' => $jamOut,
                'jam_in' => $jamIn,
                'acc_admin' => $accStatuses[array_rand($accStatuses)],
                'acc_head_unit' => 1,
                'acc_security_in' => 1,
                'acc_security_out' => 1,
            ]);
        }
    }
}

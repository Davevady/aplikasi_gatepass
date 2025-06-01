<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RequestDriver;
use App\Models\Notification;

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

            $namaEkspedisi = $ekspedisis[array_rand($ekspedisis)];
            $nopol = $nopols[array_rand($nopols)];
            $accAdmin = $accStatuses[array_rand($accStatuses)];
            $accHeadUnit = $accAdmin == 2 ? $accStatuses[array_rand($accStatuses)] : 1;
            $accSecurityOut = ($accAdmin == 2 && $accHeadUnit == 2) ? $accStatuses[array_rand($accStatuses)] : 1;
            $accSecurityIn = ($accAdmin == 2 && $accHeadUnit == 2 && $accSecurityOut == 2) ? $accStatuses[array_rand($accStatuses)] : 1;

            // Generate random date within last 90 days
            $randomDays = rand(0, 90);
            $createdAt = now()->subDays($randomDays);

            $requestDriver = RequestDriver::create([
                'nama_ekspedisi' => $namaEkspedisi,
                'nopol_kendaraan' => $nopol,
                'nama_driver' => $drivers[array_rand($drivers)],
                'no_hp_driver' => $noHps[array_rand($noHps)],
                'nama_kernet' => $kernets[array_rand($kernets)],
                'no_hp_kernet' => $noHpKernets[array_rand($noHpKernets)],
                'keperluan' => $keperluans[array_rand($keperluans)],
                'jam_out' => $jamOut,
                'jam_in' => $jamIn,
                'acc_admin' => $accAdmin,
                'acc_head_unit' => $accHeadUnit,
                'acc_security_in' => $accSecurityIn,
                'acc_security_out' => $accSecurityOut,
                'created_at' => $createdAt,
                'updated_at' => $createdAt
            ]);

            // Buat notifikasi berdasarkan status approval
            if ($accAdmin == 2) {
                // Notifikasi untuk Checker
                $users = \App\Models\User::whereHas('role', function($query) {
                    $query->whereIn('slug', ['head-unit', 'admin']);
                })->get();

                foreach($users as $user) {
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => 'Disetujui Checker',
                        'message' => 'Permohonan izin driver ' . $namaEkspedisi . 
                                   ' dengan nopol ' . $nopol . 
                                   ' telah disetujui oleh Checker dan menunggu persetujuan Head Unit',
                        'type' => 'driver',
                        'status' => 'pending',
                        'is_read' => false,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt
                    ]);
                }

                if ($accHeadUnit == 2) {
                    // Notifikasi untuk Head Unit
                    $users = \App\Models\User::whereHas('role', function($query) {
                        $query->whereIn('slug', ['security', 'admin']);
                    })->get();

                    foreach($users as $user) {
                        Notification::create([
                            'user_id' => $user->id,
                            'title' => 'Disetujui Head Unit',
                            'message' => 'Permohonan izin driver ' . $namaEkspedisi . 
                                       ' dengan nopol ' . $nopol . 
                                       ' telah disetujui oleh Head Unit dan menunggu persetujuan Security Out',
                            'type' => 'driver',
                            'status' => 'pending',
                            'is_read' => false,
                            'created_at' => $createdAt,
                            'updated_at' => $createdAt
                        ]);
                    }

                    if ($accSecurityOut == 2) {
                        // Notifikasi untuk Security Out
                        $users = \App\Models\User::whereHas('role', function($query) {
                            $query->where('slug', 'admin');
                        })->get();

                        foreach($users as $user) {
                            Notification::create([
                                'user_id' => $user->id,
                                'title' => 'Disetujui Security Out',
                                'message' => 'Permohonan izin driver ' . $namaEkspedisi . 
                                           ' dengan nopol ' . $nopol . 
                                           ' telah disetujui oleh Security Out dan menunggu driver kembali',
                                'type' => 'driver',
                                'status' => 'pending',
                                'is_read' => false,
                                'created_at' => $createdAt,
                                'updated_at' => $createdAt
                            ]);
                        }

                        if ($accSecurityIn == 2) {
                            // Notifikasi untuk Security In
                            $users = \App\Models\User::whereHas('role', function($query) {
                                $query->where('slug', 'admin');
                            })->get();

                            foreach($users as $user) {
                                Notification::create([
                                    'user_id' => $user->id,
                                    'title' => 'Permohonan Izin Driver ' . $namaEkspedisi . ' Disetujui Security In',
                                    'message' => 'Permohonan izin driver atas nama ' . $namaEkspedisi . 
                                               ' dengan nopol ' . $nopol . 
                                               ' untuk keperluan ' . $requestDriver->keperluan . 
                                               ' telah disetujui oleh Security In dan permohonan selesai',
                                    'type' => 'driver',
                                    'status' => 'approved',
                                    'is_read' => false,
                                    'created_at' => $createdAt,
                                    'updated_at' => $createdAt
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }
}

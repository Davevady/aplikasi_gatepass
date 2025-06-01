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
                $nama = $names[array_rand($names)];
                $keperluan = $keperluans[array_rand($keperluans)];
                $jamOut = $jamOuts[array_rand($jamOuts)];
                $jamIn = $jamIns[array_rand(array_filter($jamIns, function($jam) use ($jamOut) {
                    return strtotime($jam) > strtotime($jamOut);
                }))];
                $accLead = $accStatuses[array_rand($accStatuses)];
                $accHrGa = $accLead == 2 ? $accStatuses[array_rand($accStatuses)] : 1;
                $accSecurityOut = ($accLead == 2 && $accHrGa == 2) ? $accStatuses[array_rand($accStatuses)] : 1;
                $accSecurityIn = ($accLead == 2 && $accHrGa == 2 && $accSecurityOut == 2) ? $accStatuses[array_rand($accStatuses)] : 1;

                $requestKaryawan = RequestKaryawan::create([
                    'nama' => $nama,
                    'departemen_id' => $departemen->id,
                    'keperluan' => $keperluan,
                    'jam_out' => $jamOut,
                    'jam_in' => $jamIn,
                    'acc_lead' => $accLead,
                    'acc_hr_ga' => $accHrGa,
                    'acc_security_in' => $accSecurityIn,
                    'acc_security_out' => $accSecurityOut,
                ]);

                // Buat notifikasi berdasarkan status approval
                if ($accLead == 2) {
                    \App\Models\Notification::create([
                        'user_id' => 1, // Super Admin
                        'title' => 'Permohonan Izin Keluar ' . $nama . ' Disetujui Lead',
                        'message' => 'Permohonan izin keluar atas nama ' . $nama . 
                                   ' dari departemen ' . $departemen->name . 
                                   ' untuk keperluan ' . $keperluan . 
                                   ' telah disetujui oleh Lead dan menunggu persetujuan HR GA',
                        'type' => 'karyawan',
                        'status' => 'pending',
                        'is_read' => false
                    ]);
                }

                if ($accLead == 2 && $accHrGa == 2) {
                    \App\Models\Notification::create([
                        'user_id' => 1, // Super Admin
                        'title' => 'Permohonan Izin Keluar ' . $nama . ' Disetujui HR GA',
                        'message' => 'Permohonan izin keluar atas nama ' . $nama . 
                                   ' dari departemen ' . $departemen->name . 
                                   ' untuk keperluan ' . $keperluan . 
                                   ' telah disetujui oleh HR GA dan menunggu persetujuan Security Out',
                        'type' => 'karyawan',
                        'status' => 'pending',
                        'is_read' => false
                    ]);
                }

                if ($accLead == 2 && $accHrGa == 2 && $accSecurityOut == 2) {
                    \App\Models\Notification::create([
                        'user_id' => 1, // Super Admin
                        'title' => 'Permohonan Izin Keluar ' . $nama . ' Disetujui Security Out',
                        'message' => 'Permohonan izin keluar atas nama ' . $nama . 
                                   ' dari departemen ' . $departemen->name . 
                                   ' untuk keperluan ' . $keperluan . 
                                   ' telah disetujui oleh Security Out dan menunggu karyawan kembali',
                        'type' => 'karyawan',
                        'status' => 'pending',
                        'is_read' => false
                    ]);
                }

                if ($accLead == 2 && $accHrGa == 2 && $accSecurityOut == 2 && $accSecurityIn == 2) {
                    \App\Models\Notification::create([
                        'user_id' => 1, // Super Admin
                        'title' => 'Permohonan Izin Keluar ' . $nama . ' Disetujui Security In',
                        'message' => 'Permohonan izin keluar atas nama ' . $nama . 
                                   ' dari departemen ' . $departemen->name . 
                                   ' untuk keperluan ' . $keperluan . 
                                   ' telah disetujui oleh Security In dan permohonan selesai',
                        'type' => 'karyawan',
                        'status' => 'pending',
                        'is_read' => false
                    ]);
                }
            }
        }
    }
}

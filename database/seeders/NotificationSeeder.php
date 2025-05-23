<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Notifikasi untuk request karyawan
        Notification::create([
            'user_id' => 1,
            'title' => 'Permohonan Izin Keluar Budi Santoso',
            'message' => 'Permohonan izin keluar atas nama Budi Santoso untuk keperluan keluarga telah disetujui',
            'type' => 'karyawan',
            'status' => 'approved',
        ]);

        Notification::create([
            'user_id' => 1,
            'title' => 'Permohonan Izin Keluar Ani Wijaya',
            'message' => 'Permohonan izin keluar atas nama Ani Wijaya untuk keperluan rapat dengan klien sedang menunggu persetujuan',
            'type' => 'karyawan',
            'status' => 'pending',
        ]);

        // Notifikasi untuk request driver
        Notification::create([
            'user_id' => 1,
            'title' => 'Permohonan Izin Keluar Driver PT. Jaya Abadi',
            'message' => 'Permohonan izin driver PT. Jaya Abadi dengan nopol B 1234 ABC sedang menunggu persetujuan',
            'type' => 'driver',
            'status' => 'pending',
        ]);

        Notification::create([
            'user_id' => 1,
            'title' => 'Permohonan Izin Keluar Driver PT. Sejahtera',
            'message' => 'Permohonan izin driver PT. Sejahtera dengan nopol B 5678 DEF telah disetujui',
            'type' => 'driver',
            'status' => 'approved',
        ]);
    }
}

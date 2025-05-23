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
        RequestDriver::create([
            'nama_ekspedisi' => 'PT. Jaya Abadi',
            'nopol_kendaraan' => 'B 1234 ABC',
            'nama_driver' => 'Budi Santoso',
            'no_hp_driver' => '081234567890',
            'nama_kernet' => 'Andi Wijaya',
            'no_hp_kernet' => '089876543210',
            'keperluan' => 'Pengiriman barang ke gudang',
            'jam_out' => '09:00',
            'jam_in' => '10:00',
            'acc_admin' => 1, // 1 = menunggu
            'acc_head_unit' => 1, // 1 = menunggu
            'acc_security_in' => 1, // 1 = menunggu
            'acc_security_out' => 1, // 1 = menunggu
        ]);

        RequestDriver::create([
            'nama_ekspedisi' => 'PT. Sejahtera',
            'nopol_kendaraan' => 'B 5678 DEF',
            'nama_driver' => 'Joko Susilo',
            'no_hp_driver' => '081112223344',
            'nama_kernet' => null,
            'no_hp_kernet' => null,
            'keperluan' => 'Pengambilan material',
            'jam_out' => '11:00',
            'jam_in' => '12:00',
            'acc_admin' => 2, // 2 = disetujui
            'acc_head_unit' => 1, // 1 = menunggu
            'acc_security_in' => 1, // 1 = menunggu
            'acc_security_out' => 1, // 1 = menunggu
        ]);
    }
}

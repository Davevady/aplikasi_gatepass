<?php

namespace Database\Seeders;

use App\Models\Ekspedisi;
use Illuminate\Database\Seeder;

class EkspedisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ekspedisis = [
            [
                'nama_ekspedisi' => 'PT JNE Express',
                'alamat' => 'Jl. Raya Bekasi Km. 25, Bekasi',
                'no_telp' => '021-1234567',
                'email' => 'bekasi@jne.co.id',
                'pic' => 'Budi Santoso',
                'no_hp_pic' => '081234567890',
                'keterangan' => 'Ekspedisi pengiriman barang nasional',
                'status' => true
            ],
            [
                'nama_ekspedisi' => 'PT SiCepat Express',
                'alamat' => 'Jl. Raya Bogor Km. 30, Bogor',
                'no_telp' => '021-7654321',
                'email' => 'bogor@sicepat.co.id',
                'pic' => 'Andi Wijaya',
                'no_hp_pic' => '082345678901',
                'keterangan' => 'Ekspedisi pengiriman barang cepat',
                'status' => true
            ],
            [
                'nama_ekspedisi' => 'PT TIKI JNE',
                'alamat' => 'Jl. Raya Jakarta Km. 20, Jakarta',
                'no_telp' => '021-9876543',
                'email' => 'jakarta@tiki.co.id',
                'pic' => 'Dewi Lestari',
                'no_hp_pic' => '083456789012',
                'keterangan' => 'Ekspedisi pengiriman barang internasional',
                'status' => true
            ],
            [
                'nama_ekspedisi' => 'PT Wahana Express',
                'alamat' => 'Jl. Raya Depok Km. 15, Depok',
                'no_telp' => '021-4567890',
                'email' => 'depok@wahana.co.id',
                'pic' => 'Rudi Hartono',
                'no_hp_pic' => '084567890123',
                'keterangan' => 'Ekspedisi pengiriman barang lokal',
                'status' => true
            ],
            [
                'nama_ekspedisi' => 'PT Lion Parcel',
                'alamat' => 'Jl. Raya Tangerang Km. 10, Tangerang',
                'no_telp' => '021-2345678',
                'email' => 'tangerang@lionparcel.co.id',
                'pic' => 'Siti Aminah',
                'no_hp_pic' => '085678901234',
                'keterangan' => 'Ekspedisi pengiriman barang express',
                'status' => true
            ]
        ];

        foreach ($ekspedisis as $ekspedisi) {
            Ekspedisi::create($ekspedisi);
        }
    }
}

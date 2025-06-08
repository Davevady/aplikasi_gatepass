<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DashboardExport implements FromArray, WithHeadings, WithMapping
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'No Surat',
            'Nama',
            'No Telp',
            'Departemen/Ekspedisi',
            'Keperluan',
            'Tanggal',
            'Jam Keluar',
            'Jam Kembali',
            'Status',
            'Tipe',
            'No Pol Kendaraan',
            'Nama Kernet',
            'No HP Kernet'
        ];
    }

    public function map($row): array
    {
        static $no = 1;
        return [
            $no++,
            $row['no_surat'],
            $row['nama'],
            $row['no_telp'],
            $row['departemen'],
            $row['keperluan'],
            $row['tanggal'],
            $row['jam_out'],
            $row['jam_in'],
            $row['text'],
            $row['tipe'],
            $row['nopol_kendaraan'],
            $row['nama_kernet'],
            $row['no_hp_kernet']
        ];
    }
} 
<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RequestDriverExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection($this->data);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'No Surat',
            'Nama Ekspedisi',
            'No Polisi Kendaraan',
            'Nama Driver',
            'No HP Driver',
            'Nama Kernet',
            'No HP Kernet',
            'Keperluan',
            'Tanggal',
            'Jam Keluar',
            'Jam Kembali',
            'Status',
            'Tipe'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        static $no = 1;
        return [
            $no++,
            $row['no_surat'] ?? '-',
            $row['nama_ekspedisi'] ?? '-',
            $row['nopol_kendaraan'] ?? '-',
            $row['nama_driver'] ?? '-',
            $row['no_hp_driver'] ?? '-',
            $row['nama_kernet'] ?? '-',
            $row['no_hp_kernet'] ?? '-',
            $row['keperluan'] ?? '-',
            $row['tanggal'] ?? '-',
            $row['jam_out'] ?? '-',
            $row['jam_in'] ?? '-',
            $row['text'] ?? 'Menunggu',
            $row['tipe'] ?? '-'
        ];
    }
} 
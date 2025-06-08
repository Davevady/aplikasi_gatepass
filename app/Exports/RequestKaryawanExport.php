<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RequestKaryawanExport implements FromCollection, WithHeadings, WithMapping
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
            'Nama',
            'No Telp',
            'Departemen',
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
            $row['nama'] ?? '-',
            $row['no_telp'] ?? '-',
            $row['departemen'] ?? '-',
            $row['keperluan'] ?? '-',
            $row['tanggal'] ?? '-',
            $row['jam_out'] ?? '-',
            $row['jam_in'] ?? '-',
            $row['text'] ?? 'Menunggu',
            $row['tipe'] ?? '-'
        ];
    }
} 
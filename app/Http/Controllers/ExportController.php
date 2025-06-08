<?php

namespace App\Http\Controllers;

use App\Models\RequestDriver;
use App\Models\RequestKaryawan;
use Barryvdh\DomPDF\Facade\PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DashboardExport;

class ExportController extends Controller
{
    public function previewPDF($month, $year, $type = 'all')
    {
        $exportType = request()->query('type', 'filtered');
        $data = $this->getData($month, $year, $type, $exportType);
        $pdf = PDF::loadView('exports.dashboard', compact('data', 'month', 'year', 'type'));
        return $pdf->stream('dashboard_report.pdf');
    }

    public function exportPDF($month, $year, $type = 'all')
    {
        $exportType = request()->query('type', 'filtered');
        $data = $this->getData($month, $year, $type, $exportType);
        $pdf = PDF::loadView('exports.dashboard', compact('data', 'month', 'year', 'type'));
        return $pdf->download('dashboard_report.pdf');
    }

    public function exportExcel($month, $year, $type = 'all')
    {
        $exportType = request()->query('type', 'filtered');
        $data = $this->getData($month, $year, $type, $exportType);
        
        $formattedData = collect($data)->map(function ($item) {
            return [
                'no_surat' => $item['no_surat'] ?? '-',
                'nama' => $item['nama'] ?? '-',
                'no_telp' => $item['no_telp'] ?? '-',
                'departemen' => $item['departemen'] ?? '-',
                'keperluan' => $item['keperluan'] ?? '-',
                'tanggal' => $item['tanggal'] ?? '-',
                'jam_out' => $item['jam_out'] ?? '-',
                'jam_in' => $item['jam_in'] ?? '-',
                'text' => $item['text'] ?? 'Menunggu',
                'tipe' => $item['tipe'] ?? '-',
                'nopol_kendaraan' => $item['nopol_kendaraan'] ?? '-',
                'nama_kernet' => $item['nama_kernet'] ?? '-',
                'no_hp_kernet' => $item['no_hp_kernet'] ?? '-'
            ];
        })->toArray();

        return Excel::download(new DashboardExport($formattedData), 'dashboard_report.xlsx');
    }

    public function getData($month, $year, $type = 'all', $exportType = 'filtered')
    {
        $user = auth()->user();
        $data = [];

        // Data karyawan
        if ($user->role_id != 4 && $user->role_id != 5) {
            $query = RequestKaryawan::with(['departemen']);
            
            // Jika exportType adalah 'filtered', tambahkan filter bulan dan tahun
            if ($exportType === 'filtered') {
                $query->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            }
            
            $karyawanRequests = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $statusBadge = 'warning';
                    $text = 'Menunggu';
                    
                    // Cek jika ada yang menolak
                    if($item->acc_lead == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Lead';
                    } 
                    elseif($item->acc_hr_ga == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak HR GA';
                    }
                    elseif($item->acc_security_out == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Security Out';
                    } 
                    elseif($item->acc_security_in == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Security In';
                    }
                    // Cek urutan persetujuan sesuai alur jika tidak ditolak
                    elseif($item->acc_lead == 1) {
                        $statusBadge = 'warning';
                        $text = 'Menunggu Lead';
                    }
                    elseif($item->acc_lead == 2 && $item->acc_hr_ga == 1) {
                        $statusBadge = 'warning';
                        $text = 'Menunggu HR GA';
                    }
                    elseif($item->acc_lead == 2 && $item->acc_hr_ga == 2) {
                        if($item->acc_security_out == 1) {
                            if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                                $statusBadge = 'danger';
                                $text = 'Hangus';
                            } else {
                                $statusBadge = 'info';
                                $text = 'Disetujui (Belum Keluar)';
                            }
                        } elseif ($item->acc_security_out == 2) {
                            if ($item->acc_security_in == 1) {
                                if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                                    $statusBadge = 'warning';
                                    $text = 'Terlambat';
                                } else {
                                    $statusBadge = 'info';
                                    $text = 'Sudah Keluar (Belum Kembali)';
                                }
                            } elseif ($item->acc_security_in == 2) {
                                $statusBadge = 'success';
                                $text = 'Sudah Kembali';
                            }
                        }
                    }

                    return [
                        'no_surat' => $item->no_surat ?? '-',
                        'nama' => $item->nama ?? '-',
                        'no_telp' => $item->no_telp ?? '-',
                        'departemen' => $item->departemen->name ?? '-',
                        'keperluan' => $item->keperluan ?? '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out ?? '-',
                        'jam_in' => $item->jam_in ?? '-',
                        'status' => $statusBadge,
                        'text' => $text,
                        'tipe' => 'Karyawan',
                        'nopol_kendaraan' => '-',
                        'nama_kernet' => '-',
                        'no_hp_kernet' => '-',
                        'nama_ekspedisi' => '-'
                    ];
                });

            $data = array_merge($data, $karyawanRequests->toArray());
        }

        // Data driver
        if ($user->role_id != 2 && $user->role_id != 3) {
            $query = RequestDriver::with(['ekspedisi']);
            
            // Jika exportType adalah 'filtered', tambahkan filter bulan dan tahun
            if ($exportType === 'filtered') {
                $query->whereMonth('created_at', $month)
                      ->whereYear('created_at', $year);
            }
            
            $driverRequests = $query->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    $statusBadge = 'warning';
                    $text = 'Menunggu';
                    
                    if($item->acc_admin == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Admin';
                    } 
                    elseif($item->acc_head_unit == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Head Unit';
                    }
                    elseif($item->acc_security_out == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Security Out';
                    } 
                    elseif($item->acc_security_in == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Security In';
                    }
                    elseif($item->acc_admin == 1) {
                        $statusBadge = 'warning';
                        $text = 'Menunggu Admin/Checker';
                    }
                    elseif($item->acc_admin == 2 && $item->acc_head_unit == 1) {
                        $statusBadge = 'warning';
                        $text = 'Menunggu Head Unit';
                    }
                    elseif($item->acc_admin == 2 && $item->acc_head_unit == 2) {
                        if($item->acc_security_out == 1) {
                            if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                                $statusBadge = 'danger';
                                $text = 'Hangus';
                            } else {
                                $statusBadge = 'info';
                                $text = 'Disetujui (Belum Keluar)';
                            }
                        } elseif ($item->acc_security_out == 2) {
                            if ($item->acc_security_in == 1) {
                                if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                                    $statusBadge = 'warning';
                                    $text = 'Terlambat';
                                } else {
                                    $statusBadge = 'info';
                                    $text = 'Sudah Keluar (Belum Kembali)';
                                }
                            } elseif ($item->acc_security_in == 2) {
                                $statusBadge = 'success';
                                $text = 'Sudah Kembali';
                            }
                        }
                    }

                    return [
                        'no_surat' => $item->no_surat ?? '-',
                        'nama' => $item->nama_driver ?? '-',
                        'no_telp' => $item->no_hp_driver ?? '-',
                        'departemen' => $item->ekspedisi ? $item->ekspedisi->nama_ekspedisi : '-',
                        'keperluan' => $item->keperluan ?? '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out ?? '-',
                        'jam_in' => $item->jam_in ?? '-',
                        'status' => $statusBadge,
                        'text' => $text,
                        'tipe' => 'Driver',
                        'nopol_kendaraan' => $item->nopol_kendaraan ?? '-',
                        'nama_kernet' => $item->nama_kernet ?? '-',
                        'no_hp_kernet' => $item->no_hp_kernet ?? '-',
                        'nama_ekspedisi' => $item->ekspedisi ? $item->ekspedisi->nama_ekspedisi : '-'
                    ];
                });

            $data = array_merge($data, $driverRequests->toArray());
        }

        // Filter berdasarkan tipe jika bukan 'all'
        if ($type !== 'all') {
            $data = array_filter($data, function($item) use ($type) {
                return $item['tipe'] === $type;
            });
        }

        // Reset array keys
        $data = array_values($data);

        return $data;
    }

    private function getStatus($item)
    {
        $statusBadge = 'warning';
        $text = 'Menunggu';
        
        if ($item instanceof RequestKaryawan) {
            if($item->acc_lead == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Lead';
            } 
            elseif($item->acc_hr_ga == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak HR GA';
            }
            elseif($item->acc_security_out == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Security Out';
            } 
            elseif($item->acc_security_in == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Security In';
            }
            elseif($item->acc_lead == 1) {
                $statusBadge = 'warning';
                $text = 'Menunggu Lead';
            }
            elseif($item->acc_lead == 2 && $item->acc_hr_ga == 1) {
                $statusBadge = 'warning';
                $text = 'Menunggu HR GA';
            }
            elseif($item->acc_lead == 2 && $item->acc_hr_ga == 2) {
                if($item->acc_security_out == 1) {
                    if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                        $statusBadge = 'danger';
                        $text = 'Hangus';
                    } else {
                        $statusBadge = 'info';
                        $text = 'Disetujui (Belum Keluar)';
                    }
                } elseif ($item->acc_security_out == 2) {
                    if ($item->acc_security_in == 1) {
                        if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                            $statusBadge = 'warning';
                            $text = 'Terlambat';
                        } else {
                            $statusBadge = 'info';
                            $text = 'Sudah Keluar (Belum Kembali)';
                        }
                    } elseif ($item->acc_security_in == 2) {
                        $statusBadge = 'success';
                        $text = 'Sudah Kembali';
                    }
                }
            }
        } else {
            if($item->acc_admin == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Admin';
            } 
            elseif($item->acc_head_unit == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Head Unit';
            }
            elseif($item->acc_security_out == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Security Out';
            } 
            elseif($item->acc_security_in == 3) {
                $statusBadge = 'danger';
                $text = 'Ditolak Security In';
            }
            elseif($item->acc_admin == 1) {
                $statusBadge = 'warning';
                $text = 'Menunggu Admin/Checker';
            }
            elseif($item->acc_admin == 2 && $item->acc_head_unit == 1) {
                $statusBadge = 'warning';
                $text = 'Menunggu Head Unit';
            }
            elseif($item->acc_admin == 2 && $item->acc_head_unit == 2) {
                if($item->acc_security_out == 1) {
                    if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                        $statusBadge = 'danger';
                        $text = 'Hangus';
                    } else {
                        $statusBadge = 'info';
                        $text = 'Disetujui (Belum Keluar)';
                    }
                } elseif ($item->acc_security_out == 2) {
                    if ($item->acc_security_in == 1) {
                        if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
                            $statusBadge = 'warning';
                            $text = 'Terlambat';
                        } else {
                            $statusBadge = 'info';
                            $text = 'Sudah Keluar (Belum Kembali)';
                        }
                    } elseif ($item->acc_security_in == 2) {
                        $statusBadge = 'success';
                        $text = 'Sudah Kembali';
                    }
                }
            }
        }

        return [
            'badge' => $statusBadge,
            'text' => $text
        ];
    }
} 
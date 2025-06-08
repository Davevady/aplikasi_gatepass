<?php

namespace App\Http\Controllers;

use App\Models\RequestDriver;
use App\Models\RequestKaryawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SearchController extends Controller
{
    /**
     * Menampilkan halaman pencarian surat izin
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = "Cari Surat";
        return view('search.input', compact('title'));
    }

    /**
     * Melakukan pencarian surat izin berdasarkan nomor surat atau nama
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        $title = "Hasil Pencarian Surat";
        $searchQuery = $request->input('no_surat');
        
        // Cari data driver berdasarkan nomor surat atau nama driver
        $driverRequest = RequestDriver::with(['ekspedisi'])
            ->where(function($query) use ($searchQuery) {
                $query->where('no_surat', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('nama_driver', 'LIKE', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                $statusBadge = 'warning';
                $text = 'Menunggu';
                
                // Cek jika ada yang menolak
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
                // Cek urutan persetujuan sesuai alur jika tidak ditolak
                elseif($item->acc_admin == 1) {
                    $statusBadge = 'warning';
                    $text = 'Menunggu Admin/Checker';
                }
                elseif($item->acc_admin == 2 && $item->acc_head_unit == 1) {
                    $statusBadge = 'warning';
                    $text = 'Menunggu Head Unit';
                }
                // Jika sudah disetujui Admin dan Head Unit
                elseif($item->acc_admin == 2 && $item->acc_head_unit == 2) {
                    // Cek status security
                    if($item->acc_security_out == 1) {
                        // Cek status hangus (jam in sudah lewat tapi belum keluar)
                        if (Carbon::parse($item->jam_in)->isPast()) {
                            $statusBadge = 'danger';
                            $text = 'Hangus';
                        } else {
                            $statusBadge = 'info';
                            $text = 'Disetujui (Belum Keluar)';
                        }
                    } elseif ($item->acc_security_out == 2) {
                        // Cek status security in
                        if ($item->acc_security_in == 1) {
                            // Cek status terlambat (sudah keluar tapi belum kembali)
                            if (Carbon::parse($item->jam_in)->isPast()) {
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
                    'id' => $item->id,
                    'no_surat' => $item->no_surat,
                    'nama_driver' => $item->nama_driver,
                    'no_hp_driver' => $item->no_hp_driver,
                    'keperluan' => $item->keperluan,
                    'jam_out' => $item->jam_out,
                    'jam_in' => $item->jam_in,
                    'status_badge' => $statusBadge,
                    'status_text' => $text,
                    'acc_admin' => $item->acc_admin,
                    'acc_head_unit' => $item->acc_head_unit,
                    'acc_security_out' => $item->acc_security_out,
                    'acc_security_in' => $item->acc_security_in
                ];
            });
            
        // Cari data karyawan berdasarkan nomor surat atau nama
        $karyawanRequest = RequestKaryawan::where(function($query) use ($searchQuery) {
                $query->where('no_surat', 'LIKE', "%{$searchQuery}%")
                      ->orWhere('nama', 'LIKE', "%{$searchQuery}%");
            })
            ->orderBy('created_at', 'desc')
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
                // Jika sudah disetujui Lead dan HR GA
                elseif($item->acc_lead == 2 && $item->acc_hr_ga == 2) {
                    // Cek status security
                    if($item->acc_security_out == 1) {
                        // Cek status hangus (jam in sudah lewat tapi belum keluar)
                        if (Carbon::parse($item->jam_in)->isPast()) {
                            $statusBadge = 'danger';
                            $text = 'Hangus';
                        } else {
                            $statusBadge = 'info';
                            $text = 'Disetujui (Belum Keluar)';
                        }
                    } elseif ($item->acc_security_out == 2) {
                        // Cek status security in
                        if ($item->acc_security_in == 1) {
                            // Cek status terlambat (sudah keluar tapi belum kembali)
                            if (Carbon::parse($item->jam_in)->isPast()) {
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
                    'id' => $item->id,
                    'no_surat' => $item->no_surat,
                    'nama' => $item->nama,
                    'no_telp' => $item->no_telp,
                    'keperluan' => $item->keperluan,
                    'jam_out' => $item->jam_out,
                    'jam_in' => $item->jam_in,
                    'status_badge' => $statusBadge,
                    'status_text' => $text,
                    'acc_lead' => $item->acc_lead,
                    'acc_hr_ga' => $item->acc_hr_ga,
                    'acc_security_out' => $item->acc_security_out,
                    'acc_security_in' => $item->acc_security_in
                ];
            });
        
        // Jika tidak ada hasil pencarian
        if ($driverRequest->isEmpty() && $karyawanRequest->isEmpty()) {
            return view('search.index', [
                'title' => $title,
                'driverRequests' => collect(),
                'karyawanRequests' => collect(),
                'noSurat' => $searchQuery,
                'message' => 'Tidak ditemukan surat atau nama yang dicari'
            ]);
        }
        
        return view('search.index', [
            'title' => $title,
            'driverRequests' => $driverRequest,
            'karyawanRequests' => $karyawanRequest,
            'noSurat' => $searchQuery
        ]);
    }
} 
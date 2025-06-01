<?php

namespace App\Http\Controllers;

use App\Models\RequestDriver;
use App\Models\RequestKaryawan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard dengan statistik dan data terbaru
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Dashboard';
        
        // Menghitung total request karyawan berdasarkan status persetujuan
        // Status 1 = Menunggu, 2 = Disetujui, 3 = Ditolak
        $totalKaryawanMenunggu = RequestKaryawan::where(function($query) {
            $query->where('acc_lead', 1) // Lead belum menyetujui
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead sudah menyetujui
                      ->where('acc_hr_ga', 1); // HR GA belum menyetujui
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead sudah menyetujui
                      ->where('acc_hr_ga', 2) // HR GA sudah menyetujui
                      ->where('acc_security_out', 1); // Security Out belum menyetujui
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead sudah menyetujui
                      ->where('acc_hr_ga', 2) // HR GA sudah menyetujui
                      ->where('acc_security_out', 2) // Security Out sudah menyetujui
                      ->where('acc_security_in', 1); // Security In belum menyetujui
                });
        })->count();
            
        // Menghitung total request karyawan yang sudah disetujui semua pihak
        $totalKaryawanDisetujui = RequestKaryawan::where('acc_lead', 2)
            ->where('acc_hr_ga', 2)
            ->where('acc_security_out', 2)
            ->where('acc_security_in', 2)
            ->count();
            
        // Menghitung total request karyawan yang ditolak oleh salah satu pihak
        $totalKaryawanDitolak = RequestKaryawan::where(function($query) {
            $query->where('acc_lead', 3) // Lead menolak
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead menyetujui
                      ->where('acc_hr_ga', 3); // HR GA menolak
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead menyetujui
                      ->where('acc_hr_ga', 2) // HR GA menyetujui
                      ->where('acc_security_out', 3); // Security Out menolak
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2) // Lead menyetujui
                      ->where('acc_hr_ga', 2) // HR GA menyetujui
                      ->where('acc_security_out', 2) // Security Out menyetujui
                      ->where('acc_security_in', 3); // Security In menolak
                });
        })->count();
            
        // Total semua request karyawan
        $totalKaryawanRequest = RequestKaryawan::count();

        // Menghitung total request driver berdasarkan status persetujuan
        $totalDriverMenunggu = RequestDriver::where(function($query) {
            $query->where('acc_admin', 1) // Admin belum menyetujui
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin sudah menyetujui
                      ->where('acc_head_unit', 1); // Head Unit belum menyetujui
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin sudah menyetujui
                      ->where('acc_head_unit', 2) // Head Unit sudah menyetujui
                      ->where('acc_security_out', 1); // Security Out belum menyetujui
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin sudah menyetujui
                      ->where('acc_head_unit', 2) // Head Unit sudah menyetujui
                      ->where('acc_security_out', 2) // Security Out sudah menyetujui
                      ->where('acc_security_in', 1); // Security In belum menyetujui
                });
        })->count();
            
        // Menghitung total request driver yang sudah disetujui semua pihak
        $totalDriverDisetujui = RequestDriver::where('acc_admin', 2)
            ->where('acc_head_unit', 2)
            ->where('acc_security_out', 2)
            ->where('acc_security_in', 2)
            ->count();
            
        // Menghitung total request driver yang ditolak oleh salah satu pihak
        $totalDriverDitolak = RequestDriver::where(function($query) {
            $query->where('acc_admin', 3) // Admin menolak
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin menyetujui
                      ->where('acc_head_unit', 3); // Head Unit menolak
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin menyetujui
                      ->where('acc_head_unit', 2) // Head Unit menyetujui
                      ->where('acc_security_out', 3); // Security Out menolak
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2) // Admin menyetujui
                      ->where('acc_head_unit', 2) // Head Unit menyetujui
                      ->where('acc_security_out', 2) // Security Out menyetujui
                      ->where('acc_security_in', 3); // Security In menolak
                });
        })->count();
            
        // Total semua request driver
        $totalDriverRequest = RequestDriver::count();

        // Menghitung total keseluruhan request
        $totalMenunggu = $totalKaryawanMenunggu + $totalDriverMenunggu;
        $totalDisetujui = $totalKaryawanDisetujui + $totalDriverDisetujui;
        $totalDitolak = $totalKaryawanDitolak + $totalDriverDitolak;
        $totalRequest = $totalKaryawanRequest + $totalDriverRequest;

        // Mengambil 2 permohonan karyawan terbaru dengan relasi departemen
        $latestKaryawanRequests = RequestKaryawan::with('departemen')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Mengambil 2 permohonan driver terbaru
        $latestDriverRequests = RequestDriver::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Mengambil data untuk grafik bulanan
        $monthlyData = $this->getMonthlyData();

        return view('superadmin.index', compact(
            'title',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest',
            'totalKaryawanRequest',
            'totalDriverRequest',
            'latestKaryawanRequests',
            'latestDriverRequests',
            'monthlyData'
        ));
    }

    /**
     * Mengambil data statistik bulanan untuk karyawan dan driver
     * 
     * @return array Data statistik bulanan
     */
    private function getMonthlyData()
    {
        $currentYear = date('Y');
        $monthlyData = [];

        // Mengambil data statistik karyawan per bulan
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['karyawan'][$i] = RequestKaryawan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        // Mengambil data statistik driver per bulan
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['driver'][$i] = RequestDriver::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        return $monthlyData;
    }

    /**
     * Mengambil data permohonan berdasarkan status
     * 
     * @param string $status Status permohonan (disetujui/ditolak)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatusData($status)
    {
        $data = [];
        
        if ($status === 'disetujui') {
            // Data karyawan yang disetujui
            $karyawanDisetujui = RequestKaryawan::with('departemen')
                ->where('acc_lead', 2)
                ->where('acc_hr_ga', 2)
                ->where('acc_security_out', 2)
                ->where('acc_security_in', 2)
                ->get()
                ->map(function ($item) {
                    return [
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => $item->created_at->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Karyawan'
                    ];
                });

            // Data driver yang disetujui
            $driverDisetujui = RequestDriver::where('acc_admin', 2)
                ->where('acc_head_unit', 2)
                ->where('acc_security_out', 2)
                ->where('acc_security_in', 2)
                ->get()
                ->map(function ($item) {
                    return [
                        'nama' => $item->nama_driver,
                        'departemen' => '-',
                        'tanggal' => $item->created_at->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Driver'
                    ];
                });

            $data = $karyawanDisetujui->concat($driverDisetujui);
        } else if ($status === 'ditolak') {
            // Data karyawan yang ditolak
            $karyawanDitolak = RequestKaryawan::with('departemen')
                ->where(function($query) {
                    $query->where('acc_lead', 3)
                        ->orWhere('acc_hr_ga', 3)
                        ->orWhere('acc_security_out', 3)
                        ->orWhere('acc_security_in', 3);
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => $item->created_at->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Karyawan'
                    ];
                });

            // Data driver yang ditolak
            $driverDitolak = RequestDriver::where(function($query) {
                    $query->where('acc_admin', 3)
                        ->orWhere('acc_head_unit', 3)
                        ->orWhere('acc_security_out', 3)
                        ->orWhere('acc_security_in', 3);
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'nama' => $item->nama_driver,
                        'departemen' => '-',
                        'tanggal' => $item->created_at->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Driver'
                    ];
                });

            $data = $karyawanDitolak->concat($driverDitolak);
        }

        return response()->json($data);
    }
}

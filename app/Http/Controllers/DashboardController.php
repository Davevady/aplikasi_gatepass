<?php

namespace App\Http\Controllers;

use App\Models\RequestDriver;
use App\Models\RequestKaryawan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        
        // Menghitung total request karyawan
        $totalKaryawanMenunggu = RequestKaryawan::where(function($query) {
            $query->where('acc_lead', 1)
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 1);
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 2)
                      ->where('acc_security_out', 1);
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 2)
                      ->where('acc_security_out', 2)
                      ->where('acc_security_in', 1);
                });
        })->count();
            
        $totalKaryawanDisetujui = RequestKaryawan::where('acc_lead', 2)
            ->where('acc_hr_ga', 2)
            ->where('acc_security_out', 2)
            ->where('acc_security_in', 2)
            ->count();
            
        $totalKaryawanDitolak = RequestKaryawan::where(function($query) {
            $query->where('acc_lead', 3)
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 3);
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 2)
                      ->where('acc_security_out', 3);
                })
                ->orWhere(function($q) {
                    $q->where('acc_lead', 2)
                      ->where('acc_hr_ga', 2)
                      ->where('acc_security_out', 2)
                      ->where('acc_security_in', 3);
                });
        })->count();
            
        $totalKaryawanRequest = RequestKaryawan::count();

        // Menghitung total request driver
        $totalDriverMenunggu = RequestDriver::where(function($query) {
            $query->where('acc_admin', 1)
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 1);
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 2)
                      ->where('acc_security_out', 1);
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 2)
                      ->where('acc_security_out', 2)
                      ->where('acc_security_in', 1);
                });
        })->count();
            
        $totalDriverDisetujui = RequestDriver::where('acc_admin', 2)
            ->where('acc_head_unit', 2)
            ->where('acc_security_out', 2)
            ->where('acc_security_in', 2)
            ->count();
            
        $totalDriverDitolak = RequestDriver::where(function($query) {
            $query->where('acc_admin', 3)
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 3);
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 2)
                      ->where('acc_security_out', 3);
                })
                ->orWhere(function($q) {
                    $q->where('acc_admin', 2)
                      ->where('acc_head_unit', 2)
                      ->where('acc_security_out', 2)
                      ->where('acc_security_in', 3);
                });
        })->count();
            
        $totalDriverRequest = RequestDriver::count();

        // Total keseluruhan
        $totalMenunggu = $totalKaryawanMenunggu + $totalDriverMenunggu;
        $totalDisetujui = $totalKaryawanDisetujui + $totalDriverDisetujui;
        $totalDitolak = $totalKaryawanDitolak + $totalDriverDitolak;
        $totalRequest = $totalKaryawanRequest + $totalDriverRequest;

        // Ambil data permohonan terbaru
        $latestKaryawanRequests = RequestKaryawan::with('departemen')
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $latestDriverRequests = RequestDriver::orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        // Data untuk grafik bulanan
        $monthlyData = $this->getMonthlyData();

        return view('superadmin.index', compact(
            'title',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest',
            'latestKaryawanRequests',
            'latestDriverRequests',
            'monthlyData'
        ));
    }

    private function getMonthlyData()
    {
        $currentYear = date('Y');
        $monthlyData = [];

        // Data karyawan
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['karyawan'][$i] = RequestKaryawan::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        // Data driver
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['driver'][$i] = RequestDriver::whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        return $monthlyData;
    }
}

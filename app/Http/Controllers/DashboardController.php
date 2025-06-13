<?php

namespace App\Http\Controllers;

use App\Models\RequestDriver;
use App\Models\RequestKaryawan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        $user = auth()->user();
        Log::debug('User Role ID: ' . $user->role_id . ' - User Departemen ID: ' . $user->departemen_id . ' - User Departemen Name: ' . ($user->departemen ? $user->departemen->name : 'N/A'));
        
        // Menghitung total request karyawan berdasarkan status persetujuan
        $totalKaryawanMenunggu = 0;
        $totalKaryawanDisetujui = 0;
        $totalKaryawanDitolak = 0;
        $totalKaryawanRequest = 0;

        // Query dasar untuk karyawan
        $karyawanQuery = RequestKaryawan::query();
        
        // Filter berdasarkan role dan departemen
        if ($user->role_id == 2) { // Role Lead
            Log::debug('Applying Lead filter for departemen_id: ' . $user->departemen_id);
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            Log::debug('Applying HR GA filter (excluding Admin and HR)');
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]); // Kecuali departemen Admin dan HR
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            Log::debug('Applying Checker/Head Unit filter (no karyawan data)');
            $karyawanQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Menghitung statistik hanya jika user memiliki akses
        if ($user->role_id != 4 && $user->role_id != 5) {
            // Permohonan Menunggu Karyawan
            $totalKaryawanMenunggu = (clone $karyawanQuery)->where(function($query) {
                $query->where(function($q) {
                    $q->where('acc_lead', 1) // Lead belum menyetujui
                      ->orWhere('acc_hr_ga', 1) // HR GA belum menyetujui setelah Lead acc
                      ->orWhere('acc_security_out', 1); // Security Out belum menyetujui setelah HR GA acc
                })
                ->where('acc_lead', '!=', 3)
                ->where('acc_hr_ga', '!=', 3)
                ->where('acc_security_out', '!=', 3)
                ->where('acc_security_in', '!=', 3);
            })
            ->count();
                
            // Permohonan Disetujui Karyawan
            $totalKaryawanDisetujui = (clone $karyawanQuery)->where('acc_lead', 2)
                ->where('acc_hr_ga', 2)
                ->where('acc_security_out', 2)
                ->count();
                
            // Permohonan Ditolak Karyawan
            $totalKaryawanDitolak = (clone $karyawanQuery)->where(function($query) {
                $query->where('acc_lead', 3) // Lead menolak
                    ->orWhere('acc_hr_ga', 3) // HR GA menolak
                    ->orWhere('acc_security_out', 3) // Security Out menolak
                    ->orWhere('acc_security_in', 3); // Security In menolak
            })->count();
                
            // Total semua request karyawan
            $totalKaryawanRequest = $karyawanQuery->count();
        }

        // Menghitung total request driver berdasarkan status persetujuan
        $totalDriverMenunggu = 0;
        $totalDriverDisetujui = 0;
        $totalDriverDitolak = 0;
        $totalDriverRequest = 0;

        // Query dasar untuk driver
        $driverQuery = RequestDriver::query();

        // Filter berdasarkan role
        if ($user->role_id == 2 || $user->role_id == 3) { // Role Lead dan HR GA
            // Lead dan HR GA tidak bisa melihat data driver
            $driverQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Menghitung statistik driver hanya jika user memiliki akses
        if ($user->role_id != 2 && $user->role_id != 3) {
            // Permohonan Menunggu Driver
            $totalDriverMenunggu = (clone $driverQuery)->where(function($query) {
                $query->where(function($q) {
                    $q->where('acc_admin', 1) // Admin belum menyetujui
                      ->orWhere('acc_head_unit', 1) // Head Unit belum menyetujui setelah Admin acc
                      ->orWhere('acc_security_out', 1); // Security Out belum menyetujui setelah Head Unit acc
                })
                ->where('acc_admin', '!=', 3)
                ->where('acc_head_unit', '!=', 3)
                ->where('acc_security_out', '!=', 3)
                ->where('acc_security_in', '!=', 3);
            })
            ->count();
                
            // Permohonan Disetujui Driver
            $totalDriverDisetujui = (clone $driverQuery)->where('acc_admin', 2)
                ->where('acc_head_unit', 2)
                ->where('acc_security_out', 2)
                ->count();
                
            // Permohonan Ditolak Driver
            $totalDriverDitolak = (clone $driverQuery)->where(function($query) {
                $query->where('acc_admin', 3) // Admin menolak
                    ->orWhere('acc_head_unit', 3) // Head Unit menolak
                    ->orWhere('acc_security_out', 3) // Security Out menolak
                    ->orWhere('acc_security_in', 3); // Security In menolak
            })->count();
                
            // Total semua request driver
            $totalDriverRequest = $driverQuery->count();
        }

        // Menghitung total keseluruhan request yang terlihat oleh user
        $totalMenunggu = $totalKaryawanMenunggu + $totalDriverMenunggu;
        $totalDisetujui = $totalKaryawanDisetujui + $totalDriverDisetujui;
        $totalDitolak = $totalKaryawanDitolak + $totalDriverDitolak;
        
        // Total request keseluruhan yang terlihat oleh user
        $totalRequest = $totalKaryawanRequest + $totalDriverRequest;

        // Mengambil semua permohonan karyawan dengan relasi departemen
        $karyawanRequests = collect(); // Initialize as empty collection
        if ($user->role_id != 4 && $user->role_id != 5) { // Bukan role checker dan head unit
            $karyawanRequests = (clone $karyawanQuery)
                ->with(['departemen'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Mengambil semua permohonan driver
        $driverRequests = collect(); // Initialize as empty collection
        if ($user->role_id != 2 && $user->role_id != 3) { // Bukan role lead dan hr-ga
            $driverRequests = (clone $driverQuery)
                ->with('ekspedisi')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Mengambil data untuk grafik bulanan
        $monthlyData = $this->getMonthlyData();

        // Mengambil tahun-tahun yang tersedia untuk filter
        $years = $this->getAvailableYears();

        // Mengambil data departemen untuk lead
        $departemen = null;
        if ($user->role_id == 2) {
            $departemen = \App\Models\Departemen::find($user->departemen_id);
        }

        return view('superadmin.index', compact(
            'title',
            'totalMenunggu',
            'totalDisetujui', 
            'totalDitolak',
            'totalRequest',
            'totalKaryawanRequest',
            'totalDriverRequest',
            'karyawanRequests',
            'driverRequests',
            'monthlyData',
            'years',
            'departemen'
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
        $user = auth()->user();
        $monthlyData = [];

        // Query dasar untuk karyawan
        $karyawanQuery = RequestKaryawan::query();
        
        // Filter berdasarkan role dan departemen
        if ($user->role_id == 2) { // Role Lead
            // Lead hanya bisa melihat data dari departemennya sendiri
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            // HR GA bisa melihat semua departemen kecuali Admin dan HR
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]); // Kecuali departemen Admin dan HR
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            // Checker dan Head Unit tidak bisa melihat data karyawan
            $karyawanQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Mengambil data statistik karyawan per bulan
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['karyawan'][$i] = (clone $karyawanQuery)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        // Query dasar untuk driver
        $driverQuery = RequestDriver::query();

        // Filter berdasarkan role
        if ($user->role_id == 2 || $user->role_id == 3) { // Role Lead dan HR GA
            // Lead dan HR GA tidak bisa melihat data driver
            $driverQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Mengambil data statistik driver per bulan
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData['driver'][$i] = (clone $driverQuery)
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $i)
                ->count();
        }

        return $monthlyData;
    }

    /**
     * Mengambil data statistik per minggu untuk bulan tertentu
     * 
     * @param int $month Bulan yang dipilih (1-12)
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeeklyData($month)
    {
        $year = date('Y');
        $user = auth()->user();
        $data = [
            'karyawan' => [],
            'driver' => []
        ];

        // Query dasar untuk karyawan
        $karyawanQuery = RequestKaryawan::query();
        
        // Filter berdasarkan role dan departemen
        if ($user->role_id == 2) { // Role Lead
            // Lead hanya bisa melihat data dari departemennya sendiri
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            // HR GA bisa melihat semua departemen kecuali Admin dan HR
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]); // Kecuali departemen Admin dan HR
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            // Checker dan Head Unit tidak bisa melihat data karyawan
            $karyawanQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Query dasar untuk driver
        $driverQuery = RequestDriver::query();

        // Filter berdasarkan role
        if ($user->role_id == 2 || $user->role_id == 3) { // Role Lead dan HR GA
            // Lead dan HR GA tidak bisa melihat data driver
            $driverQuery->whereRaw('1 = 0'); // Query yang selalu false
        }

        // Mendapatkan jumlah minggu dalam bulan tersebut
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $totalWeeks = ceil($lastDay->day / 7);

        // Mengambil data per minggu untuk karyawan dan driver
        for ($week = 1; $week <= $totalWeeks; $week++) {
            $startDate = $firstDay->copy()->addDays(($week - 1) * 7);
            $endDate = $startDate->copy()->addDays(6);
            
            if ($endDate->month != $month) {
                $endDate = $lastDay;
            }

            $data['karyawan'][$week] = (clone $karyawanQuery)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
                
            $data['driver'][$week] = (clone $driverQuery)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        return response()->json($data);
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
        $user = auth()->user();
        
        // Query dasar untuk karyawan
        $karyawanQuery = RequestKaryawan::query();
        
        // Filter berdasarkan role dan departemen untuk karyawan
        if ($user->role_id == 2) { // Role Lead
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]);
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            $karyawanQuery->whereRaw('1 = 0');
        }

        // Query dasar untuk driver
        $driverQuery = RequestDriver::query();

        // Filter berdasarkan role untuk driver
        if ($user->role_id == 2 || $user->role_id == 3) { // Role Lead dan HR GA
            $driverQuery->whereRaw('1 = 0');
        }

        if ($status === 'disetujui') {
            // Data karyawan yang disetujui
            $karyawanDisetujui = (clone $karyawanQuery)->with('departemen')
                ->where('acc_lead', 2)
                ->where('acc_hr_ga', 2)
                ->where('acc_security_out', 2)
                ->get()
                ->map(function ($item) {
                    // Logika status detail untuk karyawan
                    $statusBadge = 'success';
                    $text = 'Sudah Kembali'; // Default jika semua acc

                    if ($item->acc_security_in == 1) {
                        $statusBadge = 'info';
                        $text = 'Sudah Keluar (Belum Kembali)';
                    }
                    
                    return [
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Karyawan',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = $karyawanDisetujui;

            // Data driver yang disetujui
            $driverDisetujui = (clone $driverQuery)->where('acc_admin', 2)
                ->where('acc_head_unit', 2)
                ->where('acc_security_out', 2)
                ->get()
                ->map(function ($item) {
                     // Logika status detail untuk driver
                    $statusBadge = 'success';
                    $text = 'Sudah Kembali'; // Default jika semua acc

                    if ($item->acc_security_in == 1) {
                        $statusBadge = 'info';
                        $text = 'Sudah Keluar (Belum Kembali)';
                    }

                    return [
                        'nama' => $item->nama_driver,
                        'departemen' => '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Driver',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = collect($data)->concat($driverDisetujui);
        } else if ($status === 'ditolak') {
            // Data karyawan yang ditolak
            $karyawanDitolak = (clone $karyawanQuery)->with('departemen')
                ->where(function($query) {
                    $query->where('acc_lead', 3)
                        ->orWhere('acc_hr_ga', 3)
                        ->orWhere('acc_security_out', 3)
                        ->orWhere('acc_security_in', 3);
                })
                ->get()
                ->map(function ($item) {
                    // Logika status detail untuk karyawan
                    $statusBadge = 'danger';
                    $text = 'Ditolak';
                    if($item->acc_lead == 3) $text = 'Ditolak Lead';
                    elseif($item->acc_hr_ga == 3) $text = 'Ditolak HR GA';

                    return [
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Karyawan',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = $karyawanDitolak;

            // Data driver yang ditolak
            $driverDitolak = (clone $driverQuery)->where(function($query) {
                    $query->where('acc_admin', 3)
                        ->orWhere('acc_head_unit', 3)
                        ->orWhere('acc_security_out', 3)
                        ->orWhere('acc_security_in', 3);
                })
                ->get()
                ->map(function ($item) {
                     // Logika status detail untuk driver
                    $statusBadge = 'danger';
                    $text = 'Ditolak';
                    if($item->acc_admin == 3) $text = 'Ditolak Admin';
                    elseif($item->acc_head_unit == 3) $text = 'Ditolak Head Unit';

                    return [
                        'nama' => $item->nama_driver,
                        'departemen' => '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Driver',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = collect($data)->concat($driverDitolak);
        } else if ($status === 'menunggu') {
            // Data karyawan yang sedang menunggu
            $karyawanMenunggu = (clone $karyawanQuery)->with('departemen')
                ->where('acc_security_out', 1) // Belum disetujui security out
                ->where('acc_lead', '!=', 3)
                ->where('acc_hr_ga', '!=', 3)
                ->where('acc_security_out', '!=', 3)
                ->where('acc_security_in', '!=', 3)
                ->get()
                ->map(function ($item) {
                    // Logika status detail untuk karyawan
                    $statusBadge = 'warning';
                    $text = 'Menunggu';
                    if($item->acc_lead == 1) $text = 'Menunggu Lead';
                    elseif($item->acc_lead == 2 && $item->acc_hr_ga == 1) $text = 'Menunggu HR GA';

                    return [
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Karyawan',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = $karyawanMenunggu;

            // Data driver yang sedang menunggu
            $driverMenunggu = (clone $driverQuery)->where('acc_security_out', 1) // Belum disetujui security out
                ->where('acc_admin', '!=', 3)
                ->where('acc_head_unit', '!=', 3)
                ->where('acc_security_out', '!=', 3)
                ->where('acc_security_in', '!=', 3)
                ->get()
                ->map(function ($item) {
                     // Logika status detail untuk driver
                    $statusBadge = 'warning';
                    $text = 'Menunggu';
                    if($item->acc_admin == 1) $text = 'Menunggu Admin/Checker';
                    elseif($item->acc_admin == 2 && $item->acc_head_unit == 1) $text = 'Menunggu Head Unit';

                    return [
                        'nama' => $item->nama_driver,
                        'departemen' => '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'tipe' => 'Driver',
                        'status' => $statusBadge,
                        'text' => $text
                    ];
                });
            $data = collect($data)->concat($driverMenunggu);
        }

        // Urutkan berdasarkan tanggal terbaru
        $dataArray = $data->toArray();
        usort($dataArray, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return response()->json($dataArray);
    }

    /**
     * Mengambil data permohonan terbaru dengan filter
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLatestRequests(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $dataType = $request->input('dataType', 'all'); // Ambil parameter dataType
        $data = [];
        $user = auth()->user();
        Log::debug('User Role ID: ' . $user->role_id . ' - User Departemen ID: ' . $user->departemen_id . ' - User Departemen Name: ' . ($user->departemen ? $user->departemen->name : 'N/A'));
        
        // Query dasar untuk karyawan
        $karyawanQuery = RequestKaryawan::query();
        
        // Filter berdasarkan role dan departemen untuk karyawan
        if ($user->role_id == 2) { // Role Lead
            Log::debug('Applying Lead filter for departemen_id: ' . $user->departemen_id);
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            Log::debug('Applying HR GA filter (excluding Admin and HR)');
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]);
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            Log::debug('Applying Checker/Head Unit filter (no karyawan data)');
            $karyawanQuery->whereRaw('1 = 0');
        }

        // Query dasar untuk driver
        $driverQuery = RequestDriver::query();

        // Filter berdasarkan role untuk driver
        if ($user->role_id == 2 || $user->role_id == 3) { // Role Lead dan HR GA
            $driverQuery->whereRaw('1 = 0');
        }

        // Data karyawan (hanya jika dataType adalah 'all' atau 'Karyawan')
        if ($dataType === 'all' || $dataType === 'Karyawan') {
            $requests = (clone $karyawanQuery)->with(['departemen'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->get();
            // Add debug log here before mapping
            Log::debug('Fetched Karyawan Requests (before map/filter): ' . $requests->count() . ' items');

            $karyawanRequests = $requests->map(function ($item) use ($user) {
                Log::debug('Processing Karyawan Item ID: ' . $item->id . ' - Departemen ID: ' . $item->departemen_id . ' - Departemen Name: ' . $item->departemen->name);
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
                        if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
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

                if ($user->role_id == 2 && $item->departemen_id != $user->departemen_id) {
                    Log::debug('Skipping item for Lead (departemen mismatch): ' . $item->id . ' - Item Departemen ID: ' . $item->departemen_id . ' vs User Departemen ID: ' . $user->departemen_id);
                    return null;
                }

                return [
                    'no_surat' => $item->no_surat ?? '-',
                    'nama' => $item->nama,
                    'departemen' => $item->departemen->name,
                    'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                    'jam_out' => $item->jam_out,
                    'jam_in' => $item->jam_in,
                    'status' => $statusBadge,
                    'text' => $text,
                    'tipe' => 'Karyawan',
                    'no_telp' => $item->no_telp ?? '-'
                ];
            });

            $data = array_merge($data, $karyawanRequests->toArray());
        }

        // Data driver (hanya jika dataType adalah 'all' atau 'Driver')
        if ($dataType === 'all' || $dataType === 'Driver') {
            $driverRequests = (clone $driverQuery)->with(['ekspedisi'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
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
                            if (\Carbon\Carbon::parse($item->jam_in)->isPast()) {
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
                        'nama' => $item->nama_driver,
                        'departemen' => $item->ekspedisi ? $item->ekspedisi->nama_ekspedisi : '-',
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('d M Y'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'status' => $statusBadge,
                        'text' => $text,
                        'tipe' => 'Driver',
                        'no_telp' => $item->no_hp_driver ?? '-'
                    ];
                });

            $data = array_merge($data, $driverRequests->toArray());
        }

        // Urutkan berdasarkan tanggal terbaru
        usort($data, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return response()->json($data);
    }

    /**
     * Mengambil tahun-tahun yang tersedia untuk filter
     * 
     * @return array
     */
    private function getAvailableYears()
    {
        $years = [];
        $currentYear = date('Y');
        
        // Ambil tahun dari data permohonan
        $karyawanYears = RequestKaryawan::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
            
        $driverYears = RequestDriver::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->pluck('year')
            ->toArray();
            
        // Gabungkan dan hapus duplikat
        $years = array_unique(array_merge($karyawanYears, $driverYears));
        
        // Tambahkan tahun saat ini jika belum ada
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
        }
        
        // Urutkan dari yang terbaru
        rsort($years);
        
        return $years;
    }
}

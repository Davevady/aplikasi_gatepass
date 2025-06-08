<?php

namespace App\Http\Controllers;

use App\Models\RequestKaryawan;
use App\Models\Departemen;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RequestKaryawanController extends Controller
{
    /**
     * Menampilkan daftar permohonan izin keluar karyawan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Permohonan Izin Keluar Karyawan';
        $user = auth()->user();
        $requestKaryawans = collect(); // Inisialisasi collection kosong
        $departemens = Departemen::all(); // Ambil semua data departemen

        // Ambil data permohonan karyawan berdasarkan role
        if ($user->role_id != 4 && $user->role_id != 5) { // Bukan role checker dan head unit
            $requestKaryawans = RequestKaryawan::with('departemen')->get();
        }
        
        // Menghitung total request berdasarkan status dengan urutan persetujuan untuk permohonan yang terlihat oleh user
        $totalMenunggu = 0;
        $totalDisetujui = 0;
        $totalDitolak = 0;
        $totalRequest = 0;

        if ($user->role_id != 4 && $user->role_id != 5) { // Bukan role checker dan head unit
             // Permohonan Menunggu Karyawan: Belum disetujui semua pihak DAN belum ditolak oleh siapapun DAN belum keluar security
            $totalMenunggu = RequestKaryawan::where(function($query) {
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
                    
            // Permohonan Disetujui Karyawan: Sudah disetujui Lead, HR GA, dan Security Out (baik sudah kembali atau belum)
            $totalDisetujui = RequestKaryawan::where('acc_lead', 2)
                ->where('acc_hr_ga', 2)
                ->where('acc_security_out', 2)
                ->count();
                    
            // Permohonan Ditolak Karyawan: Ditolak oleh salah satu pihak
            $totalDitolak = RequestKaryawan::where(function($query) {
                $query->where('acc_lead', 3) // Lead menolak
                    ->orWhere('acc_hr_ga', 3) // HR GA menolak
                    ->orWhere('acc_security_out', 3) // Security Out menolak
                    ->orWhere('acc_security_in', 3); // Security In menolak
            })->count();
                    
            // Total semua request karyawan yang terlihat oleh user
            $totalRequest = RequestKaryawan::count();
        }

        // Mengambil tahun-tahun yang tersedia untuk filter
        $years = $this->getAvailableYears();

        return view('superadmin.request-karyawan.index', compact(
            'title',
            'requestKaryawans',
            'departemens',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest',
            'years'
        ));
    }

    /**
     * Menampilkan form pengajuan izin keluar karyawan
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departemens = Departemen::all();
        $title = 'Form Izin Keluar Karyawan';
        return view('karyawan', compact('departemens', 'title'));
    }

    /**
     * Menyimpan permohonan izin keluar karyawan baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'departemen_id' => 'required|exists:departemens,id',
                'keperluan' => 'required|string',
                'jam_in' => 'required',
                'jam_out' => 'required',
                'acc_lead' => 'nullable',
                'acc_hr_ga' => 'nullable',
                'acc_security_in' => 'nullable',
                'acc_security_out' => 'nullable',
            ]);

            // Set default approval ke 1 (menunggu)
            $validated = array_merge($validated, [
                'acc_lead' => 1,
                'acc_hr_ga' => 1,
                'acc_security_in' => 1,
                'acc_security_out' => 1
            ]);

            // Mendapatkan kode departemen
            $departemen = Departemen::find($validated['departemen_id']);
            $departemenCode = $departemen->code;

            // Generate nomor urut
            $today = now();
            $year = $today->format('y'); // Tahun 2 digit
            $month = $today->format('m'); // Bulan 2 digit
            $day = $today->format('d'); // Tanggal 2 digit

            // Hitung nomor urut untuk hari ini berdasarkan departemen
            $lastRequest = RequestKaryawan::where('departemen_id', $validated['departemen_id'])
                                        ->whereDate('created_at', $today->toDateString())
                                        ->latest()
                                        ->first();
            $nextSequence = $lastRequest ? (int) substr($lastRequest->no_surat, strrpos($lastRequest->no_surat, '/') - 3, 3) + 1 : 1;
            $nomorUrut = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // Buat no_surat
            $noSurat = "SIP/{$departemenCode}/{$nomorUrut}/{$day}/{$month}/{$year}";
            $validated['no_surat'] = $noSurat;

            // Buat request karyawan baru
            $requestKaryawan = RequestKaryawan::create($validated);

            // Cari user dengan role admin, lead, hr-ga, dan security
            $users = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'lead', 'hr-ga', 'security']);
            })->get();

            // Buat notifikasi untuk setiap user yang ditemukan
            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Permohonan Izin Keluar ' . $validated['nama'],
                    'message' => 'Permohonan izin keluar atas nama ' . $validated['nama'] . 
                               ' dari departemen ' . Departemen::find($validated['departemen_id'])->name . 
                               ' untuk keperluan ' . $validated['keperluan'] . 
                               ' sedang menunggu persetujuan',
                    'type' => 'karyawan',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            // Pesan sukses
            $successMessage = "Pengajuan izin karyawan berhasil dikirim.\n" .
                            "Nama: " . $validated['nama'] . "\n" .
                            "Departemen: " . Departemen::find($validated['departemen_id'])->name . "\n" .
                            "Jam Keluar: " . $validated['jam_out'] . "\n" .
                            "Jam Kembali: " . $validated['jam_in'];

            // Return response berdasarkan tipe request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ]);
            }

            return redirect()->back()->with('success', $successMessage);
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menangani persetujuan permohonan izin keluar karyawan
     * 
     * @param int $id ID request karyawan
     * @param int $role_id ID role yang menyetujui
     * @return \Illuminate\Http\JsonResponse
     */
    public function accRequest($id, $role_id)
    {
        try {
            // Ambil data request karyawan dengan relasi departemen
            $requestKaryawan = RequestKaryawan::with(['departemen'])->find($id);

            // Cek apakah data request karyawan ada
            if (!$requestKaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Request Karyawan tidak ditemukan'
                ], 404);
            }

            // Update status persetujuan berdasarkan role
            switch ($role_id) {
                case 2: // Lead
                    $requestKaryawan->acc_lead = 2;
                    $notificationTitle = 'Disetujui Lead';
                    $notificationMessage = 'telah disetujui oleh Lead dan menunggu persetujuan HR GA';
                    // Cari user dengan role HR GA dan admin
                    $users = \App\Models\User::whereHas('role', function($query) {
                        $query->whereIn('slug', ['hr-ga', 'admin']);
                    })->get();
                    break;
                case 3: // HR GA
                    $requestKaryawan->acc_hr_ga = 2;
                    $notificationTitle = 'Disetujui HR GA';
                    $notificationMessage = 'telah disetujui oleh HR GA dan menunggu persetujuan Security Out';
                    // Cari user dengan role security dan admin
                    $users = \App\Models\User::whereHas('role', function($query) {
                        $query->whereIn('slug', ['security', 'admin']);
                    })->get();
                    break;
                case 6: // Security
                    if ($requestKaryawan->acc_security_out == 1) {
                        $requestKaryawan->acc_security_out = 2;
                        $notificationTitle = 'Disetujui Security Out';
                        $notificationMessage = 'telah disetujui oleh Security Out dan menunggu karyawan kembali';
                        // Cari user dengan role admin
                        $users = \App\Models\User::whereHas('role', function($query) {
                            $query->where('slug', 'admin');
                        })->get();
                    } else {
                        $requestKaryawan->acc_security_in = 2;
                        $notificationTitle = 'Disetujui Security In';
                        $notificationMessage = 'telah disetujui oleh Security In dan permohonan selesai';
                        // Cari user dengan role admin
                        $users = \App\Models\User::whereHas('role', function($query) {
                            $query->where('slug', 'admin');
                        })->get();
                    }
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Role tidak valid'
                    ], 400);
            }

            // Simpan perubahan
            $requestKaryawan->save();

            // Buat notifikasi untuk setiap user yang ditemukan
            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Permohonan Izin Keluar ' . $requestKaryawan->nama . ' ' . $notificationTitle,
                    'message' => 'Permohonan izin keluar atas nama ' . $requestKaryawan->nama . 
                               ' dari departemen ' . $requestKaryawan->departemen->name . 
                               ' ' . $notificationMessage,
                    'type' => 'karyawan',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status persetujuan permohonan izin keluar karyawan
     * 
     * @param int $id ID request karyawan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        try {
            $requestKaryawan = RequestKaryawan::find($id);

            if (!$requestKaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request Karyawan tidak ditemukan.'
                ], 404);
            }

            $statuses = $request->input('statuses');
            
            foreach ($statuses as $role => $status) {
                // Pastikan role yang diperbarui sesuai dengan kolom di database
                if ($role === 'lead') {
                    $requestKaryawan->acc_lead = $status;
                } elseif ($role === 'hr-ga') {
                    $requestKaryawan->acc_hr_ga = $status;
                } elseif ($role === 'security-out') {
                    $requestKaryawan->acc_security_out = $status;
                } elseif ($role === 'security-in') {
                    $requestKaryawan->acc_security_in = $status;
                }
            }

            $requestKaryawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Status permohonan berhasil diperbarui.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating status for RequestKaryawan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data permohonan izin keluar karyawan
     * 
     * @param int $id ID request karyawan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            Log::info('Attempting to update RequestKaryawan with ID: ' . $id);
            // Validasi input
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'departemen_id' => 'required|exists:departemens,id',
                'keperluan' => 'required|string',
                'jam_in' => 'required',
                'jam_out' => 'required',
            ]);

            // Ambil data request karyawan
            $requestKaryawan = RequestKaryawan::find($id);

            // Cek apakah data request karyawan ada
            if (!$requestKaryawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Request Karyawan tidak ditemukan'
                ], 404);
            }

            // Update data
            $requestKaryawan->update($validated);

            // Buat notifikasi
            $users = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'lead', 'hr-ga', 'security']);
            })->get();

            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Update Data Permohonan Izin Karyawan',
                    'message' => 'Data permohonan izin karyawan ' . $requestKaryawan->nama . 
                               ' dari departemen ' . $requestKaryawan->departemen->name . 
                               ' telah diperbarui',
                    'type' => 'karyawan',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil data permohonan karyawan untuk ekspor
     * 
     * @param int $month
     * @param int $year
     * @param string $type
     * @param string $exportType
     * @return array
     */
    private function getDataForExport($month, $year, $type, $exportType)
    {
        $query = RequestKaryawan::with(['departemen']);

        if ($exportType === 'filtered') {
            $query->whereMonth('created_at', $month)
                  ->whereYear('created_at', $year);
        }

        $requests = $query->orderBy('created_at', 'desc')
                          ->get()
                          ->map(function ($item) {
                                $statusBadge = 'warning';
                                $text = 'Menunggu';
                                
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
                                        if (Carbon::parse($item->jam_in)->isPast()) {
                                            $statusBadge = 'danger';
                                            $text = 'Hangus';
                                        } else {
                                            $statusBadge = 'info';
                                            $text = 'Menunggu Security Keluar';
                                        }
                                    } elseif ($item->acc_security_out == 2) {
                                        if ($item->acc_security_in == 1) {
                                            if (Carbon::parse($item->jam_in)->isPast()) {
                                                $statusBadge = 'warning';
                                                $text = 'Terlambat';
                                            } else {
                                                $statusBadge = 'info';
                                                $text = 'Menunggu Security Masuk';
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
                                    'tanggal' => Carbon::parse($item->created_at)->format('Y-m-d'),
                                    'jam_out' => $item->jam_out ?? '-',
                                    'jam_in' => $item->jam_in ?? '-',
                                    'status' => $statusBadge,
                                    'text' => $text,
                                    'tipe' => 'Karyawan'
                                ];
                            });

        // Filter berdasarkan tipe jika bukan 'all'
        if ($type !== 'all') {
            $requests = $requests->filter(function($item) use ($type) {
                return $item['tipe'] === $type;
            });
        }

        return $requests->values()->all(); // Reset array keys
    }

    /**
     * Export data permohonan karyawan ke PDF
     * 
     * @param int $month
     * @param int $year
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function exportPDF($month, $year, $type = 'all')
    {
        $exportType = request()->query('type', 'filtered');
        $data = $this->getDataForExport($month, $year, $type, $exportType);
        $pdf = PDF::loadView('exports.request-karyawan', compact('data', 'month', 'year', 'type'));
        return $pdf->download('laporan_karyawan.pdf');
    }

    /**
     * Export data permohonan karyawan ke Excel
     * 
     * @param int $month
     * @param int $year
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    public function exportExcel($month, $year, $type = 'all')
    {
        $exportType = request()->query('type', 'filtered');
        $data = $this->getDataForExport($month, $year, $type, $exportType);
        
        return Excel::download(new \App\Exports\RequestKaryawanExport($data), 'laporan_karyawan.xlsx');
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
            
        // Gabungkan dan hapus duplikat
        $years = array_unique($karyawanYears);
        
        // Tambahkan tahun saat ini jika belum ada
        if (!in_array($currentYear, $years)) {
            $years[] = $currentYear;
        }
        
        // Urutkan dari yang terbaru
        rsort($years);
        
        return $years;
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
        $user = auth()->user();
        $data = [];

        if ($user->role_id != 4 && $user->role_id != 5) {
            $requests = RequestKaryawan::with(['departemen'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) use ($user) {
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
                                $text = 'Menunggu Security Keluar';
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
                                    $text = 'Menunggu Security Masuk';
                                }
                            } elseif ($item->acc_security_in == 2) {
                                $statusBadge = 'success';
                                $text = 'Sudah Kembali';
                            }
                        }
                    }

                    return [
                        'id' => $item->id,
                        'no_surat' => $item->no_surat ?? '-',
                        'nama' => $item->nama,
                        'departemen' => $item->departemen->name,
                        'tanggal' => \Carbon\Carbon::parse($item->created_at)->format('Y-m-d'),
                        'jam_out' => $item->jam_out,
                        'jam_in' => $item->jam_in,
                        'keperluan' => $item->keperluan,
                        'status_badge' => $statusBadge,
                        'status_text' => $text,
                        'tipe' => 'Karyawan',
                        'no_telp' => $item->no_telp ?? '-',
                        'acc_lead' => $item->acc_lead,
                        'acc_hr_ga' => $item->acc_hr_ga,
                        'acc_security_out' => $item->acc_security_out,
                        'acc_security_in' => $item->acc_security_in,
                        'user_role_id' => $user->role_id,
                        'user_role_title' => $user->role->title ?? '',
                        'departemen_id' => $item->departemen_id,
                    ];
                });

            $data = array_merge($data, $requests->toArray());
        }

        return response()->json($data);
    }
}

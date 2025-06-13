<?php

namespace App\Http\Controllers;

use App\Models\RequestKaryawan;
use App\Models\Departemen;
use App\Models\Notification;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RequestKaryawanController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Menampilkan daftar permohonan izin keluar karyawan
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Permohonan Izin Keluar Karyawan';
        $user = auth()->user();
        $departemens = Departemen::all(); // Ambil semua data departemen

        // Query dasar untuk permohonan karyawan
        $karyawanQuery = RequestKaryawan::with('departemen');

        // Filter berdasarkan role dan departemen
        if ($user->role_id == 2) { // Role Lead
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

        // Ambil data permohonan karyawan yang sudah difilter
        $requestKaryawans = $karyawanQuery->get();
        
        // Menghitung total request berdasarkan status dengan urutan persetujuan untuk permohonan yang terlihat oleh user
        $totalMenunggu = 0;
        $totalDisetujui = 0;
        $totalDitolak = 0;
        $totalRequest = 0;

        // Hitung statistik hanya jika user memiliki akses (bukan checker dan head unit)
        if ($user->role_id != 4 && $user->role_id != 5) {
             // Permohonan Menunggu Karyawan
            $totalMenunggu = (clone $karyawanQuery)->where(function($query) {
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
            $totalDisetujui = (clone $karyawanQuery)->where('acc_lead', 2)
                ->where('acc_hr_ga', 2)
                ->where('acc_security_out', 2)
                ->count();
                    
            // Permohonan Ditolak Karyawan
            $totalDitolak = (clone $karyawanQuery)->where(function($query) {
                $query->where('acc_lead', 3) // Lead menolak
                    ->orWhere('acc_hr_ga', 3) // HR GA menolak
                    ->orWhere('acc_security_out', 3) // Security Out menolak
                    ->orWhere('acc_security_in', 3); // Security In menolak
            })->count();
                    
            // Total semua request karyawan
            $totalRequest = (clone $karyawanQuery)->count();
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
                'no_telp' => 'required|string|max:15|regex:/^[0-9]+$/',
                'departemen_id' => 'required|exists:departemens,id',
                'keperluan' => 'required|string',
                'jam_in' => 'required',
                'jam_out' => 'required',
                'acc_lead' => 'nullable',
                'acc_hr_ga' => 'nullable',
                'acc_security_in' => 'nullable',
                'acc_security_out' => 'nullable',
            ], [
                'no_telp.required' => 'Nomor telepon wajib diisi.',
                'no_telp.max' => 'Nomor telepon maksimal 15 digit.',
                'no_telp.regex' => 'Nomor telepon hanya boleh berisi angka.',
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
                                        ->orderBy('no_surat', 'desc')
                                        ->first();

            if ($lastRequest) {
                // Ambil nomor urut dari no_surat terakhir
                preg_match('/SIP\/' . $departemenCode . '\/([0-9]{3})\//', $lastRequest->no_surat, $matches);
                $lastSequence = isset($matches[1]) ? (int)$matches[1] : 0;
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }

            // Pastikan nomor urut tidak melebihi 999
            if ($nextSequence > 999) {
                throw new \Exception('Nomor urut melebihi batas maksimum (999)');
            }

            $nomorUrut = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // Buat no_surat
            $noSurat = "SIP/{$departemenCode}/{$nomorUrut}/{$day}/{$month}/{$year}";
            $validated['no_surat'] = $noSurat;

            // Cek apakah nomor surat sudah ada
            $existingRequest = RequestKaryawan::where('no_surat', $noSurat)->first();
            if ($existingRequest) {
                throw new \Exception('Nomor surat sudah ada. Silakan coba lagi.');
            }

            // Buat request karyawan baru
            $requestKaryawan = RequestKaryawan::create($validated);

            // Format pesan untuk karyawan
            $karyawanMessage = "🔔 *Notifikasi Permohonan Izin Keluar*\n\n" .
                             "No Surat: {$noSurat}\n" .
                             "Nama: {$validated['nama']}\n" .
                             "Departemen: {$departemen->name}\n" .
                             "Keperluan: {$validated['keperluan']}\n" .
                             "Jam Keluar: {$validated['jam_out']}\n" .
                             "Jam Kembali: {$validated['jam_in']}\n\n" .
                             "Status: Menunggu Persetujuan";

            // Kirim notifikasi ke karyawan
            if ($validated['no_telp']) {
                try {
                    // Bersihkan nomor telepon
                    $phone = preg_replace('/[^0-9]/', '', $validated['no_telp']);
                    if (substr($phone, 0, 2) !== '62') {
                        $phone = '62' . ltrim($phone, '0');
                    }

                    Log::info('Sending WhatsApp message to karyawan', [
                        'original_phone' => $validated['no_telp'],
                        'formatted_phone' => $phone
                    ]);

                    // Cek status device terlebih dahulu
                    if (!$this->whatsappService->checkDeviceStatus()) {
                        Log::error('WhatsApp device is not connected or inactive');
                        throw new \Exception('WhatsApp device is not connected or inactive');
                    }

                    // Validasi nomor telepon
                    $isPhoneNumberValid = $this->whatsappService->validateNumber($phone);
                    if (!$isPhoneNumberValid) {
                        Log::warning('Fonnte API reported invalid phone number or could not validate', ['phone' => $phone]);
                        // Kita akan tetap mencoba mengirim pesan meskipun validasi Fonnte gagal, untuk melihat apakah pesan terkirim.
                        // Jika pesan tidak terkirim, masalah mungkin ada di nomor atau API Fonnte.
                    }

                    $result = $this->whatsappService->sendMessage($phone, $karyawanMessage);
                    
                    if ($result) {
                        Log::info('WhatsApp message sent successfully to karyawan', [
                            'phone' => $phone
                        ]);
                    } else {
                        Log::error('Failed to send WhatsApp message to karyawan', [
                            'phone' => $phone
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Error sending WhatsApp message to karyawan', [
                        'phone' => $validated['no_telp'],
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Format pesan untuk admin/approver
            $adminMessage = "🔔 *Notifikasi Permohonan Izin Keluar Karyawan*\n\n" .
                          "No Surat: {$noSurat}\n" .
                          "Nama: {$validated['nama']}\n" .
                          "Departemen: {$departemen->name}\n" .
                          "Keperluan: {$validated['keperluan']}\n" .
                          "Jam Keluar: {$validated['jam_out']}\n" .
                          "Jam Kembali: {$validated['jam_in']}\n\n" .
                          "Mohon untuk segera melakukan persetujuan.";

            // Kirim notifikasi ke admin
            $adminUsers = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'lead', 'hr-ga']);
            })->get();

            foreach($adminUsers as $admin) {
                if ($admin->no_telp) {
                    try {
                        $result = $this->whatsappService->sendMessage($admin->no_telp, $adminMessage);
                        Log::info('WhatsApp message sent to admin', [
                            'phone' => $admin->no_telp,
                            'message' => $adminMessage,
                            'result' => $result
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send WhatsApp message to admin', [
                            'phone' => $admin->no_telp,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

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
                               ' dari departemen ' . $departemen->name . 
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
                            "Departemen: " . $departemen->name . "\n" .
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
        $user = auth()->user(); // Ambil user yang sedang login
        $query = RequestKaryawan::with(['departemen']);

        // Terapkan filter departemen berdasarkan role user
        if ($user->role_id == 2) { // Role Lead
            $query->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            $query->whereIn('departemen_id', function($q) {
                $q->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]); // Kecuali departemen Admin dan HR
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            $query->whereRaw('1 = 0'); // Query yang selalu false
        }

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
                                    $text = 'Ditolak HR/GA';
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
                                    $text = 'Menunggu HR/GA';
                                }
                                elseif($item->acc_lead == 2 && $item->acc_hr_ga == 2) {
                                    if($item->acc_security_out == 1) {
                                        if (Carbon::parse($item->jam_in)->isPast()) {
                                            $statusBadge = 'danger';
                                            $text = 'Hangus';
                                        } else {
                                            $statusBadge = 'info';
                                            $text = 'Disetujui (Belum Keluar)';
                                        }
                                    } elseif ($item->acc_security_out == 2) {
                                        if ($item->acc_security_in == 1) {
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

        // Filter berdasarkan tipe jika bukan 'all' - ini tidak lagi diperlukan karena sudah difilter oleh peran
        // if ($type !== 'all') {
        //     $requests = $requests->filter(function($item) use ($type) {
        //         return $item['tipe'] === $type;
        //     });
        // }

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
        return $pdf->stream('laporan_karyawan.pdf');
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
        Log::debug('User Role ID in RequestKaryawanController@getLatestRequests: ' . $user->role_id . ' - User Departemen ID: ' . $user->departemen_id . ' - User Departemen Name: ' . ($user->departemen ? $user->departemen->name : 'N/A'));
        $data = [];

        $karyawanQuery = RequestKaryawan::with(['departemen']);

        // Filter berdasarkan role dan departemen untuk karyawan
        if ($user->role_id == 2) { // Role Lead
            Log::debug('Applying Lead filter in RequestKaryawanController@getLatestRequests for departemen_id: ' . $user->departemen_id);
            $karyawanQuery->where('departemen_id', $user->departemen_id);
        } elseif ($user->role_id == 3) { // Role HR GA
            Log::debug('Applying HR GA filter in RequestKaryawanController@getLatestRequests (excluding Admin and HR)');
            $karyawanQuery->whereIn('departemen_id', function($query) {
                $query->select('id')
                    ->from('departemens')
                    ->whereNotIn('id', [1, 2]);
            });
        } elseif ($user->role_id == 4 || $user->role_id == 5) { // Role Checker dan Head Unit
            Log::debug('Applying Checker/Head Unit filter in RequestKaryawanController@getLatestRequests (no karyawan data)');
            $karyawanQuery->whereRaw('1 = 0');
        }

        // Hanya ambil data karyawan jika user memiliki akses (bukan role checker dan head unit)
        if ($user->role_id != 4 && $user->role_id != 5) {
            $requests = (clone $karyawanQuery)
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->orderBy('created_at', 'desc')
                ->get();
                Log::debug('Fetched Karyawan Requests in RequestKaryawanController@getLatestRequests (before map/filter): ' . $requests->count() . ' items');

                $karyawanRequests = $requests->map(function ($item) use ($user) {
                    Log::debug('Processing Karyawan Item in RequestKaryawanController@getLatestRequests ID: ' . $item->id . ' - Departemen ID: ' . $item->departemen_id . ' - Departemen Name: ' . $item->departemen->name);
                    // Log::debug('Original Request Karyawan Item: ' . json_encode($item->toArray()));
                    $statusBadge = 'warning';
                    $text = 'Menunggu';
                    
                    // Cek jika ada yang menolak
                    if($item->acc_lead == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak Lead';
                    } 
                    elseif($item->acc_hr_ga == 3) {
                        $statusBadge = 'danger';
                        $text = 'Ditolak HR/GA';
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
                        $text = 'Menunggu HR/GA';
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

                    $mappedItem = [
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

                    // Logika filter departemen untuk Lead
                    if ($user->role_id == 2 && $item->departemen_id != $user->departemen_id) {
                        Log::debug('Skipping item for Lead in RequestKaryawanController@getLatestRequests: ' . $item->id . ' - Item Departemen ID: ' . $item->departemen_id . ' vs User Departemen ID: ' . $user->departemen_id);
                        return null;
                    }

                    return $mappedItem;
                })
                ->filter() // Hapus item yang null (tidak lolos filter Lead)
                ->values() // Reset index array
                ->toArray();
            
            $data = array_merge($data, $karyawanRequests);
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Export data permohonan karyawan ke PDF per item
     * 
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function exportSinglePDF($id)
    {
        $requestKaryawan = RequestKaryawan::with('departemen')->find($id);

        if (!$requestKaryawan) {
            abort(404, 'Data Permohonan Karyawan tidak ditemukan.');
        }

        // Format data sesuai kebutuhan PDF
        $data = [
            'no_surat' => $requestKaryawan->no_surat,
            'nama' => $requestKaryawan->nama,
            'no_telp' => $requestKaryawan->no_telp,
            'departemen' => $requestKaryawan->departemen->name,
            'keperluan' => $requestKaryawan->keperluan,
            'jam_out' => $requestKaryawan->jam_out,
            'jam_in' => $requestKaryawan->jam_in,
            'tanggal' => \Carbon\Carbon::parse($requestKaryawan->created_at)->format('Y-m-d'), // Menambahkan tanggal pengajuan
        ];

        // Logika untuk status persetujuan yang lebih detail
        $statusLead = 'Menunggu';
        if ($requestKaryawan->acc_lead == 2) {
            $statusLead = 'Disetujui';
        } elseif ($requestKaryawan->acc_lead == 3) {
            $statusLead = 'Ditolak';
        }

        $statusHrGa = 'Menunggu';
        if ($requestKaryawan->acc_hr_ga == 2) {
            $statusHrGa = 'Disetujui';
        } elseif ($requestKaryawan->acc_hr_ga == 3) {
            $statusHrGa = 'Ditolak';
        }

        $statusSecurityOut = 'Belum Keluar';
        if ($requestKaryawan->acc_security_out == 2) {
            $statusSecurityOut = 'Sudah Keluar';
        } elseif ($requestKaryawan->acc_security_out == 3) {
            $statusSecurityOut = 'Ditolak Keluar';
        }

        $statusSecurityIn = 'Belum Kembali';
        if ($requestKaryawan->acc_security_in == 2) {
            $statusSecurityIn = 'Sudah Kembali';
        } elseif ($requestKaryawan->acc_security_in == 3) {
            $statusSecurityIn = 'Ditolak Kembali';
        }

        // Tambahkan status ke data
        $data['status_lead'] = $statusLead;
        $data['status_hr_ga'] = $statusHrGa;
        $data['status_security_out'] = $statusSecurityOut;
        $data['status_security_in'] = $statusSecurityIn;

        $pdf = Pdf::loadView('exports.karyawan-single', compact('data'));
        $pdf->setPaper('A4', 'portrait');
        // Bersihkan karakter "/" dan "\" dari no_surat
        $cleanNoSurat = str_replace(['/', '\\'], '-', $requestKaryawan->no_surat);
        return $pdf->stream('surat_izin_karyawan_' . $cleanNoSurat . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\RequestDriver;
use App\Models\Ekspedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RequestDriverController extends Controller
{
    /**
     * Menampilkan daftar permohonan izin keluar driver
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Permohonan Izin Keluar Driver';
        $user = auth()->user();
        $requestDrivers = collect(); // Inisialisasi collection kosong
        $ekspedisis = Ekspedisi::all(); // Ambil semua data ekspedisi

        // Ambil data permohonan driver berdasarkan role
        if ($user->role_id != 2 && $user->role_id != 3) { // Bukan role lead dan hr-ga
            $requestDrivers = RequestDriver::get();
        }
        
        // Menghitung total request berdasarkan status untuk permohonan yang terlihat oleh user
        $totalMenunggu = 0;
        $totalDisetujui = 0;
        $totalDitolak = 0;
        $totalRequest = 0;

        if ($user->role_id != 2 && $user->role_id != 3) { // Bukan role lead dan hr-ga
             // Permohonan Menunggu Driver: Belum disetujui semua pihak DAN belum ditolak oleh siapapun DAN belum keluar security
             $totalMenunggu = RequestDriver::where(function($query) {
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
                
            // Permohonan Disetujui Driver: Sudah disetujui Admin, Head Unit, dan Security Out (baik sudah kembali atau belum)
            $totalDisetujui = RequestDriver::where('acc_admin', 2)
                ->where('acc_head_unit', 2)
                ->where('acc_security_out', 2)
                ->count();
                
            // Permohonan Ditolak Driver: Ditolak oleh salah satu pihak
            $totalDitolak = RequestDriver::where(function($query) {
                $query->where('acc_admin', 3) // Admin menolak
                    ->orWhere('acc_head_unit', 3) // Head Unit menolak
                    ->orWhere('acc_security_out', 3) // Security Out menolak
                    ->orWhere('acc_security_in', 3); // Security In menolak
            })->count();
                
            // Total semua request driver yang terlihat oleh user
            $totalRequest = RequestDriver::count();
        }
        
        // Pass data to the view
        return view('superadmin.request-driver.index', compact(
            'title', 
            'requestDrivers',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest',
            'ekspedisis' // Tambahkan ekspedisis ke view
        ));
    }

    /**
     * Menampilkan form pengajuan izin keluar driver
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $title = 'Form Izin Keluar Driver';
        $ekspedisis = \App\Models\Ekspedisi::all();
        return view('driver', compact('title', 'ekspedisis'));
    }

    /**
     * Menyimpan permohonan izin keluar driver baru
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'ekspedisi_id' => 'required|exists:ekspedisis,id',
            'nopol_kendaraan' => 'required|string|max:255',
            'nama_driver' => 'required|string|max:255',
            'no_hp_driver' => 'required|string|max:255',
            'nama_kernet' => 'nullable|string|max:255',
            'no_hp_kernet' => 'nullable|string|max:255',
            'keperluan' => 'required|string',
            'jam_in' => 'required',
            'jam_out' => 'required',
            'acc_admin' => 'nullable',
            'acc_head_unit' => 'nullable',
            'acc_security_in' => 'nullable',
            'acc_security_out' => 'nullable',
        ]);

        // Set default approval ke 1 (menunggu)
        $validated = array_merge($validated, [
            'acc_admin' => 1,
            'acc_head_unit' => 1,
            'acc_security_in' => 1,
            'acc_security_out' => 1
        ]);

        try {
            // Generate nomor urut
            $today = now();
            $year = $today->format('y'); // Tahun 2 digit
            $month = $today->format('m'); // Bulan 2 digit
            $day = $today->format('d'); // Tanggal 2 digit

            // Ambil nomor urut terakhir untuk hari ini
            $lastRequest = RequestDriver::whereDate('created_at', $today->toDateString())
                ->orderByDesc('id')
                ->first();

            if ($lastRequest) {
                // Ambil nomor urut dari no_surat terakhir
                preg_match('/SID\\/([0-9]{3})\\//', $lastRequest->no_surat, $matches);
                $lastSequence = isset($matches[1]) ? (int)$matches[1] : 0;
                $nextSequence = $lastSequence + 1;
            } else {
                $nextSequence = 1;
            }
            $nomorUrut = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);

            // Buat no_surat
            $noSurat = "SID/{$nomorUrut}/{$day}/{$month}/{$year}";
            $validated['no_surat'] = $noSurat;

            // Buat request driver baru
            $requestDriver = RequestDriver::create($validated);

            // Cari user dengan role admin, checker, head unit, dan security
            $users = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'checker', 'head-unit', 'security']);
            })->get();

            // Buat notifikasi untuk setiap user yang ditemukan
            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Permohonan Izin Keluar Driver ' . Ekspedisi::find($validated['ekspedisi_id'])->nama_ekspedisi,
                    'message' => 'Permohonan izin driver ' . Ekspedisi::find($validated['ekspedisi_id'])->nama_ekspedisi . 
                               ' dengan nopol ' . $validated['nopol_kendaraan'] . 
                               ' sedang menunggu persetujuan',
                    'type' => 'driver',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            // Pesan sukses
            $successMessage = "Pengajuan izin driver berhasil dikirim.\n" .
                            "Nama Ekpedisi: " . Ekspedisi::find($validated['ekspedisi_id'])->nama_ekspedisi . "\n" .
                            "Nomor Polisi: " . $validated['nopol_kendaraan'] . "\n" .
                            "Nama Driver: " . $validated['nama_driver'] . "\n" .
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
     * Menangani persetujuan permohonan izin keluar driver
     * 
     * @param int $id ID request driver
     * @param int $role_id ID role yang menyetujui
     * @return \Illuminate\Http\JsonResponse
     */
    public function accRequest($id, $role_id)
    {
        try {
            // Ambil data request driver
            $requestDriver = RequestDriver::find($id);

            // Cek apakah data request driver ada
            if (!$requestDriver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Request Driver tidak ditemukan'
                ], 404);
            }

            // Update status persetujuan berdasarkan role
            switch ($role_id) {
                case 4: // Checker
                    $requestDriver->acc_admin = 2;
                    $notificationTitle = 'Disetujui Checker';
                    $notificationMessage = 'telah disetujui oleh Checker dan menunggu persetujuan Head Unit';
                    // Cari user dengan role head unit dan admin
                    $users = \App\Models\User::whereHas('role', function($query) {
                        $query->whereIn('slug', ['head-unit', 'admin']);
                    })->get();
                    break;
                case 5: // Head Unit
                    $requestDriver->acc_head_unit = 2;
                    $notificationTitle = 'Disetujui Head Unit';
                    $notificationMessage = 'telah disetujui oleh Head Unit dan menunggu persetujuan Security Out';
                    // Cari user dengan role security dan admin
                    $users = \App\Models\User::whereHas('role', function($query) {
                        $query->whereIn('slug', ['security', 'admin']);
                    })->get();
                    break;
                case 6: // Security
                    if ($requestDriver->acc_security_out == 1) {
                        $requestDriver->acc_security_out = 2;
                        $notificationTitle = 'Disetujui Security Out';
                        $notificationMessage = 'telah disetujui oleh Security Out dan menunggu driver kembali';
                        // Cari user dengan role admin
                        $users = \App\Models\User::whereHas('role', function($query) {
                            $query->where('slug', 'admin');
                        })->get();
                    } else {
                        $requestDriver->acc_security_in = 2;
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
            $requestDriver->save();

            // Buat notifikasi untuk setiap user yang ditemukan
            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => $notificationTitle,
                    'message' => 'Permohonan izin driver ' . $requestDriver->nama_ekspedisi . 
                               ' dengan nopol ' . $requestDriver->nopol_kendaraan . 
                               ' ' . $notificationMessage,
                    'type' => 'driver',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Permohonan izin driver berhasil disetujui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status persetujuan permohonan izin keluar driver
     * 
     * @param int $id ID request driver
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        try {
            // Ambil data request driver
            $requestDriver = RequestDriver::find($id);

            // Cek apakah data request driver ada
            if (!$requestDriver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Request Driver tidak ditemukan'
                ], 404);
            }

            // Update status berdasarkan input
            $statuses = $request->input('statuses');
            
            if (isset($statuses['admin'])) {
                $requestDriver->acc_admin = $statuses['admin'];
            }
            if (isset($statuses['head-unit'])) {
                $requestDriver->acc_head_unit = $statuses['head-unit'];
            }
            if (isset($statuses['security-out'])) {
                $requestDriver->acc_security_out = $statuses['security-out'];
            }
            if (isset($statuses['security-in'])) {
                $requestDriver->acc_security_in = $statuses['security-in'];
            }

            // Simpan perubahan
            $requestDriver->save();

            // Buat notifikasi
            $users = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'checker', 'head-unit', 'security']);
            })->get();

            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Update Status Permohonan Izin Driver',
                    'message' => 'Status permohonan izin driver ' . $requestDriver->nama_ekspedisi . 
                               ' dengan nopol ' . $requestDriver->nopol_kendaraan . 
                               ' telah diperbarui',
                    'type' => 'driver',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update data permohonan izin keluar driver
     * 
     * @param int $id ID request driver
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        try {
            Log::info('Attempting to update RequestDriver with ID: ' . $id);
            // Validasi input
            $validated = $request->validate([
                'ekspedisi_id' => 'required|exists:ekspedisis,id',
                'nopol_kendaraan' => 'required|string|max:255',
                'nama_driver' => 'required|string|max:255',
                'no_hp_driver' => 'required|string|max:255',
                'nama_kernet' => 'nullable|string|max:255',
                'no_hp_kernet' => 'nullable|string|max:255',
                'keperluan' => 'required|string',
                'jam_in' => 'required',
                'jam_out' => 'required',
            ]);

            // Ambil data request driver
            $requestDriver = RequestDriver::find($id);

            // Cek apakah data request driver ada
            if (!$requestDriver) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Request Driver tidak ditemukan'
                ], 404);
            }

            // Update data
            $requestDriver->update($validated);

            // Buat notifikasi
            $users = \App\Models\User::whereHas('role', function($query) {
                $query->whereIn('slug', ['admin', 'checker', 'head-unit', 'security']);
            })->get();

            foreach($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title' => 'Update Data Permohonan Izin Driver',
                    'message' => 'Data permohonan izin driver ' . $requestDriver->nama_ekspedisi . 
                               ' dengan nopol ' . $requestDriver->nopol_kendaraan . 
                               ' telah diperbarui',
                    'type' => 'driver',
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
}

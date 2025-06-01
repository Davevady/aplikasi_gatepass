<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\RequestDriver;
use Illuminate\Http\Request;

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
            'requestDrivers', // Tetap menggunakan $requestDrivers
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest'
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
        return view('driver', compact('title'));
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
            'nama_ekspedisi' => 'required|string|max:255',
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
                    'title' => 'Permohonan Izin Keluar Driver ' . $validated['nama_ekspedisi'],
                    'message' => 'Permohonan izin driver ' . $validated['nama_ekspedisi'] . 
                               ' dengan nopol ' . $validated['nopol_kendaraan'] . 
                               ' sedang menunggu persetujuan',
                    'type' => 'driver',
                    'status' => 'pending',
                    'is_read' => false
                ]);
            }

            // Pesan sukses
            $successMessage = "Pengajuan izin driver berhasil dikirim.\n" .
                            "Nama Ekpedisi: " . $validated['nama_ekspedisi'] . "\n" .
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
}

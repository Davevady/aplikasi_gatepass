<?php

namespace App\Http\Controllers;

use App\Models\RequestKaryawan;
use App\Models\Departemen;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        
        // Pass data to the view
        return view('superadmin.request-karyawan.index', compact(
            'title', 
            'requestKaryawans', // Ubah dari $departemens menjadi $requestKaryawans
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest'
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
}

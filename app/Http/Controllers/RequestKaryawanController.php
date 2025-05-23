<?php

namespace App\Http\Controllers;

use App\Models\RequestKaryawan;
use App\Models\Departemen;
use App\Models\Notification;
use Illuminate\Http\Request;

class RequestKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Permohonan Izin Keluar Karyawan';
        $departemens = Departemen::with('requestKaryawans')->get();
        
        // Menghitung total request berdasarkan status dengan urutan persetujuan
        $totalMenunggu = RequestKaryawan::where(function($query) {
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
            
        $totalDisetujui = RequestKaryawan::where('acc_lead', 2) // Lead menyetujui
            ->where('acc_hr_ga', 2) // HR GA menyetujui
            ->where('acc_security_out', 2) // Security Out menyetujui
            ->where('acc_security_in', 2) // Security In menyetujui
            ->count();
            
        $totalDitolak = RequestKaryawan::where(function($query) {
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
            
        $totalRequest = RequestKaryawan::count();
        
        return view('superadmin.request-karyawan.index', compact(
            'title', 
            'departemens',
            'totalMenunggu',
            'totalDisetujui',
            'totalDitolak',
            'totalRequest'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departemens = Departemen::all();
        $title = 'Form Izin Keluar Karyawan';
        return view('karyawan', compact('departemens', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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

            $requestKaryawan = RequestKaryawan::create($validated);

            // Buat notifikasi untuk admin
            Notification::create([
                'user_id' => 1, // ID admin
                'title' => 'Permohonan Izin Keluar ' . $validated['nama'],
                'message' => 'Permohonan izin keluar atas nama ' . $validated['nama'] . 
                           ' dari departemen ' . Departemen::find($validated['departemen_id'])->name . 
                           ' untuk keperluan ' . $validated['keperluan'] . 
                           ' sedang menunggu persetujuan',
                'type' => 'karyawan',
                'status' => 'pending',
                'is_read' => false
            ]);

            $successMessage = "Pengajuan izin karyawan berhasil dikirim.\n" .
                            "Nama: " . $validated['nama'] . "\n" .
                            "Departemen: " . Departemen::find($validated['departemen_id'])->name . "\n" .
                            "Jam Keluar: " . $validated['jam_out'] . "\n" .
                            "Jam Kembali: " . $validated['jam_in'];

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
     * Display the specified resource.
     */
    public function show(RequestKaryawan $requestKaryawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestKaryawan $requestKaryawan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestKaryawan $requestKaryawan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestKaryawan $requestKaryawan)
    {
        //
    }
}

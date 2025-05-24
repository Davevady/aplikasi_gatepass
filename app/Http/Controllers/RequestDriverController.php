<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\RequestDriver;
use Illuminate\Http\Request;

class RequestDriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Permohonan Izin Keluar Driver';
        
        // Mengambil data yang masih memiliki status menunggu (value 1)
        $requestDrivers = RequestDriver::where(function($query) {
            $query->where('acc_admin', 1)
                ->orWhere('acc_head_unit', 1)
                ->orWhere('acc_security_out', 1)
                ->orWhere('acc_security_in', 1);
        })->get();
        
        // Menghitung total request berdasarkan status
        $totalMenunggu = RequestDriver::where(function($query) {
            $query->where('acc_admin', 1)
                ->orWhere('acc_head_unit', 1)
                ->orWhere('acc_security_out', 1)
                ->orWhere('acc_security_in', 1);
        })->count();
            
        $totalDisetujui = RequestDriver::where('acc_admin', 2)
            ->where('acc_head_unit', 2)
            ->where('acc_security_out', 2)
            ->where('acc_security_in', 2)
            ->count();
            
        $totalDitolak = RequestDriver::where(function($query) {
            $query->where('acc_admin', 3)
                ->orWhere('acc_head_unit', 3)
                ->orWhere('acc_security_out', 3)
                ->orWhere('acc_security_in', 3);
        })->count();
            
        $totalRequest = RequestDriver::count();
        
        return view('superadmin.request-driver.index', compact(
            'title', 
            'requestDrivers',
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
        $title = 'Form Izin Keluar Driver';
        return view('driver', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
            $requestDriver = RequestDriver::create($validated);

            // Buat notifikasi untuk admin
            Notification::create([
                'user_id' => 1, // ID admin
                'title' => 'Permohonan Izin Keluar Driver ' . $validated['nama_ekspedisi'],
                'message' => 'Permohonan izin driver ' . $validated['nama_ekspedisi'] . 
                           ' dengan nopol ' . $validated['nopol_kendaraan'] . 
                           ' sedang menunggu persetujuan',
                'type' => 'driver',
                'status' => 'pending',
                'is_read' => false
            ]);

            $successMessage = "Pengajuan izin driver berhasil dikirim.\n" .
                            "Nama Ekpedisi: " . $validated['nama_ekspedisi'] . "\n" .
                            "Nomor Polisi: " . $validated['nopol_kendaraan'] . "\n" .
                            "Nama Driver: " . $validated['nama_driver'] . "\n" .
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
     * Handle the approval of request
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
                    break;
                case 5: // Head Unit
                    $requestDriver->acc_head_unit = 2;
                    $notificationTitle = 'Disetujui Head Unit';
                    $notificationMessage = 'telah disetujui oleh Head Unit dan menunggu persetujuan Security Out';
                    break;
                case 6: // Security
                    if ($requestDriver->acc_security_out == 1) {
                        $requestDriver->acc_security_out = 2;
                        $notificationTitle = 'Disetujui Security Out';
                        $notificationMessage = 'telah disetujui oleh Security Out dan menunggu driver kembali';
                    } else {
                        $requestDriver->acc_security_in = 2;
                        $notificationTitle = 'Disetujui Security In';
                        $notificationMessage = 'telah disetujui oleh Security In dan permohonan selesai';
                    }
                    break;
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Role tidak valid'
                    ], 400);
            }

            $requestDriver->save();

            // Buat notifikasi
            Notification::create([
                'user_id' => 1, // ID admin
                'title' => 'Permohonan Izin Driver ' . $requestDriver->nama_ekspedisi . ' ' . $notificationTitle,
                'message' => 'Permohonan izin driver ' . $requestDriver->nama_ekspedisi . 
                           ' dengan nopol ' . $requestDriver->nopol_kendaraan . 
                           ' ' . $notificationMessage,
                'type' => 'driver',
                'status' => 'pending',
                'is_read' => false
            ]);

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
     * Display the specified resource.
     */
    public function show(RequestDriver $requestDriver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RequestDriver $requestDriver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RequestDriver $requestDriver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestDriver $requestDriver)
    {
        //
    }
}

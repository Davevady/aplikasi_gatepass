<?php

namespace App\Http\Controllers;

use App\Models\Ekspedisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EkspedisiController extends Controller
{
    /**
     * Menampilkan daftar semua ekspedisi
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Ekspedisi';
        $ekspedisis = Ekspedisi::all();
        return view('superadmin.ekspedisi.index', compact('ekspedisis', 'title'));
    }

    /**
     * Menyimpan ekspedisi baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_ekspedisi' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'pic' => 'required|string|max:255',
                'no_hp_pic' => 'required|string|max:20',
                'keterangan' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'add');
            }

            // Buat ekspedisi baru
            Ekspedisi::create([
                'nama_ekspedisi' => $request->nama_ekspedisi,
                'alamat' => $request->alamat,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'pic' => $request->pic,
                'no_hp_pic' => $request->no_hp_pic,
                'keterangan' => $request->keterangan,
                'status' => true
            ]);

            return redirect()->back()->with('success', 'Ekspedisi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput()
                ->with('modal', 'add');
        }
    }

    /**
     * Memperbarui data ekspedisi yang sudah ada
     * 
     * @param Request $request
     * @param int $id ID ekspedisi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Cari ekspedisi berdasarkan ID
            $ekspedisi = Ekspedisi::findOrFail($id);

            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_ekspedisi' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_telp' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'pic' => 'required|string|max:255',
                'no_hp_pic' => 'required|string|max:20',
                'keterangan' => 'nullable|string'
            ], [
                'nama_ekspedisi.required' => 'Nama ekspedisi harus diisi',
                'alamat.required' => 'Alamat harus diisi',
                'no_telp.required' => 'Nomor telepon harus diisi',
                'email.email' => 'Format email tidak valid',
                'pic.required' => 'PIC harus diisi',
                'no_hp_pic.required' => 'Nomor HP PIC harus diisi'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = '';
                
                foreach ($errors->all() as $error) {
                    $errorMessage .= $error . ' ';
                }

                return redirect()->back()
                    ->with('error', trim($errorMessage))
                    ->withInput()
                    ->with('modal', 'edit');
            }

            // Update data ekspedisi
            $ekspedisi->nama_ekspedisi = $request->nama_ekspedisi;
            $ekspedisi->alamat = $request->alamat;
            $ekspedisi->no_telp = $request->no_telp;
            $ekspedisi->email = $request->email;
            $ekspedisi->pic = $request->pic;
            $ekspedisi->no_hp_pic = $request->no_hp_pic;
            $ekspedisi->keterangan = $request->keterangan;
            $ekspedisi->save();

            return redirect()->route('ekspedisi.index')->with('success', 'Ekspedisi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui ekspedisi')
                ->withInput()
                ->with('modal', 'edit');
        }
    }

    /**
     * Menghapus ekspedisi dari database
     * 
     * @param int $id ID ekspedisi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Cari ekspedisi berdasarkan ID
            $ekspedisi = Ekspedisi::findOrFail($id);

            // Log sebelum menghapus
            Log::info('Menghapus ekspedisi', [
                'id' => $ekspedisi->id,
                'nama_ekspedisi' => $ekspedisi->nama_ekspedisi
            ]);

            // Hapus ekspedisi
            $ekspedisi->delete();

            // Log setelah menghapus
            Log::info('Ekspedisi berhasil dihapus', [
                'id' => $ekspedisi->id
            ]);

            return redirect()->back()->with('success', 'Ekspedisi berhasil dihapus');
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal menghapus ekspedisi', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus ekspedisi: ' . $e->getMessage());
        }
    }
}

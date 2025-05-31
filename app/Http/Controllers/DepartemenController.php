<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DepartemenController extends Controller
{
    /**
     * Menampilkan daftar semua departemen
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Departemen';
        $departemens = Departemen::all();
        return view('superadmin.departemen.index', compact('departemens', 'title'));
    }

    /**
     * Menyimpan departemen baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:departemens',
                'description' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'add');
            }

            // Buat departemen baru
            Departemen::create([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Departemen berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput()
                ->with('modal', 'add');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Departemen $departemen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Departemen $departemen)
    {
        //
    }

    /**
     * Memperbarui data departemen yang sudah ada
     * 
     * @param Request $request
     * @param int $id ID departemen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Cari departemen berdasarkan ID
            $departemen = Departemen::findOrFail($id);

            // Validasi input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:10|unique:departemens,code,' . $id,
                'description' => 'nullable|string'
            ], [
                'name.required' => 'Nama departemen harus diisi',
                'name.max' => 'Nama departemen maksimal 255 karakter',
                'code.required' => 'Kode departemen harus diisi', 
                'code.max' => 'Kode departemen maksimal 10 karakter',
                'code.unique' => 'Kode departemen sudah digunakan'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $errorMessage = '';
                
                if ($errors->has('name')) {
                    $errorMessage .= $errors->first('name') . ' ';
                }
                if ($errors->has('code')) {
                    $errorMessage .= $errors->first('code') . ' ';
                }
                if ($errors->has('description')) {
                    $errorMessage .= $errors->first('description') . ' ';
                }

                return redirect()->back()
                    ->with('error', trim($errorMessage));
            }

            // Update data departemen
            $departemen->name = $request->name;
            $departemen->code = strtoupper($request->code);
            $departemen->description = $request->description;
            $departemen->save();

            return redirect()->route('departemen.index')->with('success', 'Departemen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui departemen');
        }
    }

    /**
     * Menghapus departemen dari database
     * 
     * @param int $id ID departemen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            // Cari departemen berdasarkan ID
            $departemen = Departemen::findOrFail($id);

            // Cek apakah departemen masih digunakan oleh user
            if ($departemen->users()->count() > 0) {
                return redirect()->back()->with('error', 'Departemen tidak dapat dihapus karena masih digunakan oleh pengguna');
            }

            // Cek apakah departemen masih digunakan dalam request karyawan
            if ($departemen->requestKaryawans()->count() > 0) {
                return redirect()->back()->with('error', 'Departemen tidak dapat dihapus karena masih digunakan dalam request karyawan');
            }

            // Log sebelum menghapus
            Log::info('Menghapus departemen', [
                'id' => $departemen->id,
                'name' => $departemen->name,
                'code' => $departemen->code
            ]);

            // Hapus departemen
            $departemen->delete();

            // Log setelah menghapus
            Log::info('Departemen berhasil dihapus', [
                'id' => $departemen->id
            ]);

            return redirect()->back()->with('success', 'Departemen berhasil dihapus');
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal menghapus departemen', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus departemen: ' . $e->getMessage());
        }
    }
}

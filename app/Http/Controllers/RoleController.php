<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar semua role yang tersedia
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Role';
        $roles = Role::all();
        return view('superadmin.role.index', compact('roles', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Menyimpan role baru ke database
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:roles',
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'add');
            }

            // Buat role baru dengan slug otomatis
            $role = Role::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Role berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput()
                ->with('modal', 'add');
        }
    }

    /**
     * Menampilkan detail role dalam format JSON
     * 
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return response()->json($role);
    }

    /**
     * Memperbarui data role yang sudah ada
     * 
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255|unique:roles,title,' . $role->id,
                'description' => 'required|string'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update data role
            $role->update([
                'title' => $request->title,
                'description' => $request->description
            ]);

            return redirect()->back()->with('success', 'Role berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menghapus role dari database
     * 
     * @param Role $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        try {
            // Cek apakah role masih digunakan oleh user
            if ($role->users()->count() > 0) {
                return redirect()->back()->with('error', 'Role tidak dapat dihapus karena masih digunakan oleh pengguna');
            }

            // Hapus role
            $role->delete();

            return redirect()->back()->with('success', 'Role berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}

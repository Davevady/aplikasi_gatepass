<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function login()
    {
        $title = 'Masuk';
        return view('auth.login', compact('title'));
    }

    public function authLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        // Get user data first to check status
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ])->onlyInput('email');
    }

    public function register()
    {
        $title = 'Daftar';
        return view('auth.register', compact('title'));
    }

    public function authRegister(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // Cek email yang sudah terdaftar
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return redirect()->back()->withErrors(['email' => 'Email sudah terdaftar'])->withInput();
            }

            // Buat user baru
            $user = User::create([
                'title' => $request->title,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => 1
            ]);

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            Log::error('Error saat registrasi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout!');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Data Pengguna';
        $users = User::all();
        return view('superadmin.users.index', compact('title', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'departemen_id' => 'required|exists:departemens,id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'role_id' => 'required|exists:roles,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // dd($request);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validasi gagal',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->with('failed', $validator->errors())->withInput();
            }

            // Cek email yang sudah terdaftar
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Email sudah terdaftar',
                        'errors' => ['email' => ['Email ini sudah digunakan']]
                    ], 422);
                }
                return redirect()->back()->with('failed', 'Email sudah terdaftar')->withInput();
            }

            DB::beginTransaction();
            try {
                // Buat user baru
                $user = new User();
                $user->departemen_id = $request->departemen_id;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make('password');
                $user->role_id = $request->role_id;
                $user->is_active = 1;

                // Upload Photo
                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $photoName = time() . '.' . $photo->extension();
                    $photo->move(public_path('images/users'), $photoName);
                    $user->photo = 'images/users/' . $photoName;
                }

                $user->save();

                DB::commit();

                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Data berhasil disimpan',
                        'data' => $user,
                        'metadata' => [
                            'status_code' => 201,
                            'created_at' => now()->toDateTimeString(),
                        ]
                    ], 201);
                }
                return redirect()->back()->with('success', 'Data berhasil disimpan');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error saving user:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Gagal menyimpan data',
                        'error' => $e->getMessage()
                    ], 500);
                }
                return redirect()->back()->with('failed', 'Gagal menyimpan data' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('User store error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('failed', 'Terjadi kesalahan saat menyimpan data' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'role_id' => 'required|exists:roles,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = User::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'role_id' => $request->role_id,
                'updated_by' => auth()->id()
            ];

            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($user->photo && file_exists(public_path($user->photo))) {
                    unlink(public_path($user->photo));
                }
                
                $photo = $request->file('photo');
                $photoName = time() . '.' . $photo->extension();
                $photo->move(public_path('images/users'), $photoName);
                $data['photo'] = 'images/users/' . $photoName;
            }

            $user->update($data);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data user berhasil diupdate'
                ]);
            }

            return redirect()->back()->with('success', 'Data user berhasil diupdate');
        } catch (\Exception $e) {
            Log::error('Error updating user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengupdate data: ' . $e->getMessage());
        }
    }

    public function toggleActive(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Update status aktif berdasarkan input
            $user->is_active = $request->input('is_active');
            $user->save();

            return redirect()->back()->with('success', 'Status user berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error toggling user status:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengubah status user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->exists) {
            $user->delete();
            return redirect()->back()->with('success', 'Pengguna berhasil dihapus');
        }

        return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
    }

    public function restore($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->restore();
            return redirect()->back()->with('success', 'Pengguna berhasil dikembalikan');
        }

        return redirect()->back()->with('error', 'Pengguna tidak ditemukan');
    }
}

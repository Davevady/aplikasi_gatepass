<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
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

        // Check if user is active
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
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
        $users = User::with(['departemen', 'role'])->get();
        $departemens = Departemen::all();
        $roles = Role::all();
        return view('superadmin.users.index', compact('title', 'users', 'departemens', 'roles'));
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
            ], [
                'departemen_id.required' => 'Departemen harus dipilih',
                'departemen_id.exists' => 'Departemen tidak valid',
                'name.required' => 'Nama harus diisi',
                'name.max' => 'Nama maksimal 255 karakter',
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan',
                'role_id.required' => 'Role harus dipilih',
                'role_id.exists' => 'Role tidak valid',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
                'photo.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'add');
            }

            DB::beginTransaction();
            try {
                // Buat user baru
                $user = new User();
                $user->departemen_id = $request->departemen_id;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make('password'); // Default password
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
                return redirect()->back()->with('success', 'Pengguna berhasil ditambahkan');
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Error saving user:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->back()
                    ->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                    ->withInput()
                    ->with('modal', 'add');
            }
        } catch (\Exception $e) {
            Log::error('User store error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data')
                ->withInput()
                ->with('modal', 'add');
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
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role_id' => 'required|exists:roles,id',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'name.required' => 'Nama harus diisi',
                'name.max' => 'Nama maksimal 255 karakter',
                'role_id.required' => 'Role harus dipilih',
                'role_id.exists' => 'Role tidak valid',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
                'photo.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'edit')
                    ->with('edit_id', $id);
            }

            $user = User::findOrFail($id);
            
            $data = [
                'name' => $request->name,
                'role_id' => $request->role_id
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

            return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput()
                ->with('modal', 'edit')
                ->with('edit_id', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // Hapus foto jika ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            $user->delete();

            return redirect()->back()->with('success', 'Pengguna berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error deleting user:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }

    public function toggleActive(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Update status aktif
            $user->is_active = !$user->is_active; // Toggle status
            $user->save();

            // Log perubahan status
            Log::info('User status changed', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'new_status' => $user->is_active ? 'active' : 'inactive'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status pengguna berhasil diubah',
                'is_active' => $user->is_active
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling user status:', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status pengguna: ' . $e->getMessage()
            ], 500);
        }
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

    /**
     * Update user's email
     */
    public function updateEmail(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:users,email,' . $id
            ], [
                'email.required' => 'Email harus diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'email')
                    ->with('edit_id', $id);
            }

            $user = User::findOrFail($id);
            $user->email = $request->email;
            $user->save();

            return redirect()->back()->with('success', 'Email pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating user email:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengupdate email: ' . $e->getMessage())
                ->withInput()
                ->with('modal', 'email')
                ->with('edit_id', $id);
        }
    }

    /**
     * Reset user's password
     */
    public function resetPassword(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:8|confirmed'
            ], [
                'password.required' => 'Password harus diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak sesuai'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'password')
                    ->with('edit_id', $id);
            }

            $user = User::findOrFail($id);
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password pengguna berhasil direset');
        } catch (\Exception $e) {
            Log::error('Error resetting user password:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mereset password: ' . $e->getMessage())
                ->withInput()
                ->with('modal', 'password')
                ->with('edit_id', $id);
        }
    }

    /**
     * Update user's photo
     */
    public function updatePhoto(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ], [
                'photo.required' => 'Foto harus diupload',
                'photo.image' => 'File harus berupa gambar',
                'photo.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
                'photo.max' => 'Ukuran gambar maksimal 2MB'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'photo')
                    ->with('edit_id', $id);
            }

            $user = User::findOrFail($id);

            // Hapus foto lama jika ada
            if ($user->photo && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }

            // Upload foto baru
            $photo = $request->file('photo');
            $photoName = time() . '.' . $photo->extension();
            $photo->move(public_path('images/users'), $photoName);
            
            $user->photo = 'images/users/' . $photoName;
            $user->save();

            return redirect()->back()->with('success', 'Foto profil berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating user photo:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengupdate foto: ' . $e->getMessage())
                ->withInput()
                ->with('modal', 'photo')
                ->with('edit_id', $id);
        }
    }

    /**
     * Update user's basic info (name, role, departemen)
     */
    public function updateBasicInfo(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'role_id' => 'required|exists:roles,id',
                'departemen_id' => 'required|exists:departemens,id'
            ], [
                'name.required' => 'Nama harus diisi',
                'name.max' => 'Nama maksimal 255 karakter',
                'role_id.required' => 'Role harus dipilih',
                'role_id.exists' => 'Role tidak valid',
                'departemen_id.required' => 'Departemen harus dipilih',
                'departemen_id.exists' => 'Departemen tidak valid'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('modal', 'edit')
                    ->with('edit_id', $id);
            }

            $user = User::findOrFail($id);
            $user->name = $request->name;
            $user->role_id = $request->role_id;
            $user->departemen_id = $request->departemen_id;
            $user->save();

            return redirect()->back()->with('success', 'Data pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating user basic info:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput()
                ->with('modal', 'edit')
                ->with('edit_id', $id);
        }
    }

    /**
     * Reset user's password to default
     */
    public function resetPasswordToDefault($id)
    {
        try {
            $user = User::findOrFail($id);
            
            // Reset password ke default
            $user->password = Hash::make('password');
            $user->save();

            // Log reset password
            Log::info('User password reset to default', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset ke default'
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetting password to default:', [
                'user_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }
}

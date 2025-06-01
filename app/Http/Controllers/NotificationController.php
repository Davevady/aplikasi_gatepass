<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Menampilkan daftar notifikasi untuk user yang sedang login
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $title = 'Data Notifikasi';
        $notifications = Notification::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('superadmin.notifications.index', compact('notifications', 'title'));
    }

    /**
     * Menampilkan detail notifikasi dan mengubah status is_read menjadi true
     * 
     * @param int $id ID notifikasi
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function showAndRead($id)
    {
        // Cari notifikasi berdasarkan ID dan user_id
        $notification = Notification::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        // Update status is_read jika belum dibaca
        if (!$notification->is_read) {
            $notification->is_read = true;
            $notification->save();
        }

        // Jika request AJAX, return JSON
        if (request()->ajax()) {
            return response()->json([
                'title' => $notification->title,
                'message' => $notification->message,
                'created_at' => $notification->created_at->format('d-m-Y H:i'),
            ]);
        }

        // Jika bukan AJAX, tampilkan view detail
        return view('notifications.show', compact('notification'));
    }

    /**
     * Update notifikasi yang dipilih
     * 
     * @param Request $request
     * @param int $id ID notifikasi
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi request
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        // Cari notifikasi berdasarkan ID
        $notification = Notification::findOrFail($id);

        // Cek apakah user adalah admin
        if (auth()->user()->role->title !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mengedit notifikasi');
        }

        // Update notifikasi
        $notification->update([
            'title' => $request->title,
            'message' => $request->message
        ]);

        return redirect()->route('notifications.index')
            ->with('success', 'Notifikasi berhasil diperbarui');
    }
}

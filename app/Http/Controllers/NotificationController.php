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
        $notifications = Notification::where('user_id', auth()->user()->id)->get();
        return view('notifications.index', compact('notifications'));
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
}

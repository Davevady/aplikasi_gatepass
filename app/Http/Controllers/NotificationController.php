<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->user()->id)->get();
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        //
    }

    /**
     * Menampilkan detail notifikasi dan set is_read menjadi true
     */
    public function showAndRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->where('id', $id)->firstOrFail();
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
        // Jika bukan AJAX, bisa redirect atau tampilkan view detail
        return view('notifications.show', compact('notification'));
    }
}

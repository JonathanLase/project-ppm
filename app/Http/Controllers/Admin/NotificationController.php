<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'semua');

        $query = Notification::where('user_id', Auth::id())->latest();

        if ($filter !== 'semua') {
            $query->where('type', $filter);
        }

        $notifications = $query->get();

        return view('Admin.notifikasi', compact('notifications', 'filter'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return back();
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->whereNull('read_at')->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}

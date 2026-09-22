<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index()
    {
        // Mengambil data log terbaru beserta data user yang melakukannya
        $logs = ActivityLog::with('user')->latest()->paginate(10);

        return view('Admin.activity_log', compact('logs'));
    }
}

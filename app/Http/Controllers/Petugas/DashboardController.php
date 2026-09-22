<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanHasil;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Petugas PPM';

        $stats = (object) [
            'total_pengajuan'    => Pengajuan::count(),
            'menunggu_validasi'  => Pengajuan::whereIn('status', ['proses', 'menunggu'])->count(),
            'disetujui'          => Pengajuan::where('status', 'disetujui')->count(),
            'revisi_ditolak'     => Pengajuan::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count(),
        ];

        $pengajuanTerbaru = Pengajuan::with(['pegawai', 'skema'])
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('Petugas.dashboard', compact('title', 'stats', 'pengajuanTerbaru'));
    }
}

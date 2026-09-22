<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Skema;
use App\Models\LaporanHasil;
use App\Models\LaporanKemajuan;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard Admin';

        // 1. Hitung revisi dari masing-masing entitas untuk kartu statistik
        $revisiProposal = Pengajuan::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count();

        $revisiKemajuan = class_exists(LaporanKemajuan::class)
            ? LaporanKemajuan::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count()
            : 0;

        $revisiHasil = LaporanHasil::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count();

        $stats = (object) [
            'total_pengajuan' => Pengajuan::count(),
            'menunggu_validasi' => Pengajuan::whereIn('status', ['proses', 'menunggu'])->count(),
            'disetujui' => Pengajuan::where('status', 'disetujui')->count(),
            'revisi_ditolak' => $revisiProposal + $revisiKemajuan + $revisiHasil,
            'laporan_hasil_masuk' => LaporanHasil::count(),
        ];

        $jumlahPenelitian = Pengajuan::where('jenis', 'penelitian')->count();
        $jumlahPengabdian = Pengajuan::where('jenis', 'pengabdian')->count();
        $totalJenis = $jumlahPenelitian + $jumlahPengabdian;

        $persenPenelitian = $totalJenis > 0 ? round(($jumlahPenelitian / $totalJenis) * 100) : 0;
        $persenPengabdian = $totalJenis > 0 ? round(($jumlahPengabdian / $totalJenis) * 100) : 0;

        $skemaData = Skema::withCount('pengajuan')->get();
        $perSkemaLabels = $skemaData->pluck('nama')->toArray();
        $perSkemaData = $skemaData->pluck('pengajuan_count')->toArray();

        $perTahunLabels = ['2022', '2023', '2024', '2025', '2026'];
        $perTahunData = [];
        foreach ($perTahunLabels as $tahun) {
            $perTahunData[] = Pengajuan::whereYear('created_at', $tahun)->count();
        }

        // 2. AMBIL DATA TERBARU DARI KETIGA TABEL
        // Ambil Proposal Terbaru
        $proposalTerbaru = Pengajuan::with(['pegawai', 'skema'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function($item) {
                $item->tipe_label = 'Proposal';
                $item->nama_judul = $item->judul ?? '-';
                $item->nama_pegawai = $item->pegawai->nama ?? '-';
                $item->nama_skema = $item->skema->nama ?? '-';
                return $item;
            });

        // Ambil Laporan Kemajuan Terbaru (jika modelnya ada)
        $kemajuanTerbaru = collect();
        if (class_exists(LaporanKemajuan::class)) {
            $kemajuanTerbaru = LaporanKemajuan::with(['pengajuan.pegawai', 'pengajuan.skema'])
                ->latest('updated_at')
                ->take(5)
                ->get()
                ->map(function($item) {
                    $item->tipe_label = 'Laporan Kemajuan';
                    $item->nama_judul = $item->pengajuan->judul ?? 'Laporan Kemajuan #'.$item->id;
                    $item->nama_pegawai = $item->pengajuan->pegawai->nama ?? '-';
                    $item->nama_skema = $item->pengajuan->skema->nama ?? '-';
                    return $item;
                });
        }

        // Ambil Laporan Hasil Terbaru
        $hasilTerbaru = LaporanHasil::with(['pengajuan.pegawai', 'pengajuan.skema'])
            ->latest('updated_at')
            ->take(5)
            ->get()
            ->map(function($item) {
                $item->tipe_label = 'Laporan Hasil';
                // Menyesuaikan jika kolom judul ada di tabel laporan_hasil atau melalui relasi pengajuan
                $item->nama_judul = $item->judul ?? ($item->pengajuan->judul ?? 'Laporan Hasil #'.$item->id);
                $item->nama_pegawai = $item->pengajuan->pegawai->nama ?? ($item->pegawai->nama ?? '-');
                $item->nama_skema = $item->pengajuan->skema->nama ?? '-';
                return $item;
            });

        // Gabungkan semuanya, urutkan berdasarkan updated_at paling akhir, dan ambil 5 teratas
        $pengajuanTerbaru = $proposalTerbaru->concat($kemajuanTerbaru)->concat($hasilTerbaru)
            ->sortByDesc('updated_at')
            ->take(5);

        return view('Admin.dashboard', compact(
            'title',
            'stats',
            'persenPenelitian',
            'persenPengabdian',
            'perSkemaLabels',
            'perSkemaData',
            'perTahunLabels',
            'perTahunData',
            'pengajuanTerbaru'
        ));
    }
}

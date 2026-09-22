<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanHasil;

class PengajuanController extends Controller
{
    public function semua(Request $request)
    {
        $title = 'Semua Pengajuan';

        $totalSemua = Pengajuan::count() + LaporanKemajuan::count() + LaporanHasil::count();

        $totalProses = Pengajuan::whereIn('status', ['proses', 'menunggu'])->count()
                     + LaporanKemajuan::whereIn('status', ['proses', 'menunggu'])->count()
                     + LaporanHasil::whereIn('status', ['proses', 'menunggu'])->count();

        $totalDisetujui = Pengajuan::where('status', 'disetujui')->count()
                        + LaporanKemajuan::where('status', 'disetujui')->count()
                        + LaporanHasil::where('status', 'disetujui')->count();

        $revisiProposal = Pengajuan::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count();
        $revisiKemajuan = class_exists(LaporanKemajuan::class) ? LaporanKemajuan::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count() : 0;
        $revisiHasil = LaporanHasil::whereIn('status', ['revisi', 'ditolak', 'perbaikan', 'perlu_revisi'])->count();

        $totalRevisi = $revisiProposal + $revisiKemajuan + $revisiHasil;

        $search = $request->input('search');
        $jenis = $request->input('jenis');
        $jalur = $request->input('jalur');
        $status = $request->input('status');
        if ($status == 'menunggu') {
            $status = 'proses';
        }
        if ($request->filled('status_filter')) {
            $status = $request->input('status_filter');
        }
        $tahun = $request->input('tahun');

        $proposalQuery = Pengajuan::with(['pegawai', 'skema', 'rumpunIlmu'])
            ->when($search, fn($q) => $q->where(fn($sub) => $sub->where('judul', 'like', "%{$search}%")->orWhere('kode', 'like', "%{$search}%")))
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->when($jalur, fn($q) => $q->where('jalur', $jalur))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->get()
            ->map(function($item) {
                $item->tipe_dokumen = 'Proposal';
                $item->kode_dokumen = $item->kode ?? '-';
                $item->judul_dokumen = $item->judul ?? '-';
                $item->nama_pengusul = $item->pegawai->nama ?? '-';
                $item->jenis_dokumen = $item->jenis ?? '-';
                $item->jalur_dokumen = $item->jalur ?? '-';
                return $item;
            });

        $kemajuanQuery = collect();
        if (class_exists(LaporanKemajuan::class)) {
            $kemajuanQuery = LaporanKemajuan::with(['pengajuan.pegawai', 'pengajuan.skema', 'pengajuan.rumpunIlmu'])
                ->when($search, fn($q) => $q->whereHas('pengajuan', fn($sub) => $sub->where('judul', 'like', "%{$search}%")))
                ->when($jenis, fn($q) => $q->whereHas('pengajuan', fn($sub) => $sub->where('jenis', $jenis)))
                ->when($jalur, fn($q) => $q->whereHas('pengajuan', fn($sub) => $sub->where('jalur', $jalur)))
                ->when($status, fn($q) => $q->where('status', $status))
                ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
                ->get()
                ->map(function($item) {
                    $item->tipe_dokumen = 'Laporan Kemajuan';
                    $item->kode_dokumen = 'LK-' . $item->id;
                    $item->judul_dokumen = $item->pengajuan->judul ?? 'Laporan Kemajuan #'.$item->id;
                    $item->nama_pengusul = $item->pengajuan->pegawai->nama ?? '-';
                    $item->jenis_dokumen = $item->pengajuan->jenis ?? 'penelitian';
                    $item->jalur_dokumen = $item->pengajuan->jalur ?? '-';
                    return $item;
                });
        }

        $hasilQuery = LaporanHasil::with(['pengajuan.pegawai', 'pengajuan.skema', 'pengajuan.rumpunIlmu'])
            ->when($search, fn($q) => $q->where('judul', 'like', "%{$search}%")->orWhereHas('pengajuan', fn($sub) => $sub->where('judul', 'like', "%{$search}%")))
            ->when($jenis, fn($q) => $q->whereHas('pengajuan', fn($sub) => $sub->where('jenis', $jenis)))
            ->when($jalur, fn($q) => $q->whereHas('pengajuan', fn($sub) => $sub->where('jalur', $jalur)))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
            ->get()
            ->map(function($item) {
                $item->tipe_dokumen = 'Laporan Hasil';
                $item->kode_dokumen = 'LH-' . $item->id;
                $item->judul_dokumen = $item->judul ?? ($item->pengajuan->judul ?? 'Laporan Hasil #'.$item->id);
                $item->nama_pengusul = $item->pengajuan->pegawai->nama ?? ($item->pegawai->nama ?? '-');
                $item->jenis_dokumen = $item->pengajuan->jenis ?? 'penelitian';
                $item->jalur_dokumen = $item->pengajuan->jalur ?? '-';
                return $item;
            });

        $semuaData = $proposalQuery->concat($kemajuanQuery)->concat($hasilQuery)
            ->sortByDesc('updated_at');

        $perPage = 10;
        $page = request()->input('page', 1);
        $pengajuans = new \Illuminate\Pagination\LengthAwarePaginator(
            $semuaData->forPage($page, $perPage)->values(),
            $semuaData->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('Admin.pengajuan.semua', compact(
            'title',
            'pengajuans',
            'totalSemua',
            'totalProses',
            'totalDisetujui',
            'totalRevisi'
        ));
    }

    public function penelitian(Request $request)
    {
        $request->merge(['jenis' => 'penelitian']);
        return $this->semua($request);
    }

    public function pengabdian(Request $request)
    {
        $request->merge(['jenis' => 'pengabdian']);
        return $this->semua($request);
    }

    public function showDokumen($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if (!$pengajuan->proposal_path) {
            abort(404, 'Dokumen proposal tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $pengajuan->proposal_path);

        abort_unless(file_exists($filePath), 404, 'File proposal tidak ditemukan di server.');

        return response()->file($filePath);
    }

    public function downloadDokumen($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);

        if (!$pengajuan->proposal_path) {
            abort(404, 'Dokumen proposal tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $pengajuan->proposal_path);

        abort_unless(file_exists($filePath), 404, 'File proposal tidak ditemukan di server.');

        $namaFile = $pengajuan->proposal_nama_asli ?? basename($filePath);

        return response()->download($filePath, $namaFile);
    }

    public function showLaporanKemajuan($id)
    {
        $laporan = LaporanKemajuan::findOrFail($id);

        if (!$laporan->file_path) {
            abort(404, 'Dokumen laporan kemajuan tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_path);
        abort_unless(file_exists($filePath), 404, 'File laporan kemajuan tidak ditemukan di server.');

        return response()->file($filePath);
    }

    public function downloadLaporanKemajuan($id)
    {
        $laporan = LaporanKemajuan::findOrFail($id);

        if (!$laporan->file_path) {
            abort(404, 'Dokumen laporan kemajuan tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_path);
        abort_unless(file_exists($filePath), 404, 'File laporan kemajuan tidak ditemukan di server.');

        $namaFile = $laporan->file_nama_asli ?? basename($filePath);

        return response()->download($filePath, $namaFile);
    }

    public function showLaporanHasil($id)
    {
        $laporan = LaporanHasil::findOrFail($id);

        if (!$laporan->file_path) {
            abort(404, 'Dokumen laporan hasil tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_path);
        abort_unless(file_exists($filePath), 404, 'File laporan hasil tidak ditemukan di server.');

        return response()->file($filePath);
    }

    public function downloadLaporanHasil($id)
    {
        $laporan = LaporanHasil::findOrFail($id);

        if (!$laporan->file_path) {
            abort(404, 'Dokumen laporan hasil tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $laporan->file_path);
        abort_unless(file_exists($filePath), 404, 'File laporan hasil tidak ditemukan di server.');

        $namaFile = $laporan->file_nama_asli ?? basename($filePath);

        return response()->download($filePath, $namaFile);
    }
}

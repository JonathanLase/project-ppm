<?php

namespace App\Http\Controllers;

use App\Models\ArsipSk;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArsipSkController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipSk::with('skema');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('jalur')) {
            $query->where('jalur', $request->jalur);
        }

        if ($request->filled('skema_id')) {
            $query->where('skema_id', $request->skema_id);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $query->where(function ($q) use ($search) {
                $q->where('no_sk', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        $arsipSks = $query->orderBy('tahun', 'desc')->latest()->paginate(10)->withQueryString();

        // Daftar tahun untuk filter
        $availableYears = ArsipSk::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $currentYear = (int) date('Y');
        $defaultYears = range($currentYear, $currentYear - 4);
        $years = array_values(array_unique(array_merge($availableYears, $defaultYears)));
        rsort($years);

        // Daftar skema aktif untuk dropdown dinamis
        $skemas = Skema::where('aktif', true)
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'jenis', 'jalur']);

        return view('arsip-sk.index', [
            'arsipSks' => $arsipSks,
            'years' => $years,
            'skemas' => $skemas,
            'filterTahun' => $request->tahun,
            'filterJenis' => $request->jenis,
            'filterJalur' => $request->jalur,
            'filterSkema' => $request->skema_id,
            'q' => $request->q,
        ]);
    }

    public function preview($id)
    {
        $arsip = ArsipSk::findOrFail($id);

        if (!Storage::disk('public')->exists($arsip->file_path)) {
            abort(404, 'File SK tidak ditemukan di server.');
        }

        $filePath = Storage::disk('public')->path($arsip->file_path);

        return response()->file($filePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . ($arsip->file_nama_asli ?? 'SK.pdf') . '"',
        ]);
    }

    public function download($id): BinaryFileResponse
    {
        $arsip = ArsipSk::findOrFail($id);

        if (!Storage::disk('public')->exists($arsip->file_path)) {
            abort(404, 'File SK tidak ditemukan di server.');
        }

        $fileName = $arsip->file_nama_asli ?: ('SK_' . str_replace(['/', '\\', ' '], '_', $arsip->no_sk) . '.pdf');

        return Storage::disk('public')->download($arsip->file_path, $fileName);
    }
}

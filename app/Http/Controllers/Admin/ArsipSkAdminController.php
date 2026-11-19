<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArsipSk;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ArsipSkAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ArsipSk::with(['skema', 'uploader']);

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

        $availableYears = ArsipSk::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        $currentYear = (int) date('Y');
        $defaultYears = range($currentYear, $currentYear - 4);
        $years = array_values(array_unique(array_merge($availableYears, $defaultYears)));
        rsort($years);

        $skemas = Skema::where('aktif', true)
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'jenis', 'jalur']);

        return view('Admin.arsip-sk.index', [
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_sk' => 'required|string|max:100',
            'tahun' => 'required|digits:4|integer|min:2000|max:' . ((int)date('Y') + 2),
            'jenis' => 'required|in:penelitian,pengabdian',
            'jalur' => 'required|in:simlitabkes,mandiri',
            'skema_id' => 'nullable|exists:skema,id',
            'file' => 'required|file|mimes:pdf|max:5120',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'no_sk.required' => 'Nomor SK wajib diisi.',
            'tahun.required' => 'Tahun SK wajib diisi.',
            'jenis.required' => 'Jenis kegiatan wajib dipilih.',
            'jalur.required' => 'Jalur pengajuan wajib dipilih.',
            'file.required' => 'File dokumen SK wajib diunggah.',
            'file.mimes' => 'File dokumen SK harus berformat PDF.',
            'file.max' => 'Ukuran file dokumen SK maksimal 5 MB.',
        ]);

        $file = $request->file('file');
        $path = $file->store('arsip-sk', 'public');

        ArsipSk::create([
            'no_sk' => $validated['no_sk'],
            'tahun' => $validated['tahun'],
            'jenis' => $validated['jenis'],
            'jalur' => $validated['jalur'],
            'skema_id' => $validated['skema_id'] ?? null,
            'file_path' => $path,
            'file_nama_asli' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'keterangan' => $validated['keterangan'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('admin.arsip-sk.index')->with('success', 'Arsip SK baru berhasil diunggah.');
    }

    public function update(Request $request, $id)
    {
        $arsip = ArsipSk::findOrFail($id);

        $validated = $request->validate([
            'no_sk' => 'required|string|max:100',
            'tahun' => 'required|digits:4|integer|min:2000|max:' . ((int)date('Y') + 2),
            'jenis' => 'required|in:penelitian,pengabdian',
            'jalur' => 'required|in:simlitabkes,mandiri',
            'skema_id' => 'nullable|exists:skema,id',
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'keterangan' => 'nullable|string|max:1000',
        ], [
            'no_sk.required' => 'Nomor SK wajib diisi.',
            'tahun.required' => 'Tahun SK wajib diisi.',
            'jenis.required' => 'Jenis kegiatan wajib dipilih.',
            'jalur.required' => 'Jalur pengajuan wajib dipilih.',
            'file.mimes' => 'File dokumen SK harus berformat PDF.',
            'file.max' => 'Ukuran file dokumen SK maksimal 5 MB.',
        ]);

        $dataUpdate = [
            'no_sk' => $validated['no_sk'],
            'tahun' => $validated['tahun'],
            'jenis' => $validated['jenis'],
            'jalur' => $validated['jalur'],
            'skema_id' => $validated['skema_id'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ];

        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
                Storage::disk('public')->delete($arsip->file_path);
            }

            $file = $request->file('file');
            $dataUpdate['file_path'] = $file->store('arsip-sk', 'public');
            $dataUpdate['file_nama_asli'] = $file->getClientOriginalName();
            $dataUpdate['file_size'] = $file->getSize();
        }

        $arsip->update($dataUpdate);

        return redirect()->route('admin.arsip-sk.index')->with('success', 'Data Arsip SK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $arsip = ArsipSk::findOrFail($id);

        if ($arsip->file_path && Storage::disk('public')->exists($arsip->file_path)) {
            Storage::disk('public')->delete($arsip->file_path);
        }

        $arsip->delete();

        return redirect()->route('admin.arsip-sk.index')->with('success', 'Arsip SK berhasil dihapus.');
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

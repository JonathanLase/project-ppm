<?php

namespace App\Http\Controllers;

use App\Models\LuaranMaster;
use App\Models\Notification;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Models\PengajuanLuaran;
use App\Models\PengajuanTim;
use App\Models\RumpunIlmu;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PengajuanController extends Controller
{
    const SESSION_KEY = 'wizard_pengajuan';

    /* ===================== DAFTAR PENGAJUAN (TABEL) ===================== */

    public function daftar()
    {
        $daftarPengajuan = Pengajuan::with('skema')
            ->where('pegawai_id', Auth::id())
            ->latest()
            ->get();

        return view('pengajuan.daftar', [
            'daftarPengajuan' => $daftarPengajuan,
        ]);
    }

    private function wizard(): array
    {
        $default = [
            'jenis' => null,
            'jalur' => null,
            'skema_id' => null,
            'rumpun_ilmu_id' => null,
            'judul' => null,
            'tahun_anggaran' => date('Y'),
            'tahun_pengajuan' => date('Y'),
            'tahun_pelaksanaan' => 'I',
            'tahun_capaian' => date('Y'),
            'anggota' => [],
            'proposal_path' => null,
            'proposal_nama_asli' => null,
            'proposal_size' => null,
            'kontrak_path' => null,
            'kontrak_nama_asli' => null,
            'kontrak_size' => null,
            'rab_path' => null,
            'rab_nama_asli' => null,
            'rab_size' => null,
            'total_biaya' => null,
            'luaran_wajib' => [],
            'luaran_tambahan' => [],
            'inovasi_produk' => null,
        ];

        return array_merge($default, session(self::SESSION_KEY, []));
    }

    private function saveWizard(array $data): void
    {
        session([self::SESSION_KEY => array_merge($this->wizard(), $data)]);
    }

    private function resetWizard(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    private function requireStep(int $minStep): ?\Illuminate\Http\RedirectResponse
    {
        $w = $this->wizard();
        if ($minStep >= 2 && (!$w['jenis'] || !$w['jalur'] || !$w['skema_id'] || !$w['judul'])) {
            return redirect()->route('pengajuan.step1')->with('error', 'Lengkapi Jalur & Skema terlebih dahulu.');
        }
        if ($minStep >= 4 && !$w['proposal_path']) {
            return redirect()->route('pengajuan.step3')->with('error', 'Unggah dokumen proposal terlebih dahulu.');
        }
        return null;
    }

    /* ===================== STEP 1: Jalur & Skema ===================== */

    public function step1()
    {
        $this->resetWizardIfFresh();

        $skemaGrouped = [];
        foreach (Skema::where('aktif', true)->orderBy('nama')->get() as $s) {
            $skemaGrouped[$s->jenis . '|simlitabkes'][] = ['id' => $s->id, 'nama' => $s->nama];
            $skemaGrouped[$s->jenis . '|mandiri'][] = ['id' => $s->id, 'nama' => $s->nama];
        }

        return view('pengajuan.step1', [
            'w' => $this->wizard(),
            'rumpunIlmu' => RumpunIlmu::orderBy('nama')->get(),
            'skemaGrouped' => $skemaGrouped,
        ]);
    }

    private function resetWizardIfFresh()
    {
        if (!session()->has(self::SESSION_KEY)) {
            session([self::SESSION_KEY => $this->wizard()]);
        }
    }

    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'jenis' => 'required|in:penelitian,pengabdian',
            'jalur' => 'required|in:simlitabkes,mandiri',
            'skema_id' => 'required|exists:skema,id',
            'rumpun_ilmu_id' => 'nullable|exists:rumpun_ilmu,id',
            'judul' => 'required|string|max:255',
            'tahun_anggaran' => 'required|digits:4',
            'tahun_pengajuan' => 'required|digits:4',
            'tahun_pelaksanaan' => 'required|in:I,II,III',
            'tahun_capaian' => 'required|digits:4',
        ]);

        $this->saveWizard($data);

        return redirect()->route('pengajuan.step2');
    }

    /* ===================== STEP 2: Tim & Unit Kerja ===================== */

    public function step2()
    {
        if ($redirect = $this->requireStep(2)) {
            return $redirect;
        }

        $ketua = Auth::user();
        $anggotaTersedia = Pegawai::where('role', 'dosen')->where('id', '!=', $ketua->id)->orderBy('nama')->get();

        $pegawaiListJson = $anggotaTersedia->map(function ($p) {
            return [
                'id' => $p->id,
                'nama' => $p->nama,
                'nip' => $p->nidn ?? $p->nip,
                'jurusan' => $p->jurusan,
            ];
        })->values();

        $w = $this->wizard();

        // Buang dari session anggota manapun yang ID-nya sama dengan ketua
        // (misalnya sisa dari sesi wizard lama sebelum perbaikan ini berlaku).
        $w['anggota'] = collect($w['anggota'])
            ->reject(fn ($a) => !empty($a['pegawai_id']) && (int) $a['pegawai_id'] === (int) $ketua->id)
            ->values()
            ->all();
        $this->saveWizard(['anggota' => $w['anggota']]);

        $initialAnggotaJson = collect($w['anggota'])->map(function ($a) {
            return [
                'pegawai_id' => $a['pegawai_id'] ?? '',
                'nama_external' => $a['nama_external'] ?? '',
                'institusi_external' => $a['institusi_external'] ?? '',
            ];
        })->values();

        return view('pengajuan.step2', [
            'w' => $w,
            'ketua' => $ketua,
            'anggotaTersedia' => $anggotaTersedia,
            'pegawaiListJson' => $pegawaiListJson,
            'initialAnggotaJson' => $initialAnggotaJson,
        ]);
    }

    public function postStep2(Request $request)
    {
        $ketua = Auth::user();

        $data = $request->validate([
            'anggota' => 'nullable|array',
            'anggota.*.pegawai_id' => [
                'nullable',
                'exists:pegawais,id',
                function ($attribute, $value, $fail) use ($ketua) {
                    if ($value && (int) $value === (int) $ketua->id) {
                        $fail('Ketua pengaju tidak bisa dipilih sebagai anggota.');
                    }
                },
            ],
            'anggota.*.nama_external' => 'nullable|string|max:255',
            'anggota.*.institusi_external' => 'nullable|string|max:255',
        ]);

        $anggota = [];
        foreach ($data['anggota'] ?? [] as $a) {
            if (!empty($a['pegawai_id'])) {
                $anggota[] = ['pegawai_id' => $a['pegawai_id'], 'nama_external' => null, 'institusi_external' => null];
            } elseif (!empty($a['nama_external'])) {
                $anggota[] = [
                    'pegawai_id' => null,
                    'nama_external' => trim($a['nama_external']),
                    'institusi_external' => trim($a['institusi_external'] ?? ''),
                ];
            }
        }

        $this->saveWizard(['anggota' => $anggota]);

        return redirect()->route('pengajuan.step3');
    }

    /* ===================== STEP 3: Proposal & Biaya ===================== */

    public function step3()
    {
        if ($redirect = $this->requireStep(2)) {
            return $redirect;
        }

        return view('pengajuan.step3', ['w' => $this->wizard()]);
    }

    public function postStep3(Request $request)
    {
        $rules = ['total_biaya' => 'required|numeric|min:0'];

        $w = $this->wizard();

        // Dokumen proposal wajib diisi jika belum pernah diunggah sebelumnya.
        // Dokumen kontrak dan RAB bersifat opsional.
        $rules['proposal'] = $w['proposal_path'] ? 'nullable|file|mimes:pdf|max:2048' : 'required|file|mimes:pdf|max:2048';
        $rules['kontrak'] = 'nullable|file|mimes:pdf|max:2048';
        $rules['rab'] = 'nullable|file|mimes:pdf|max:2048';

        $data = $request->validate($rules);

        $update = ['total_biaya' => $data['total_biaya']];

        // Daftar dokumen yang diproses seragam: [field request, prefix kolom, folder storage]
        $dokumenList = [
            'proposal' => ['proposal', 'proposal'],
            'kontrak' => ['kontrak', 'kontrak'],
            'rab' => ['rab', 'rab'],
        ];

        foreach ($dokumenList as $fieldName => [$kolomPrefix, $folder]) {
            if ($request->hasFile($fieldName)) {
                $pathLama = $w["{$kolomPrefix}_path"] ?? null;
                if ($pathLama) {
                    Storage::disk('public')->delete($pathLama);
                }
                $file = $request->file($fieldName);
                $path = $file->store($folder, 'public');
                $update["{$kolomPrefix}_path"] = $path;
                $update["{$kolomPrefix}_nama_asli"] = $file->getClientOriginalName();
                $update["{$kolomPrefix}_size"] = $file->getSize();
            }
        }

        $this->saveWizard($update);

        return redirect()->route('pengajuan.step4');
    }

    /* ===================== STEP 4: Rencana Luaran ===================== */

    public function step4()
    {
        if ($redirect = $this->requireStep(4)) {
            return $redirect;
        }

        $w = $this->wizard();
        $luarans = LuaranMaster::where('jenis', $w['jenis'])->orderBy('id')->get();

        return view('pengajuan.step4', [
            'w' => $w,
            'luarans' => $luarans,
        ]);
    }

    public function postStep4(Request $request)
    {
        $data = $request->validate([
            'luaran_wajib' => 'required|array|min:1',
            'luaran_wajib.*' => 'exists:luaran_masters,id',
            'luaran_wajib_opsi' => 'nullable|array',
            'luaran_tambahan' => 'nullable|array',
            'luaran_tambahan.*' => 'exists:luaran_masters,id',
            'luaran_tambahan_opsi' => 'nullable|array',
            'inovasi_produk' => 'nullable|string',
        ], [
            'luaran_wajib.required' => 'Pilih minimal 1 luaran wajib.',
            'luaran_wajib.min' => 'Pilih minimal 1 luaran wajib.',
        ]);

        $wajib = [];
        foreach ($data['luaran_wajib'] as $id) {
            $wajib[$id] = $data['luaran_wajib_opsi'][$id] ?? null;
        }

        $tambahan = [];
        foreach ($data['luaran_tambahan'] ?? [] as $id) {
            $tambahan[$id] = $data['luaran_tambahan_opsi'][$id] ?? null;
        }

        $this->saveWizard([
            'luaran_wajib' => $wajib,
            'luaran_tambahan' => $tambahan,
            'inovasi_produk' => $data['inovasi_produk'] ?? null,
        ]);

        return redirect()->route('pengajuan.step5');
    }

    /* ===================== STEP 5: Review & Kirim ===================== */

    public function step5()
    {
        if ($redirect = $this->requireStep(4)) {
            return $redirect;
        }

        $w = $this->wizard();

        $pegawaiIds = collect($w['anggota'])->pluck('pegawai_id')->filter()->values();
        $anggotaLuar = collect($w['anggota'])
            ->filter(fn($a) => empty($a['pegawai_id']))
            ->map(fn($a) => ['nama' => $a['nama_external'], 'instansi' => $a['institusi_external']])
            ->values()
            ->all();

        return view('pengajuan.step5', [
            'w' => $w,
            'ketua' => Auth::user(),
            'skema' => Skema::find($w['skema_id']),
            'rumpunIlmu' => $w['rumpun_ilmu_id'] ? RumpunIlmu::find($w['rumpun_ilmu_id']) : null,
            'anggotaTim' => Pegawai::whereIn('id', $pegawaiIds)->get(),
            'anggotaLuar' => $anggotaLuar,
            'luaranWajibDipilih' => LuaranMaster::whereIn('id', array_keys($w['luaran_wajib']))->get(),
            'luaranTambahanDipilih' => LuaranMaster::whereIn('id', array_keys($w['luaran_tambahan']))->get(),
        ]);
    }

    public function submit(Request $request)
    {
        $w = $this->wizard();

        if (!$w['jenis'] || !$w['skema_id'] || !$w['proposal_path']) {
            return redirect()->route('pengajuan.step1')->with('error', 'Data pengajuan belum lengkap. Dokumen proposal wajib diunggah.');
        }

        $pengajuan = DB::transaction(function () use ($w) {
            $ketua = Auth::user();

            $pengajuan = Pengajuan::create([
                'kode' => 'TEMP',
                'pegawai_id' => $ketua->id,
                'jenis' => $w['jenis'],
                'jalur' => $w['jalur'],
                'skema_id' => $w['skema_id'],
                'rumpun_ilmu_id' => $w['rumpun_ilmu_id'],
                'judul' => $w['judul'],
                'tahun_anggaran' => $w['tahun_anggaran'],
                'tahun_pengajuan' => $w['tahun_pengajuan'],
                'tahun_pelaksanaan' => $w['tahun_pelaksanaan'],
                'tahun_capaian' => $w['tahun_capaian'],
                'proposal_path' => $w['proposal_path'],
                'proposal_nama_asli' => $w['proposal_nama_asli'],
                'proposal_size' => $w['proposal_size'],
                'kontrak_path' => $w['kontrak_path'],
                'kontrak_nama_asli' => $w['kontrak_nama_asli'],
                'kontrak_size' => $w['kontrak_size'],
                'rab_path' => $w['rab_path'],
                'rab_nama_asli' => $w['rab_nama_asli'],
                'rab_size' => $w['rab_size'],
                'total_biaya' => $w['total_biaya'],
                'inovasi_produk' => $w['inovasi_produk'],
                'tahap' => 'proposal',
                'status' => 'proses',
            ]);

            $prefix = $w['jenis'] === 'penelitian' ? 'PNL' : (Skema::find($w['skema_id'])->kode ?? 'PKM');
            $pengajuan->update(['kode' => sprintf('%s-%s-%05d', $prefix, $w['tahun_pengajuan'], $pengajuan->id)]);

            PengajuanTim::create(['pengajuan_id' => $pengajuan->id, 'pegawai_id' => $ketua->id, 'peran' => 'ketua']);

            foreach ($w['anggota'] as $a) {
                // Pengaman terakhir (defense-in-depth): meski postStep2() dan
                // step2() sudah mencegah ketua terpilih sebagai anggota, baris
                // ini memastikan proses submit final tidak akan pernah
                // menyimpan ketua dua kali walau datanya lolos dari validasi
                // sebelumnya (mis. session lama, request manual, dsb).
                if (!empty($a['pegawai_id']) && (int) $a['pegawai_id'] === (int) $ketua->id) {
                    continue;
                }

                if (!empty($a['pegawai_id'])) {
                    PengajuanTim::create([
                        'pengajuan_id' => $pengajuan->id,
                        'pegawai_id' => $a['pegawai_id'],
                        'peran' => 'anggota',
                    ]);
                } elseif (!empty($a['nama_external'])) {
                    PengajuanTim::create([
                        'pengajuan_id' => $pengajuan->id,
                        'pegawai_id' => null,
                        'nama_luar' => $a['nama_external'],
                        'instansi_luar' => $a['institusi_external'] ?: null,
                        'peran' => 'anggota',
                    ]);
                }
            }

            foreach ($w['luaran_wajib'] as $luaranMasterId => $opsi) {
                PengajuanLuaran::create([
                    'pengajuan_id' => $pengajuan->id,
                    'luaran_master_id' => $luaranMasterId,
                    'opsi_dipilih' => $opsi ?: null,
                    'is_wajib' => true,
                ]);
            }

            foreach ($w['luaran_tambahan'] as $luaranMasterId => $opsi) {
                PengajuanLuaran::create([
                    'pengajuan_id' => $pengajuan->id,
                    'luaran_master_id' => $luaranMasterId,
                    'opsi_dipilih' => $opsi ?: null,
                    'is_wajib' => false,
                ]);
            }

            return $pengajuan;
        });

        // Kirim notifikasi ke semua admin — dibungkus try-catch supaya kalau
        // gagal karena sebab apapun, proses submit proposal tetap sukses dan
        // tidak terganggu. Errornya (kalau ada) tercatat di storage/logs/laravel.log.
        try {
            $adminIds = Pegawai::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'pengajuan',
                    'title' => 'Proposal Baru Masuk',
                    'message' => 'Proposal "' . $pengajuan->judul . '" dari ' . Auth::user()->nama . ' menunggu validasi.',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim notifikasi admin (pengajuan baru): ' . $e->getMessage());
        }

        $this->resetWizard();

        return redirect()->route('pengajuan.sukses');
    }

    public function sukses()
    {
        $pengajuan = Pengajuan::where('pegawai_id', Auth::id())->latest()->first();

        abort_unless($pengajuan, 404);

        return view('pengajuan.sukses', [
            'judulHalaman' => 'Pengajuan Baru',
            'pengajuan' => $pengajuan,
            'kembaliUrl' => route('pengajuan.daftar'),
            'kembaliLabel' => 'Kembali ke Daftar Proposal',
        ]);
    }

    public function batal()
    {
        $w = $this->wizard();
        foreach (['proposal_path', 'kontrak_path', 'rab_path'] as $pathKey) {
            if ($w[$pathKey]) {
                Storage::disk('public')->delete($w[$pathKey]);
            }
        }
        $this->resetWizard();

        return redirect()->route('dashboard');
    }

    /* ===================== EDIT PENGAJUAN (status proses atau revisi) ===================== */

    public function edit(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if (!in_array($pengajuan->status, ['proses', 'revisi'])) {
            return redirect()->route('pengajuan.detail', $pengajuan)->with('error', 'Pengajuan ini tidak dapat diedit karena sudah disetujui.');
        }

        $pengajuan->load('tim');
        $anggotaTersedia = Pegawai::where('role', 'dosen')->where('id', '!=', $user->id)->orderBy('nama')->get();

        $timTerpilih = $pengajuan->tim->where('peran', 'anggota')->whereNotNull('pegawai_id')->pluck('pegawai_id')->toArray();
        $timLuarExisting = $pengajuan->tim->where('peran', 'anggota')->whereNull('pegawai_id')
            ->map(fn ($t) => ['nama' => $t->nama_luar, 'instansi' => $t->instansi_luar])->values()->toArray();

        return view('pengajuan.edit', [
            'pengajuan' => $pengajuan,
            'ketua' => $pengajuan->pegawai,
            'anggotaTersedia' => $anggotaTersedia,
            'timTerpilih' => $timTerpilih,
            'timLuarExisting' => $timLuarExisting,
        ]);
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if (!in_array($pengajuan->status, ['proses', 'revisi'])) {
            return redirect()->route('pengajuan.detail', $pengajuan)->with('error', 'Pengajuan ini tidak dapat diedit karena sudah disetujui.');
        }

        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'total_biaya' => 'required|numeric|min:0',
            'proposal' => 'nullable|file|mimes:pdf|max:2048',
            'kontrak' => 'nullable|file|mimes:pdf|max:2048',
            'rab' => 'nullable|file|mimes:pdf|max:2048',
            'tim' => 'nullable|array',
            'tim.*' => [
                'exists:pegawais,id',
                function ($attribute, $value, $fail) use ($user) {
                    if ((int) $value === (int) $user->id) {
                        $fail('Ketua pengaju tidak bisa dipilih sebagai anggota.');
                    }
                },
            ],
            'tim_luar_nama' => 'nullable|array',
            'tim_luar_nama.*' => 'nullable|string|max:255',
            'tim_luar_instansi' => 'nullable|array',
            'tim_luar_instansi.*' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $data, $pengajuan) {
            $update = [
                'judul' => $data['judul'],
                'total_biaya' => $data['total_biaya'],
                'status' => 'proses',
            ];

            if ($request->hasFile('proposal')) {
                if ($pengajuan->proposal_path && Storage::disk('public')->exists($pengajuan->proposal_path)) {
                    Storage::disk('public')->delete($pengajuan->proposal_path);
                }
                $file = $request->file('proposal');
                $update['proposal_path'] = $file->store('proposal', 'public');
                $update['proposal_nama_asli'] = $file->getClientOriginalName();
                $update['proposal_size'] = $file->getSize();
            }

            if ($request->hasFile('kontrak')) {
                if ($pengajuan->kontrak_path && Storage::disk('public')->exists($pengajuan->kontrak_path)) {
                    Storage::disk('public')->delete($pengajuan->kontrak_path);
                }
                $file = $request->file('kontrak');
                $update['kontrak_path'] = $file->store('kontrak', 'public');
                $update['kontrak_nama_asli'] = $file->getClientOriginalName();
                $update['kontrak_size'] = $file->getSize();
            }

            if ($request->hasFile('rab')) {
                if ($pengajuan->rab_path && Storage::disk('public')->exists($pengajuan->rab_path)) {
                    Storage::disk('public')->delete($pengajuan->rab_path);
                }
                $file = $request->file('rab');
                $update['rab_path'] = $file->store('rab', 'public');
                $update['rab_nama_asli'] = $file->getClientOriginalName();
                $update['rab_size'] = $file->getSize();
            }

            $pengajuan->update($update);

            $pengajuan->tim()->where('peran', 'anggota')->delete();

            foreach ($data['tim'] ?? [] as $pegawaiId) {
                PengajuanTim::create(['pengajuan_id' => $pengajuan->id, 'pegawai_id' => $pegawaiId, 'peran' => 'anggota']);
            }

            foreach ($data['tim_luar_nama'] ?? [] as $i => $nama) {
                if (trim((string) $nama) !== '') {
                    PengajuanTim::create([
                        'pengajuan_id' => $pengajuan->id,
                        'pegawai_id' => null,
                        'nama_luar' => trim($nama),
                        'instansi_luar' => trim($data['tim_luar_instansi'][$i] ?? '') ?: null,
                        'peran' => 'anggota',
                    ]);
                }
            }
        });

        return redirect()->route('pengajuan.detail', $pengajuan)->with('success', 'Data pengajuan dan berkas berhasil diperbarui.');
    }
}
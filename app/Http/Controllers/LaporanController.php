<?php

namespace App\Http\Controllers;

use App\Models\LaporanHasil;
use App\Models\LaporanKemajuan;
use App\Models\LuaranMaster;
use App\Models\Notification;
use App\Models\Pegawai;
use App\Models\Pengajuan;
use App\Rules\GoogleDriveLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /* ===================== LAPORAN KEMAJUAN ===================== */
    /* Tidak ada input link di Laporan Kemajuan (hanya upload PDF + checkbox
     * luaran_tercapai), jadi tidak perlu validasi GoogleDriveLink di sini. */

    public function kemajuan()
    {
        $user = Auth::user();

        $daftarKegiatan = Pengajuan::with('skema')
            ->where('pegawai_id', $user->id)
            ->where('jalur', 'simlitabkes')
            ->whereIn('tahap', ['laporan_kemajuan', 'laporan_hasil'])
            ->latest()
            ->get();

        if ($daftarKegiatan->isEmpty()) {
            return view('laporan.kemajuan', ['tanpaKegiatan' => true]);
        }

        $laporanByPengajuan = LaporanKemajuan::whereIn('pengajuan_id', $daftarKegiatan->pluck('id'))
            ->get()
            ->keyBy('pengajuan_id');

        return view('laporan.kemajuan', [
            'daftarKegiatan' => $daftarKegiatan,
            'laporanByPengajuan' => $laporanByPengajuan,
        ]);
    }

    public function kemajuanForm(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->jalur === 'mandiri') {
            return redirect()->route('laporan.kemajuan')
                ->with('error', 'Kegiatan jalur Mandiri tidak memerlukan Laporan Kemajuan.');
        }

        $pengajuan->load('luaran.luaranMaster');
        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && in_array($laporan->status, ['disetujui', 'proses'])) {
            return redirect()->route('laporan.kemajuan.detail', $pengajuan);
        }

        return view('laporan.kemajuan-form', [
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
        ]);
    }

    public function kemajuanDetail(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $pengajuan->load('luaran.luaranMaster');
        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->firstOrFail();

        return view('laporan.kemajuan-detail', [
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
        ]);
    }

    public function kemajuanStore(Request $request, Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
            'kegiatan_dilakukan' => 'nullable|string',
            'kendala' => 'nullable|string',
            'rencana_berikutnya' => 'nullable|string',
            'luaran_tercapai' => 'nullable|array',
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'dokumentasi.*' => 'nullable|image|max:5120',
        ];
        if ($isKirim) {
            $rules['file'] = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->whereNotNull('file_path')->exists()
                ? 'nullable|file|mimes:pdf|max:5120'
                : 'required|file|mimes:pdf|max:5120';
        }

        $data = $request->validate($rules);

        $totalLuaran = $pengajuan->luaran()->count();
        $luaranTercapai = $data['luaran_tercapai'] ?? [];
        $persentaseOtomatis = $totalLuaran > 0 ? (int) round(count($luaranTercapai) / $totalLuaran * 100) : 0;

        $laporan = LaporanKemajuan::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->persentase = $persentaseOtomatis;
        $laporan->kegiatan_dilakukan = $data['kegiatan_dilakukan'] ?? null;
        $laporan->kendala = $data['kendala'] ?? null;
        $laporan->rencana_berikutnya = $data['rencana_berikutnya'] ?? null;
        $laporan->luaran_tercapai = $luaranTercapai;
        $laporan->status = $isKirim ? 'proses' : 'draft';
        $laporan->catatan_validator = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $laporan->file_path = $file->store('laporan-kemajuan', 'public');
            $laporan->file_nama_asli = $file->getClientOriginalName();
            $laporan->file_size = $file->getSize();
        }

        if ($request->hasFile('dokumentasi')) {
            $dok = $laporan->dokumentasi ?? [];
            foreach ($request->file('dokumentasi') as $f) {
                $path = $f->store('laporan-kemajuan/dokumentasi', 'public');
                $dok[] = ['path' => $path, 'nama' => $f->getClientOriginalName()];
            }
            $laporan->dokumentasi = $dok;
        }

        $laporan->save();

        if ($isKirim) {
            // Kirim notifikasi ke semua admin bahwa ada laporan kemajuan baru masuk
            $adminIds = Pegawai::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'laporan_kemajuan',
                    'title' => 'Laporan Kemajuan Baru Masuk',
                    'message' => 'Laporan kemajuan untuk "' . $pengajuan->judul . '" dari ' . Auth::user()->nama . ' menunggu validasi.',
                ]);
            }

            return redirect()->route('laporan.kemajuan.sukses', $pengajuan);
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Draft berhasil disimpan.');
    }

    public function kemajuanSukses(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        return view('laporan.sukses', [
            'judulHalaman' => 'Laporan Kemajuan',
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'kembaliUrl' => route('laporan.kemajuan'),
            'kembaliLabel' => 'Kembali ke Daftar Laporan Kemajuan',
        ]);
    }

    public function kemajuanHapusFile(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && $laporan->file_path) {
            Storage::disk('public')->delete($laporan->file_path);
            $laporan->file_path = null;
            $laporan->file_nama_asli = null;
            $laporan->file_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Dokumen kemajuan berhasil dihapus.');
    }

    public function kemajuanHapusDokumentasi(Pengajuan $pengajuan, int $index)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanKemajuan::where('pengajuan_id', $pengajuan->id)->first();

        if ($laporan && !empty($laporan->dokumentasi[$index])) {
            Storage::disk('public')->delete($laporan->dokumentasi[$index]['path']);
            $dok = $laporan->dokumentasi;
            unset($dok[$index]);
            $laporan->dokumentasi = array_values($dok);
            $laporan->save();
        }

        return redirect()->route('laporan.kemajuan.form', $pengajuan)
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

    /* ===================== LAPORAN HASIL ===================== */

    public function index(string $tipe)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();

        $daftarKegiatan = Pengajuan::with('skema')
            ->where('pegawai_id', $user->id)
            ->where('tahap', 'laporan_hasil')
            ->latest()
            ->get();

        if ($daftarKegiatan->isEmpty()) {
            return view('laporan.hasil', ['tanpaKegiatan' => true]);
        }

        $laporanByPengajuan = LaporanHasil::whereIn('pengajuan_id', $daftarKegiatan->pluck('id'))
            ->get()
            ->keyBy('pengajuan_id');

        return view('laporan.hasil', [
            'daftarKegiatan' => $daftarKegiatan,
            'laporanByPengajuan' => $laporanByPengajuan,
        ]);
    }

    public function form(string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        if ($pengajuan->tahap !== 'laporan_hasil') {
            return redirect()->route('laporan.index', $tipe)->with('error', 'Pengajuan ini belum berada di tahap Laporan Hasil.');
        }

        $pengajuan->load('luaran.luaranMaster', 'laporanKemajuan');
        $laporan = $pengajuan->laporanHasil;
        $readonly = $laporan && in_array($laporan->status, ['proses', 'disetujui']);

        // Status "tercapai" untuk tiap luaran di Laporan Hasil mengikuti dua sumber:
        //   (a) untuk jalur Simlitabkes: apa yang sudah dicentang dosen di Laporan
        //       Kemajuan (luaran_tercapai di sana berupa array flat id luaran), DAN
        //   (b) luaran yang link bukti-nya sudah pernah diisi & tersimpan sebelumnya
        //       di Laporan Hasil ini sendiri (draft/reload) — supaya auto-ceklis dari
        //       pengisian link tetap konsisten setelah halaman dibuka ulang, bukan
        //       cuma efek visual sesaat di browser (lihat updateKemajuanLuaran() di
        //       laporan/form.blade.php untuk bagian real-time di sisi client).
        // Untuk jalur Mandiri, tidak ada tahap Laporan Kemajuan sama sekali,
        // jadi semua luaran yang direncanakan dianggap tercapai otomatis.
        if ($pengajuan->jalur === 'mandiri') {
            $luaranTercapaiIds = $pengajuan->luaran->pluck('id')->all();
        } else {
            $dariKemajuan = $pengajuan->laporanKemajuan->luaran_tercapai ?? [];

            $dariLinkTerisi = collect(optional($laporan)->luaran_tercapai ?? [])
                ->filter(fn ($item) => trim($item['link'] ?? '') !== '')
                ->keys()
                ->map(fn ($id) => (int) $id)
                ->all();

            $luaranTercapaiIds = array_values(array_unique(array_merge($dariKemajuan, $dariLinkTerisi)));
        }

        // Daftar luaran master di luar yang sudah dipilih saat pengajuan proposal —
        // dipakai untuk fitur "Luaran Lainnya" supaya dosen bisa menambahkan bukti
        // luaran yang tercapai di lapangan meski tidak direncanakan di awal.
        $luaranMasterDipilihIds = $pengajuan->luaran->pluck('luaran_master_id');
        $luaranMasterLain = LuaranMaster::where('jenis', $pengajuan->jenis)
            ->whereNotIn('id', $luaranMasterDipilihIds)
            ->orderBy('nama')
            ->get();

        return view('laporan.form', [
            'tipe' => $tipe,
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'luaranList' => $pengajuan->luaran,
            'luaranTercapaiIds' => $luaranTercapaiIds,
            'luaranMasterLain' => $luaranMasterLain,
            'readonly' => $readonly,
            'judulHalaman' => 'Laporan Hasil',
        ]);
    }

    /**
     * Simpan Laporan Hasil. Ringkasan Hasil & Dokumentasi Kegiatan opsional.
     * Dokumen laporan & kwitansi wajib diisi; bukti pajak & berita acara opsional.
     * Link Inovasi Produk dan No. SK wajib diisi — tapi hanya divalidasi wajib
     * saat action=kirim (draft boleh belum lengkap).
     *
     * Semua field link (link bukti luaran, link luaran tambahan, dan link
     * inovasi/produk) divalidasi harus berupa link Google Drive/Docs yang
     * valid (lihat App\Rules\GoogleDriveLink) supaya dosen tidak asal input
     * link sembarangan.
     *
     * Status "tercapai" untuk tiap luaran TIDAK lagi otomatis semua tercentang.
     * Untuk jalur Simlitabkes, hanya luaran yang sudah dicentang di Laporan
     * Kemajuan yang wajib diisi link-nya di sini; yang belum tercapai di
     * Kemajuan boleh dikosongkan. Untuk jalur Mandiri (tidak ada tahap
     * Kemajuan), semua luaran yang direncanakan tetap wajib diisi link-nya.
     *
     * Untuk "Luaran Lainnya", dosen bisa pilih judul dari $luaranMasterLain ATAU
     * pilih opsi "lainnya" di dropdown lalu ketik judul manual sendiri (nama_custom).
     * Baris dengan luaran_master_id = "lainnya" TIDAK divalidasi lewat rule
     * exists:luaran_masters,id (karena bukan ID asli), melainkan lewat rule
     * required_if di bawah yang mewajibkan nama_custom diisi.
     */
    public function store(Request $request, string $tipe, Pengajuan $pengajuan)
    {
        abort_unless($tipe === 'hasil', 404);

        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $existing = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();
        abort_if(
            $existing && in_array($existing->status, ['proses', 'disetujui']),
            403,
            'Laporan sudah dikirim dan tidak bisa diubah. Hubungi admin untuk membuka akses.'
        );

        $isKirim = $request->input('action') === 'kirim';

        $rules = [
            'ringkasan_hasil' => 'nullable|string',
            'luaran' => 'nullable|array',
            'luaran.*.link' => ['nullable', 'string', 'max:500', new GoogleDriveLink],
            'luaran_lain' => 'nullable|array',
            // "lainnya" adalah kode khusus untuk input manual, jadi TIDAK boleh
            // divalidasi exists:luaran_masters,id (bukan ID asli tabel itu).
            'luaran_lain.*.luaran_master_id' => ['nullable', 'string', function ($attribute, $value, $fail) {
                if (empty($value) || $value === 'lainnya') {
                    return;
                }
                if (!LuaranMaster::where('id', $value)->exists()) {
                    $fail('Luaran yang dipilih tidak valid.');
                }
            }],
            // nama_custom wajib diisi HANYA kalau luaran_master_id di baris yang sama = "lainnya".
            'luaran_lain.*.nama_custom' => 'nullable|required_if:luaran_lain.*.luaran_master_id,lainnya|string|max:255',
            'luaran_lain.*.link' => ['nullable', 'string', 'max:500', new GoogleDriveLink],
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'bukti_pajak' => 'nullable|file|mimes:pdf|max:5120',
            'berita_acara' => 'nullable|file|mimes:pdf|max:5120',
            'dokumentasi.*' => 'nullable|image|max:5120',
        ];

        if ($isKirim) {
            $rules['file'] = ($existing && $existing->file_path)
                ? 'nullable|file|mimes:pdf|max:5120'
                : 'required|file|mimes:pdf|max:5120';
            $rules['kwitansi'] = ($existing && $existing->kwitansi_path)
                ? 'nullable|file|mimes:pdf|max:5120'
                : 'required|file|mimes:pdf|max:5120';
            $rules['link_inovasi_produk'] = ['required', 'string', 'max:255', new GoogleDriveLink];
            $rules['no_sk'] = 'required|string|max:255';
        } else {
            $rules['kwitansi'] = 'nullable|file|mimes:pdf|max:5120';
            $rules['link_inovasi_produk'] = ['nullable', 'string', 'max:255', new GoogleDriveLink];
            $rules['no_sk'] = 'nullable|string|max:255';
        }

        $data = $request->validate($rules, [
            'luaran_lain.*.nama_custom.required_if' => 'Judul luaran manual wajib diisi untuk baris "Lainnya".',
        ]);

        // Luaran yang WAJIB diisi link-nya adalah luaran yang berstatus tercapai —
        // untuk Simlitabkes ikut apa yang dicentang di Laporan Kemajuan, untuk
        // Mandiri semua luaran yang direncanakan (tidak ada tahap Kemajuan).
        $pengajuan->loadMissing('luaran.luaranMaster', 'laporanKemajuan');
        if ($pengajuan->jalur === 'mandiri') {
            $luaranWajibDiisiIds = $pengajuan->luaran->pluck('id')->all();
        } else {
            $luaranWajibDiisiIds = $pengajuan->laporanKemajuan->luaran_tercapai ?? [];
        }

        // Wajib isi link bukti hanya untuk luaran yang berstatus tercapai di atas
        // sebelum laporan bisa dikirim — divalidasi manual karena nama field-nya
        // dinamis per ID luaran (luaran[123][link], dst). Validasi format Google
        // Drive untuk link-link ini sudah ditangani oleh rule 'luaran.*.link' di atas;
        // di sini hanya cek kosong/tidaknya.
        if ($isKirim) {
            foreach ($pengajuan->luaran as $pl) {
                if (!in_array($pl->id, $luaranWajibDiisiIds)) {
                    continue; // belum tercapai di Laporan Kemajuan — tidak wajib
                }
                $link = trim($request->input("luaran.{$pl->id}.link", ''));
                if ($link === '') {
                    return back()
                        ->withErrors([
                            'luaran' => 'Tautan bukti untuk luaran "' . ($pl->luaranMaster->nama ?? 'yang dipilih')
                                . '" wajib diisi sebelum laporan dikirim.',
                        ])
                        ->withInput();
                }
            }
        }

        // Simpan entri untuk setiap luaran yang direncanakan — link boleh kosong
        // untuk luaran yang belum tercapai di Laporan Kemajuan (tidak wajib diisi).
        $luaranTercapai = [];
        foreach ($pengajuan->luaran as $pl) {
            $link = trim($request->input("luaran.{$pl->id}.link", ''));
            $luaranTercapai[$pl->id] = ['link' => $link];
        }

        // Luaran tambahan di luar rencana awal proposal — hanya baris yang
        // link-nya diisi yang disimpan. Ada 2 bentuk baris yang valid:
        // 1) Pilih dari daftar $luaranMasterLain -> simpan luaran_master_id (int)
        // 2) Pilih "Lainnya" lalu ketik manual  -> simpan nama_custom (string),
        //    luaran_master_id disimpan null karena memang bukan ID asli.
        $luaranTambahanLain = [];
        foreach ($request->input('luaran_lain', []) as $item) {
            $masterId = $item['luaran_master_id'] ?? null;
            $namaCustom = trim($item['nama_custom'] ?? '');
            $link = trim($item['link'] ?? '');

            if ($link === '') {
                continue; // baris kosong / belum diisi, lewati
            }

            if ($masterId === 'lainnya') {
                if ($namaCustom === '') {
                    continue; // judul manual belum diisi, lewati (harusnya sudah kena validasi di atas kalau action=kirim)
                }
                $luaranTambahanLain[] = [
                    'luaran_master_id' => null,
                    'nama_custom' => $namaCustom,
                    'link' => $link,
                ];
            } elseif (!empty($masterId)) {
                $luaranTambahanLain[] = [
                    'luaran_master_id' => (int) $masterId,
                    'nama_custom' => null,
                    'link' => $link,
                ];
            }
        }

        // Persentase dihitung dari luaran yang wajib diisi (tercapai di Kemajuan,
        // atau semua untuk jalur Mandiri) yang link-nya benar-benar sudah terisi.
        $totalWajibDiisi = count($luaranWajibDiisiIds);
        $jumlahTerisi = 0;
        foreach ($luaranWajibDiisiIds as $lid) {
            if (!empty($luaranTercapai[$lid]['link'] ?? '')) {
                $jumlahTerisi++;
            }
        }
        $persentaseOtomatis = $totalWajibDiisi > 0 ? (int) round($jumlahTerisi / $totalWajibDiisi * 100) : 0;

        $laporan = LaporanHasil::firstOrNew(['pengajuan_id' => $pengajuan->id]);
        $laporan->pengajuan_id = $pengajuan->id;
        $laporan->persentase = $persentaseOtomatis;
        $laporan->ringkasan_hasil = $data['ringkasan_hasil'] ?? null;
        $laporan->link_inovasi_produk = $data['link_inovasi_produk'] ?? $laporan->link_inovasi_produk;
        $laporan->no_sk = $data['no_sk'] ?? $laporan->no_sk;
        $laporan->luaran_tercapai = $luaranTercapai;
        $laporan->luaran_tambahan_lain = $luaranTambahanLain;
        $laporan->status = $isKirim ? 'proses' : 'draft';
        $laporan->catatan_validator = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $laporan->file_path = $file->store('laporan-hasil', 'public');
            $laporan->file_nama_asli = $file->getClientOriginalName();
            $laporan->file_size = $file->getSize();
        }

        if ($request->hasFile('kwitansi')) {
            if ($laporan->kwitansi_path) {
                Storage::disk('public')->delete($laporan->kwitansi_path);
            }
            $file = $request->file('kwitansi');
            $laporan->kwitansi_path = $file->store('laporan-hasil/kwitansi', 'public');
            $laporan->kwitansi_nama_asli = $file->getClientOriginalName();
            $laporan->kwitansi_size = $file->getSize();
        }

        if ($request->hasFile('bukti_pajak')) {
            if ($laporan->bukti_pajak_path) {
                Storage::disk('public')->delete($laporan->bukti_pajak_path);
            }
            $file = $request->file('bukti_pajak');
            $laporan->bukti_pajak_path = $file->store('laporan-hasil/bukti-pajak', 'public');
            $laporan->bukti_pajak_nama_asli = $file->getClientOriginalName();
            $laporan->bukti_pajak_size = $file->getSize();
        }

        if ($request->hasFile('berita_acara')) {
            if ($laporan->berita_acara_path) {
                Storage::disk('public')->delete($laporan->berita_acara_path);
            }
            $file = $request->file('berita_acara');
            $laporan->berita_acara_path = $file->store('laporan-hasil/berita-acara', 'public');
            $laporan->berita_acara_nama_asli = $file->getClientOriginalName();
            $laporan->berita_acara_size = $file->getSize();
        }

        if ($request->hasFile('dokumentasi')) {
            $dok = $laporan->dokumentasi ?? [];
            foreach ($request->file('dokumentasi') as $f) {
                $path = $f->store('laporan-hasil/dokumentasi', 'public');
                $dok[] = ['path' => $path, 'nama' => $f->getClientOriginalName()];
            }
            $laporan->dokumentasi = $dok;
        }

        $laporan->save();

        if ($isKirim) {
            // Kirim notifikasi ke semua admin bahwa ada laporan hasil baru masuk
            $adminIds = Pegawai::where('role', 'admin')->pluck('id');
            foreach ($adminIds as $adminId) {
                Notification::create([
                    'user_id' => $adminId,
                    'type' => 'laporan_hasil',
                    'title' => 'Laporan Hasil Baru Masuk',
                    'message' => 'Laporan hasil untuk "' . $pengajuan->judul . '" dari ' . Auth::user()->nama . ' menunggu validasi.',
                ]);
            }

            return redirect()->route('laporan.hasil.sukses', $pengajuan);
        }

        return redirect()->route('laporan.form', [$tipe, $pengajuan])
            ->with('success', 'Draft berhasil disimpan.');
    }

    public function sukses(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        return view('laporan.sukses', [
            'judulHalaman' => 'Laporan Hasil',
            'pengajuan' => $pengajuan,
            'laporan' => $laporan,
            'kembaliUrl' => route('laporan.index', 'hasil'),
            'kembaliLabel' => 'Kembali ke Laporan Hasil',
        ]);
    }

    public function hasilHapusFile(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && $laporan->file_path) {
            Storage::disk('public')->delete($laporan->file_path);
            $laporan->file_path = null;
            $laporan->file_nama_asli = null;
            $laporan->file_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumen berhasil dihapus.');
    }

    public function hasilHapusKwitansi(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && $laporan->kwitansi_path) {
            Storage::disk('public')->delete($laporan->kwitansi_path);
            $laporan->kwitansi_path = null;
            $laporan->kwitansi_nama_asli = null;
            $laporan->kwitansi_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumen kwitansi berhasil dihapus.');
    }

    public function hasilHapusBuktiPajak(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && $laporan->bukti_pajak_path) {
            Storage::disk('public')->delete($laporan->bukti_pajak_path);
            $laporan->bukti_pajak_path = null;
            $laporan->bukti_pajak_nama_asli = null;
            $laporan->bukti_pajak_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumen bukti pajak berhasil dihapus.');
    }

    public function hasilHapusBeritaAcara(Pengajuan $pengajuan)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && $laporan->berita_acara_path) {
            Storage::disk('public')->delete($laporan->berita_acara_path);
            $laporan->berita_acara_path = null;
            $laporan->berita_acara_nama_asli = null;
            $laporan->berita_acara_size = null;
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumen berita acara/hibah berhasil dihapus.');
    }

    public function hasilHapusDokumentasi(Pengajuan $pengajuan, int $index)
    {
        $user = Auth::user();
        abort_unless($pengajuan->pegawai_id === $user->id, 403);

        $laporan = LaporanHasil::where('pengajuan_id', $pengajuan->id)->first();

        abort_if($laporan && in_array($laporan->status, ['proses', 'disetujui']), 403, 'Laporan sudah dikirim dan tidak bisa diubah.');

        if ($laporan && !empty($laporan->dokumentasi[$index])) {
            Storage::disk('public')->delete($laporan->dokumentasi[$index]['path']);
            $dok = $laporan->dokumentasi;
            unset($dok[$index]);
            $laporan->dokumentasi = array_values($dok);
            $laporan->save();
        }

        return redirect()->route('laporan.form', ['hasil', $pengajuan])->with('success', 'Dokumentasi berhasil dihapus.');
    }
}

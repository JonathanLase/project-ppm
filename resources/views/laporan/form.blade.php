@extends('layouts.app')

@section('title', $judulHalaman)
@section('crumbs', 'Menu Dosen / ' . $judulHalaman)

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .tag-opsional {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            color: #1d4ed8;
            background: #dbeafe;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .tag-wajib-field {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            color: #dc2626;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 999px;
            margin-left: 6px;
            vertical-align: middle;
        }

        /* Kotak centang status luaran — dibuat custom (bukan checkbox native) supaya warnanya konsisten hijau, tidak abu-abu walau readonly */
        .luaran-item input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .check-visual {
            display: inline-block;
            width: 18px;
            height: 18px;
            min-width: 18px;
            border: 2px solid #cbd5e1;
            border-radius: 5px;
            background: #fff;
            position: relative;
            vertical-align: middle;
            margin-right: 6px;
            transition: background .15s ease, border-color .15s ease;
        }

        .check-visual.is-checked {
            background: #00875A;
            border-color: #00875A;
        }

        .check-visual.is-checked::after {
            content: '';
            position: absolute;
            left: 5px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid #fff;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .luaran-lain-row {
            display: flex;
            gap: 8px;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .luaran-lain-row select,
        .luaran-lain-row input[type="text"] {
            width: 100%;
        }

        .luaran-lain-pilihan {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }

        .luaran-lain-link {
            flex: 1;
            min-width: 0;
        }

        .luaran-lain-custom {
            display: none;
        }

        .luaran-lain-empty {
            font-size: 12px;
            color: var(--ink-500);
            margin-bottom: 8px;
        }

        /* ===== Upload box compact — horizontal, hemat ruang ===== */
        .upload-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border: 1.5px dashed #cbd5e1;
            border-radius: 10px;
            background: #fafbfc;
            cursor: pointer;
            transition: border-color .15s ease, background .15s ease;
        }

        .upload-box:hover {
            border-color: #00875A;
            background: #f4faf7;
        }

        .upload-box .ub-icon {
            width: 34px;
            height: 34px;
            min-width: 34px;
            border-radius: 8px;
            background: #e6f4ee;
            color: #00875A;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .upload-box .ub-text {
            flex: 1;
            min-width: 0;
        }

        .upload-box .ub-text b {
            display: block;
            font-size: 12.5px;
            color: #111827;
            font-weight: 700;
        }

        .upload-box .ub-text span {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-top: 1px;
        }

        .upload-box .ub-btn {
            flex-shrink: 0;
            font-size: 11.5px;
            font-weight: 700;
            color: #00875A;
            background: #e6f4ee;
            padding: 6px 12px;
            border-radius: 7px;
        }

        .upload-field {
            margin-bottom: 18px;
        }

        .upload-field-label {
            display: flex;
            align-items: center;
            font-size: 12.5px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .upload-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 12px;
            background: #f0fdf7;
            border: 1px solid #cdeee0;
            border-radius: 9px;
            font-size: 12px;
            margin-top: 8px;
        }

        .upload-chip .name {
            color: #1f2937;
            font-weight: 600;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .upload-chip .name a {
            color: var(--green-700);
            font-weight: 700;
        }

        .upload-chip .status {
            flex-shrink: 0;
            font-weight: 700;
            color: var(--green-700);
            font-size: 11px;
        }

        /* ===== Validasi link Google Drive ===== */
        .drive-link-hint {
            font-size: 10.5px;
            color: var(--ink-500);
            margin-top: 4px;
        }

        input.drive-link-input:invalid {
            border-color: #dc2626;
        }

        input.drive-link-input.is-valid {
            border-color: #00875A;
        }
    </style>

    <div style="margin-bottom:14px;">
        <a href="{{ route('laporan.index', $tipe) }}" class="btn btn-outline" style="font-size:12.5px;">&larr; Kembali ke
            Daftar Kegiatan</a>
    </div>

    @if (session('success'))
        <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    @if ($readonly ?? false)
        <div class="alert-box alert-info" style="margin-bottom:16px;">
            🔒 Laporan ini sudah dikirim dan sedang/sudah divalidasi admin. Hubungi admin bila perlu perubahan.
        </div>
    @endif

    <div class="grid g12">
        <div class="card">
            <h3 style="margin-bottom:2px;">{{ $judulHalaman }}</h3>
            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:14px;">
                {{ $pengajuan->kode }} — {{ $pengajuan->judul }}
            </div>

            <div class="field">
                <label>Skema</label>
                <div style="display:flex; align-items:center; gap:8px;">
                    <b>{{ $pengajuan->skema->nama ?? '-' }}</b>
                    <span
                        class="badge {{ $pengajuan->jalur === 'mandiri' ? 'b-selesai' : 'b-disetujui' }}">{{ ucfirst($pengajuan->jalur) }}</span>
                </div>
            </div>

            <div class="grid g2">
                <div class="field"><label>Tahun Pelaksanaan</label>
                    <div>{{ $pengajuan->tahun_pelaksanaan }}</div>
                </div>
                <div class="field">
                    <label>Status Laporan Hasil</label>
                    @if ($laporan)
                        @php [$statusLabel, $statusClass] = $laporan->statusLabel(); @endphp
                        <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    @else
                        <span class="badge b-menunggu">Belum Diajukan</span>
                    @endif
                </div>
            </div>

            @if ($laporan && $laporan->status === 'revisi' && $laporan->catatan_validator)
                <div class="alert-box alert-amber">⚠️ <b>Catatan revisi dari
                        admin:</b><br>{{ $laporan->catatan_validator }}</div>
            @endif

            <form method="POST" action="{{ route('laporan.store', [$tipe, $pengajuan]) }}" enctype="multipart/form-data"
                id="formHasil">
                @csrf

                <div class="field">
                    <label>Ringkasan Hasil <span class="tag-opsional">OPSIONAL</span></label>
                    <textarea name="ringkasan_hasil" rows="3" placeholder="Uraikan singkat hasil pelaksanaan kegiatan..."
                        {{ $readonly ?? false ? 'disabled' : '' }}>{{ old('ringkasan_hasil', $laporan->ringkasan_hasil ?? '') }}</textarea>
                </div>

                {{-- ===================== Upload Dokumen Laporan Hasil (WAJIB) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Dokumen Laporan Hasil <span class="tag-wajib-field">WAJIB
                            DIISI</span></div>

                    @if ($laporan && $laporan->file_path)
                        <div class="upload-chip">
                            <span class="name">📄 <a href="{{ asset('storage/' . $laporan->file_path) }}"
                                    target="_blank">{{ $laporan->file_nama_asli }}</a>
                                &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                            @php [$fl, $fc] = $laporan->statusLabel(); @endphp
                            <span class="badge {{ $fc }}">{{ $fl }}</span>
                        </div>
                    @endif

                    @unless ($readonly ?? false)
                        <label for="fileInput" class="upload-box" style="margin-top:8px;">
                            <span class="ub-icon">📄</span>
                            <span class="ub-text">
                                <b>Upload File Laporan Hasil</b>
                                <span>Klik untuk memilih file (PDF, maks. 5MB)</span>
                            </span>
                            <span class="ub-btn">Pilih File</span>
                        </label>
                        <input type="file" id="fileInput" name="file" accept="application/pdf" style="display:none;"
                            {{ $laporan && $laporan->file_path ? '' : 'required' }} onchange="showFileName(this, 'fileChip')">
                        <div class="upload-chip" id="fileChip" style="display:none;"></div>
                    @endunless

                    @if ($laporan && $laporan->file_path && !($readonly ?? false))
                        <button type="button" class="btn btn-danger btn-sm" style="margin-top:6px;"
                            onclick="hapusItem('{{ route('laporan.hasil.hapus-file', $pengajuan) }}', 'Hapus dokumen ini?')">Hapus
                            Dokumen</button>
                    @endif
                </div>

                {{-- ===================== Upload Kwitansi (WAJIB) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Kwitansi <span class="tag-wajib-field">WAJIB DIISI</span></div>

                    @if ($laporan && $laporan->kwitansi_path)
                        <div class="upload-chip">
                            <span class="name">📄 <a href="{{ asset('storage/' . $laporan->kwitansi_path) }}"
                                    target="_blank">{{ $laporan->kwitansi_nama_asli }}</a>
                                &middot; {{ number_format($laporan->kwitansi_size / 1024, 0) }} KB</span>
                            <span class="status">Tersimpan</span>
                        </div>
                    @endif

                    @unless ($readonly ?? false)
                        <label for="kwitansiInput" class="upload-box" style="margin-top:8px;">
                            <span class="ub-icon">📄</span>
                            <span class="ub-text">
                                <b>Upload File Kwitansi</b>
                                <span>Klik untuk memilih file (PDF, maks. 5MB)</span>
                            </span>
                            <span class="ub-btn">Pilih File</span>
                        </label>
                        <input type="file" id="kwitansiInput" name="kwitansi" accept="application/pdf" style="display:none;"
                            {{ $laporan && $laporan->kwitansi_path ? '' : 'required' }}
                            onchange="showFileName(this, 'kwitansiChip')">
                        <div class="upload-chip" id="kwitansiChip" style="display:none;"></div>
                    @endunless

                    @if ($laporan && $laporan->kwitansi_path && !($readonly ?? false))
                        <button type="button" class="btn btn-danger btn-sm" style="margin-top:6px;"
                            onclick="hapusItem('{{ route('laporan.hasil.hapus-kwitansi', $pengajuan) }}', 'Hapus dokumen kwitansi ini?')">Hapus
                            Kwitansi</button>
                    @endif
                </div>

                {{-- ===================== Upload Bukti Pajak (OPSIONAL) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Bukti Pajak <span class="tag-opsional">OPSIONAL</span></div>

                    @if ($laporan && $laporan->bukti_pajak_path)
                        <div class="upload-chip">
                            <span class="name">📄 <a href="{{ asset('storage/' . $laporan->bukti_pajak_path) }}"
                                    target="_blank">{{ $laporan->bukti_pajak_nama_asli }}</a>
                                &middot; {{ number_format($laporan->bukti_pajak_size / 1024, 0) }} KB</span>
                            <span class="status">Tersimpan</span>
                        </div>
                    @endif

                    @unless ($readonly ?? false)
                        <label for="buktiPajakInput" class="upload-box" style="margin-top:8px;">
                            <span class="ub-icon">📄</span>
                            <span class="ub-text">
                                <b>Upload File Bukti Pajak</b>
                                <span>Klik untuk memilih file (PDF, maks. 5MB)</span>
                            </span>
                            <span class="ub-btn">Pilih File</span>
                        </label>
                        <input type="file" id="buktiPajakInput" name="bukti_pajak" accept="application/pdf"
                            style="display:none;" onchange="showFileName(this, 'buktiPajakChip')">
                        <div class="upload-chip" id="buktiPajakChip" style="display:none;"></div>
                    @endunless

                    @if ($laporan && $laporan->bukti_pajak_path && !($readonly ?? false))
                        <button type="button" class="btn btn-danger btn-sm" style="margin-top:6px;"
                            onclick="hapusItem('{{ route('laporan.hasil.hapus-bukti-pajak', $pengajuan) }}', 'Hapus dokumen bukti pajak ini?')">Hapus
                            Bukti Pajak</button>
                    @endif
                </div>

                {{-- ===================== Upload Berita Acara / Hibah (OPSIONAL) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Berita Acara Hibah <span class="tag-opsional">OPSIONAL</span>
                    </div>

                    @if ($laporan && $laporan->berita_acara_path)
                        <div class="upload-chip">
                            <span class="name">📄 <a href="{{ asset('storage/' . $laporan->berita_acara_path) }}"
                                    target="_blank">{{ $laporan->berita_acara_nama_asli }}</a>
                                &middot; {{ number_format($laporan->berita_acara_size / 1024, 0) }} KB</span>
                            <span class="status">Tersimpan</span>
                        </div>
                    @endif

                    @unless ($readonly ?? false)
                        <label for="beritaAcaraInput" class="upload-box" style="margin-top:8px;">
                            <span class="ub-icon">📄</span>
                            <span class="ub-text">
                                <b>Upload File Berita Acara / Hibah</b>
                                <span>Klik untuk memilih file (PDF, maks. 5MB)</span>
                            </span>
                            <span class="ub-btn">Pilih File</span>
                        </label>
                        <input type="file" id="beritaAcaraInput" name="berita_acara" accept="application/pdf"
                            style="display:none;" onchange="showFileName(this, 'beritaAcaraChip')">
                        <div class="upload-chip" id="beritaAcaraChip" style="display:none;"></div>
                    @endunless

                    @if ($laporan && $laporan->berita_acara_path && !($readonly ?? false))
                        <button type="button" class="btn btn-danger btn-sm" style="margin-top:6px;"
                            onclick="hapusItem('{{ route('laporan.hasil.hapus-berita-acara', $pengajuan) }}', 'Hapus dokumen berita acara/hibah ini?')">Hapus
                            Berita Acara/Hibah</button>
                    @endif
                </div>

                <div class="field">
                    <label>Link Inovasi Produk (Google Drive) <span class="tag-wajib-field">WAJIB DIISI</span></label>
                    <input type="text" name="link_inovasi_produk" class="drive-link-input"
                        placeholder="https://drive.google.com/..."
                        value="{{ old('link_inovasi_produk', $laporan->link_inovasi_produk ?? '') }}"
                        {{ $readonly ?? false ? 'disabled' : '' }} required
                        pattern="^(https?:\/\/)?(www\.)?(drive|docs)\.google\.com\/.+$" oninput="validateDriveLink(this)">
                    <div class="hint drive-link-hint" style="text-align:left;">Wajib berupa tautan Google Drive
                        (drive.google.com atau docs.google.com) dan pastikan akses tautan public/editor agar dapat
                        diakses admin.</div>
                </div>

                <div class="field">
                    <label>No. SK Penelitian &amp; Pengabdian <span class="tag-wajib-field">WAJIB DIISI</span></label>
                    <input type="text" name="no_sk" placeholder="Contoh: SK.421/PNL-2026-00125/2026"
                        value="{{ old('no_sk', $laporan->no_sk ?? '') }}" {{ $readonly ?? false ? 'disabled' : '' }}
                        required>
                </div>
            </form>
        </div>

        <div class="card">
            <h3 style="margin-bottom:2px;">Status Luaran Wajib &amp; Tambahan</h3>
            <div class="sub">Luaran berikut diambil dari rencana luaran saat pengajuan proposal. Status tercapai
                mengikuti apa yang sudah dicentang di Laporan Kemajuan (khusus jalur Simlitabkes) — untuk jalur
                Mandiri, semua luaran otomatis tercapai. Isi tautan bukti (wajib berupa link Google Drive) untuk
                luaran yang berstatus tercapai.</div>

            @forelse ($luaranList as $pl)
                @php
                    $existing = ($laporan->luaran_tercapai ?? [])[$pl->id] ?? null;
                    $isTercapai = in_array($pl->id, $luaranTercapaiIds ?? []);
                @endphp
                <div class="luaran-item" data-achieved="{{ $isTercapai ? '1' : '0' }}">
                    <div class="hd">
                        {{-- Checkbox ini MENGIKUTI status di Laporan Kemajuan (jalur Simlitabkes)
                             atau selalu tercentang untuk jalur Mandiri (tidak ada tahap Kemajuan). --}}
                        <input type="checkbox" id="chk{{ $pl->id }}" disabled {{ $isTercapai ? 'checked' : '' }}>
                        <span class="check-visual {{ $isTercapai ? 'is-checked' : '' }}"
                            id="checkVisual{{ $pl->id }}"></span>
                        <b>{{ $pl->luaranMaster->nama ?? '-' }}</b>
                        @if ($pl->is_wajib)
                            <span class="tag-wajib">WAJIB</span>
                        @else
                            <span class="opt-tag">TAMBAHAN</span>
                        @endif
                        @unless ($isTercapai)
                            <span class="tag-opsional" style="background:#f1f5f9; color:#64748b;">Belum tercapai di
                                Laporan Kemajuan</span>
                        @endunless
                    </div>
                    <input type="text" form="formHasil" name="luaran[{{ $pl->id }}][link]"
                        class="drive-link-input" id="linkLuaran{{ $pl->id }}"
                        placeholder="https://drive.google.com/... (link bukti luaran)"
                        value="{{ $existing['link'] ?? '' }}" {{ $readonly ?? false ? 'disabled' : '' }}
                        pattern="^(https?:\/\/)?(www\.)?(drive|docs)\.google\.com\/.+$"
                        oninput="validateDriveLink(this); updateKemajuanLuaran();">
                </div>
            @empty
                <div class="sub">Tidak ada luaran yang direncanakan pada pengajuan ini.</div>
            @endforelse

            @php
                $totalTercapai = collect($luaranList)
                    ->filter(fn($pl) => in_array($pl->id, $luaranTercapaiIds ?? []))
                    ->count();
            @endphp
            <div class="field" style="margin-top:14px;">
                <label>Kemajuan Luaran</label>
                <div style="background:#f1f5f9; border-radius:8px; height:8px; overflow:hidden;">
                    <div id="progressBarLuaran" style="width:0%; background:#00875A; height:100%; transition:width 0.2s;">
                    </div>
                </div>
                <div class="sub" style="margin-top:4px;" id="progressTextLuaran">0 dari {{ $totalTercapai }} luaran
                    terpenuhi (0%)</div>
            </div>

            <div class="field" style="margin-top:16px;">
                <label>Dokumentasi Kegiatan <span class="tag-opsional">OPSIONAL</span></label>
                @if (!empty($laporan->dokumentasi))
                    <div class="upload-chip" style="margin-bottom:8px;">
                        <span class="name">🖼️ {{ count($laporan->dokumentasi) }} file dipilih</span>
                    </div>
                @endif
                @unless ($readonly ?? false)
                    <input type="file" form="formHasil" name="dokumentasi[]" accept="image/*" multiple>
                @endunless
            </div>

            {{-- ===================== Luaran Lainnya ===================== --}}
            <div class="field" style="margin-top:16px; padding-top:16px; border-top:1px dashed #e2e8f0;">
                <label>Luaran Lainnya <span class="tag-opsional">OPSIONAL</span></label>
                <div class="sub" style="margin-bottom:10px;">
                    Luaran yang tercapai di lapangan tapi tidak direncanakan/dipilih saat pengajuan proposal bisa
                    ditambahkan di sini. Pilih judul luaran dari daftar, atau pilih "Lainnya" untuk menulis judul
                    luaran sendiri, lalu isi tautan bukti (wajib link Google Drive).
                </div>

                <div id="luaranLainContainer"></div>
                <div class="luaran-lain-empty" id="luaranLainEmptyMsg" style="display:none;">Belum ada luaran lain yang
                    ditambahkan.</div>

                @unless ($readonly ?? false)
                    <button type="button" class="btn btn-outline btn-sm" onclick="tambahLuaranLain()">+ Tambah
                        Luaran</button>
                @endunless
            </div>

            @unless ($readonly ?? false)
                <div style="display:flex; justify-content:space-between; margin-top:20px;">
                    <button class="btn btn-primary" type="submit" form="formHasil" name="action" value="kirim">Kirim
                        Laporan</button>
                </div>
            @endunless
        </div>
    </div>

    {{-- Template baris "Luaran Lainnya" — di-clone lewat JS setiap kali baris baru ditambahkan.
         Sekarang ada 2 mode: pilih dari daftar $luaranMasterLain, ATAU pilih opsi
         "Lainnya (input manual)" yang memunculkan textbox judul bebas. --}}
    <template id="templateLuaranLain">
        <div class="luaran-lain-row">
            <div class="luaran-lain-pilihan">
                <select form="formHasil" name="luaran_lain[__INDEX__][luaran_master_id]" class="luaran-lain-select"
                    {{ $readonly ?? false ? 'disabled' : '' }}>
                    <option value="">Pilih judul luaran...</option>
                    @foreach ($luaranMasterLain as $lm)
                        <option value="{{ $lm->id }}">{{ $lm->nama }}</option>
                    @endforeach
                    <option value="lainnya">+ Lainnya (input manual)</option>
                </select>
                <input type="text" form="formHasil" name="luaran_lain[__INDEX__][nama_custom]"
                    class="luaran-lain-custom" placeholder="Tulis judul luaran secara manual..."
                    {{ $readonly ?? false ? 'disabled' : '' }}>
            </div>
            <input type="text" form="formHasil" name="luaran_lain[__INDEX__][link]"
                class="luaran-lain-link drive-link-input" placeholder="https://drive.google.com/... (link bukti luaran)"
                {{ $readonly ?? false ? 'disabled' : '' }} pattern="^(https?:\/\/)?(www\.)?(drive|docs)\.google\.com\/.+$"
                oninput="validateDriveLink(this)">
            @unless ($readonly ?? false)
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.luaran-lain-row').remove()">
                    Hapus</button>
            @endunless
        </div>
    </template>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // Dipakai untuk semua tombol "Hapus X" (dokumen, kwitansi, bukti pajak, berita acara)
        // supaya TIDAK perlu bikin <form> baru di dalam <form id="formHasil"> — form bersarang
        // itu invalid HTML dan bikin browser menutup paksa formHasil lebih awal, sehingga field
        // sesudahnya (kwitansi dst) gagal ikut terkirim.
        function hapusItem(url, confirmMsg) {
            if (!confirm(confirmMsg || 'Yakin ingin menghapus file ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF_TOKEN + '">';
            document.body.appendChild(form);
            form.submit();
        }

        function showFileName(input, chipId) {
            const chip = document.getElementById(chipId);
            if (input.files && input.files[0]) {
                const f = input.files[0];
                chip.style.display = 'flex';
                chip.innerHTML =
                    `<span class="name">📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span class="status">Siap diunggah</span>`;
            }
        }

        // ===== Validasi link Google Drive =====
        const driveLinkRegex = /^(https?:\/\/)?(www\.)?(drive|docs)\.google\.com\/.+$/i;

        function validateDriveLink(input) {
            const value = input.value.trim();
            if (value === '') {
                input.classList.remove('is-valid');
                input.setCustomValidity('');
                return;
            }
            if (driveLinkRegex.test(value)) {
                input.classList.add('is-valid');
                input.setCustomValidity('');
            } else {
                input.classList.remove('is-valid');
                input.setCustomValidity(
                    'Link harus berupa tautan Google Drive yang valid, contoh: https://drive.google.com/file/d/....'
                );
            }
        }

        // Validasi ulang setiap link Google Drive yang sudah terisi (mis. dari draft) saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.drive-link-input').forEach(validateDriveLink);
        });

        // Progress "Kemajuan Luaran" sekarang hanya dihitung dari luaran yang memang
        // berstatus tercapai (data-achieved="1") — sesuai centang di Laporan Kemajuan
        // (atau semua, untuk jalur Mandiri). Luaran yang belum tercapai tidak ikut
        // dihitung sebagai penyebut, karena memang belum wajib diisi.
        function updateKemajuanLuaran() {
            const achievedItems = document.querySelectorAll('.luaran-item[data-achieved="1"]');
            const total = achievedItems.length;
            let terisi = 0;
            achievedItems.forEach(item => {
                const inp = item.querySelector('input[type="text"][id^="linkLuaran"]');
                if (inp && inp.value.trim().length > 0) terisi++;
            });

            const persen = total > 0 ? Math.round((terisi / total) * 100) : 0;

            document.getElementById('progressBarLuaran').style.width = persen + '%';
            document.getElementById('progressTextLuaran').textContent =
                terisi + ' dari ' + total + ' luaran terpenuhi (' + persen + '%)';
        }

        // Hitung sekali saat halaman dimuat, supaya kalau ada link yang sudah terisi
        // sebelumnya (dari draft/reload), progress bar langsung akurat tanpa perlu diketik ulang.
        document.addEventListener('DOMContentLoaded', updateKemajuanLuaran);

        // ===== Luaran Lainnya =====
        let luaranLainIndex = 0;

        function tambahLuaranLain(selectedId = '', linkVal = '', customNama = '') {
            const tplHtml = document.getElementById('templateLuaranLain').innerHTML.replaceAll('__INDEX__',
                luaranLainIndex);
            const wrapper = document.createElement('div');
            wrapper.innerHTML = tplHtml.trim();
            const row = wrapper.firstElementChild;

            const select = row.querySelector('.luaran-lain-select');
            const customInput = row.querySelector('.luaran-lain-custom');
            const linkInput = row.querySelector('.luaran-lain-link');

            // Kalau data lama punya nama_custom (dulu pernah diisi manual),
            // langsung set dropdown ke "Lainnya" dan tampilkan textbox-nya terisi.
            if (customNama) {
                select.value = 'lainnya';
                customInput.style.display = 'block';
                customInput.required = true;
                customInput.value = customNama;
            } else if (selectedId) {
                select.value = selectedId;
            }

            if (linkVal) {
                linkInput.value = linkVal;
                validateDriveLink(linkInput);
            }

            document.getElementById('luaranLainContainer').appendChild(row);
            luaranLainIndex++;
        }

        // Toggle textbox manual setiap kali pilihan dropdown "Luaran Lainnya" diubah.
        // Pakai event delegation di container supaya tetap jalan untuk baris
        // yang ditambahkan belakangan lewat JS (bukan cuma yang ada saat load).
        document.getElementById('luaranLainContainer').addEventListener('change', function(e) {
            if (!e.target.classList.contains('luaran-lain-select')) return;

            const row = e.target.closest('.luaran-lain-row');
            const customInput = row.querySelector('.luaran-lain-custom');

            if (e.target.value === 'lainnya') {
                customInput.style.display = 'block';
                customInput.required = true;
                customInput.focus();
            } else {
                customInput.style.display = 'none';
                customInput.required = false;
                customInput.value = '';
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const existing = @json($laporan->luaran_tambahan_lain ?? []);
            existing.forEach(item => tambahLuaranLain(
                item.luaran_master_id ?? '',
                item.link ?? '',
                item.nama_custom ?? ''
            ));
        });
    </script>
@endsection

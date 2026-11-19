@extends('layouts.app')

@section('title', 'Detail Laporan Kemajuan')
@section('crumbs', 'Menu Dosen / Laporan Kemajuan / Detail')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div style="margin-bottom:14px; display:flex; justify-content:space-between; align-items:center;">
        <a href="{{ route('laporan.kemajuan') }}" class="btn btn-outline" style="font-size:12.5px;">&larr; Kembali ke
            Daftar Kegiatan</a>

        @if ($laporan->status === 'revisi')
            <a href="{{ route('laporan.kemajuan.form', $pengajuan) }}" class="btn btn-primary" style="font-size:12.5px;">Edit
                Laporan</a>
        @endif
    </div>

    {{-- ===================== LACAK STATUS VALIDASI LAPORAN KEMAJUAN ===================== --}}
    <div style="margin-bottom:16px;">
        @include('partials.timeline-validasi', [
            'sumber' => [['objek' => $laporan, 'tahap' => 'laporan_kemajuan']],
            'judul'  => 'Lacak Status Validasi Laporan Kemajuan',
        ])
    </div>

    <div class="grid g12" style="align-items:start; grid-template-columns: 1.1fr 1fr; gap:16px;">
        <div class="card">
            <h3>Laporan Kemajuan</h3>
            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:14px;">
                {{ $pengajuan->kode }} — {{ $pengajuan->judul }}
            </div>

            <div class="field"><label>Skema</label>
                <div>{{ $pengajuan->skema->nama ?? '-' }} &middot; <span class="badge b-disetujui">Simlitabkes</span></div>
            </div>
            <div class="field"><label>Tanggal Upload Pengajuan</label>
                <div>{{ $pengajuan->created_at->format('d M Y') }}</div>
            </div>
            <div class="field">
                <label>Status Validasi</label>
                <div>@php [$sl, $sc] = $laporan->statusLabel(); @endphp<span class="badge {{ $sc }}">{{ $sl }}</span></div>
            </div>

            @if ($laporan->status === 'revisi' && $laporan->catatan_validator)
                <div class="alert-box alert-amber" style="margin-top:8px;">
                    <div><b>Catatan Revisi dari Admin</b><br>{{ $laporan->catatan_validator }}</div>
                </div>
            @endif

            <div class="field" style="margin-top:14px;"><label>Komentar</label>
                <div>{{ $laporan->komentar ?: '-' }}</div>
            </div>

            <div class="field"><label>Dokumen Kemajuan</label>
                @if ($laporan->file_path)
                    <div class="file-chip" style="display:flex; align-items:center; gap:10px;">
                        <span>📄 <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a>
                            &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                    </div>
                @else
                    <div style="color:var(--ink-500); font-size:12.5px;">Belum ada dokumen.</div>
                @endif
            </div>

            <div class="field">
                <label>Dokumen Pendukung</label>
                @if (!empty($laporan->dokumentasi))
                    @foreach ($laporan->dokumentasi as $dok)
                        @php
                            $ext = strtolower(pathinfo($dok['nama'] ?? ($dok['path'] ?? ''), PATHINFO_EXTENSION));
                            $isPdf = ($ext === 'pdf');
                        @endphp
                        <div class="file-chip" style="margin-bottom:6px;">
                            @if ($isPdf) 📄 @else 🖼️ @endif
                            <a href="{{ asset('storage/' . ($dok['path'] ?? '')) }}" target="_blank"
                               style="color:var(--green-700); font-weight:700;">
                                {{ $dok['nama'] ?? 'Dokumen Pendukung' }}
                            </a>
                        </div>
                    @endforeach
                @else
                    <div style="color:var(--ink-500); font-size:12.5px;">Tidak ada dokumen pendukung.</div>
                @endif
            </div>
        </div>

        <div class="card">
            <h3>Capaian Kegiatan</h3>
            <div class="field"><label>Kegiatan yang telah dilakukan</label>
                <div style="white-space:pre-line;">{{ $laporan->kegiatan_dilakukan ?: '-' }}</div>
            </div>
            <div class="field"><label>Kendala</label>
                <div style="white-space:pre-line;">{{ $laporan->kendala ?: '-' }}</div>
            </div>
            <div class="field" style="margin-bottom:20px;"><label>Rencana Berikutnya</label>
                <div style="white-space:pre-line;">{{ $laporan->rencana_berikutnya ?: '-' }}</div>
            </div>

            <div style="border-top:1px solid var(--line); padding-top:18px;">
                <h3>Status Luaran Wajib &amp; Tambahan</h3>
                @php $tercapai = $laporan->luaran_tercapai ?? []; @endphp
                @foreach ($pengajuan->luaran as $l)
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:10px; padding:6px 0;">
                        <span style="display:flex; align-items:center; gap:8px;">
                            <span style="font-size:14px;">{{ in_array($l->id, $tercapai) ? '✅' : '⬜' }}</span>
                            {{ $l->luaranMaster->nama }}
                        </span>
                        <span
                            style="display:inline-block; padding:2px 10px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.3px; white-space:nowrap; {{ $l->luaranMaster->wajib ? 'background:#fee2e2; color:#dc2626;' : 'background:#dbeafe; color:#2563eb;' }}">{{ $l->luaranMaster->wajib ? 'WAJIB' : 'TAMBAHAN' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
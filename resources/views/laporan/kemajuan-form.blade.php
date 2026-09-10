@extends('layouts.app')

@section('title', 'Laporan Kemajuan')
@section('crumbs', 'Menu Dosen / Laporan Kemajuan / Isi Laporan')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div style="margin-bottom:14px;">
        <a href="{{ route('laporan.kemajuan') }}" class="btn btn-outline" style="font-size:12.5px;">&larr; Kembali ke
            Daftar Kegiatan</a>
    </div>

    @if ($errors->any())
        <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
    @endif

    @if (session('success'))
        <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('laporan.kemajuan.store', $pengajuan) }}" enctype="multipart/form-data"
        id="formLaporan">
        @csrf
        <input type="hidden" name="action" id="actionInput" value="draft">

        <div class="grid g12" style="align-items:start; grid-template-columns: 1.1fr 1fr; gap:16px;">
            <div class="card">
                <h3>Laporan Kemajuan</h3>
                <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:14px;">
                    {{ $pengajuan->kode }} — {{ $pengajuan->judul }}
                </div>

                <div class="field"><label>Skema</label>
                    <div>{{ $pengajuan->skema->nama ?? '-' }} &middot; <span class="badge b-disetujui">Simlitabkes</span>
                    </div>
                </div>
                <div class="field"><label>Tanggal Upload Pengajuan</label>
                    <div>{{ $pengajuan->created_at->format('d M Y') }}</div>
                </div>
                <div class="field">
                    <label>Status Validasi</label>
                    <div>@php [$sl, $sc] = $laporan ? $laporan->statusLabel() : ['Belum Diisi', 'b-menunggu']; @endphp<span class="badge {{ $sc }}">{{ $sl }}</span></div>
                </div>

                <div class="field">
                    <label>Upload Dokumen Kemajuan (PDF, maks. 5MB) <span
                            style="color:#dc2626; font-weight:600; font-size:11.5px;">Wajib diisi</span></label>
                    @if ($laporan && $laporan->file_path)
                        <div class="file-chip"
                            style="display:flex; align-items:center; justify-content:space-between; gap:10px;">
                            <span>📄 <a href="{{ asset('storage/' . $laporan->file_path) }}" target="_blank"
                                    style="color:var(--green-700); font-weight:700;">{{ $laporan->file_nama_asli }}</a>
                                &middot; {{ number_format($laporan->file_size / 1024, 0) }} KB</span>
                            <button type="button"
                                onclick="hapusItem('{{ route('laporan.kemajuan.hapus-file', $pengajuan) }}')"
                                style="border:1px solid #fca5a5; background:#fff; color:#dc2626; border-radius:6px; padding:4px 10px; font-size:12px; cursor:pointer; white-space:nowrap;">Hapus</button>
                        </div>
                    @endif
                    <input type="file" name="file" accept="application/pdf" style="margin-top:8px;">
                </div>

                <div class="field">
                    <label>Dokumentasi Kegiatan (boleh lebih dari satu) <span
                            style="color:#2563eb; font-weight:600; font-size:11.5px;">Opsional</span></label>
                    @if ($laporan && !empty($laporan->dokumentasi))
                        @foreach ($laporan->dokumentasi as $i => $dok)
                            <div class="file-chip"
                                style="display:flex; align-items:center; justify-content:space-between; gap:10px; margin-bottom:6px;">
                                <span>🖼 {{ $dok['nama'] ?? 'Dokumentasi ' . ($i + 1) }}</span>
                                <button type="button"
                                    onclick="hapusItem('{{ route('laporan.kemajuan.hapus-dokumentasi', [$pengajuan, $i]) }}')"
                                    style="border:1px solid #fca5a5; background:#fff; color:#dc2626; border-radius:6px; padding:4px 10px; font-size:12px; cursor:pointer; white-space:nowrap;">Hapus</button>
                            </div>
                        @endforeach
                    @endif
                    <input type="file" name="dokumentasi[]" accept="image/*" multiple style="margin-top:8px;">
                </div>
            </div>

            <div class="card">
                <h3>Capaian Kegiatan <span style="color:#2563eb; font-weight:600; font-size:11.5px;">Opsional</span></h3>
                <div class="field"><label>Kegiatan yang telah dilakukan</label>
                    <textarea name="kegiatan_dilakukan" rows="4" placeholder="1. ...&#10;2. ...">{{ old('kegiatan_dilakukan', $laporan->kegiatan_dilakukan ?? '') }}</textarea>
                </div>
                <div class="field"><label>Kendala</label>
                    <textarea name="kendala" rows="2">{{ old('kendala', $laporan->kendala ?? '') }}</textarea>
                </div>
                <div class="field" style="margin-bottom:20px;"><label>Rencana Berikutnya</label>
                    <textarea name="rencana_berikutnya" rows="2">{{ old('rencana_berikutnya', $laporan->rencana_berikutnya ?? '') }}</textarea>
                </div>

                <div style="border-top:1px solid var(--line); padding-top:18px;">
                    <h3>Status Luaran Wajib &amp; Tambahan</h3>
                    <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:10px;">Centang
                        luaran yang sudah tercapai pada tahap ini.</div>
                    @php $tercapai = $laporan->luaran_tercapai ?? []; @endphp
                    @foreach ($pengajuan->luaran as $l)
                        <label class="luaran-item"
                            style="cursor:pointer; display:flex; align-items:center; justify-content:space-between; gap:10px; padding:6px 0;">
                            <span style="display:flex; align-items:center; gap:8px;">
                                <input type="checkbox" name="luaran_tercapai[]" value="{{ $l->id }}"
                                    {{ in_array($l->id, $tercapai) ? 'checked' : '' }}>
                                {{ $l->luaranMaster->nama }}
                            </span>
                            <span
                                style="display:inline-block; padding:2px 10px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.3px; white-space:nowrap; {{ $l->is_wajib ? 'background:#fee2e2; color:#dc2626;' : 'background:#dbeafe; color:#2563eb;' }}">{{ $l->is_wajib ? 'WAJIB' : 'TAMBAHAN' }}</span>
                        </label>
                    @endforeach

                    <div style="display:flex; gap:10px; margin-top:14px;">

                        <button type="submit" class="btn btn-primary"
                            onclick="document.getElementById('actionInput').value='kirim'">Kirim Laporan</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function hapusItem(url) {
            if (!confirm('Yakin ingin menghapus file ini?')) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.innerHTML = '<input type="hidden" name="_token" value="' + CSRF_TOKEN + '">';
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection

@extends('layouts.app')

@section('title', 'Luaran')
@section('crumbs', 'Menu Dosen / Luaran')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <div class="card wizard-card">
        <div style="margin-bottom:16px;">
            <h3 style="margin-bottom:2px;">{{ $luaran->luaranMaster->nama }}</h3>
            <div style="font-size:12px; color:var(--ink-500);">{{ $pengajuan->judul }} &middot; {{ $pengajuan->kode }}</div>
        </div>

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        @if ($realisasi && $realisasi->status === 'revisi' && $realisasi->catatan_validator)
            <div class="alert-box alert-amber">⚠️ <b>Catatan revisi:</b> {{ $realisasi->catatan_validator }}</div>
        @endif

        @if ($realisasi && $realisasi->file_path)
            <div class="field">
                <label>Dokumen Bukti Saat Ini</label>
                <div class="file-chip">
                    <span>📄 <a href="{{ asset('storage/' . $realisasi->file_path) }}" target="_blank"
                            style="color:var(--green-700); font-weight:700;">{{ $realisasi->file_nama_asli }}</a></span>
                    @php [$label, $class] = $realisasi->statusLabel(); @endphp
                    <span class="badge {{ $class }}">{{ $label }}</span>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('luaran.store', $luaran) }}" enctype="multipart/form-data">
            @csrf

            <div class="field">
                <label>Keterangan / Judul Capaian</label>
                <textarea name="keterangan" rows="3"
                    placeholder="Contoh: Artikel 'Sistem Informasi Klinik Berbasis Web' dimuat di Jurnal XYZ Vol.5" required>{{ old('keterangan', $realisasi->keterangan ?? '') }}</textarea>
            </div>

            <div class="field">
                <label>Link Bukti (opsional)</label>
                <input type="url" name="link_bukti" placeholder="https://..."
                    value="{{ old('link_bukti', $realisasi->link_bukti ?? '') }}">
            </div>

            <div class="field">
                <label>Tanggal Realisasi</label>
                <input type="date" name="tanggal_realisasi"
                    value="{{ old('tanggal_realisasi', $realisasi?->tanggal_realisasi?->format('Y-m-d')) }}" required>
            </div>

            <div class="field">
                <label>Dokumen Bukti (PDF/Gambar, opsional)</label>
                <label for="fileInput" style="display:block;">
                    <div class="dropzone">
                        <div class="ic">☁️</div>
                        <b>Upload Bukti (PDF/JPG/PNG, maks. 5MB)</b>
                        <span>Klik di sini untuk memilih file</span>
                    </div>
                </label>
                <input type="file" id="fileInput" name="file" accept="application/pdf,image/*" style="display:none;"
                    onchange="showFileName(this)">
                <div class="file-chip" id="fileChip" style="display:none;"></div>
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('luaran.index') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Simpan Realisasi</button>
            </div>
        </form>
    </div>

    <script>
        function showFileName(input) {
            const chip = document.getElementById('fileChip');
            if (input.files && input.files[0]) {
                const f = input.files[0];
                chip.style.display = 'flex';
                chip.innerHTML =
                    `<span>📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span style="color:var(--green-700); font-weight:700;">Siap diunggah</span>`;
            }
        }
    </script>
@endsection

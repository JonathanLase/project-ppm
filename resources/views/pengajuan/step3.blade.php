@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .s3-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 800;
            font-size: 13.5px;
            color: #111827;
            margin-bottom: 12px;
        }

        .s3-section-title .ic {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #e6f4ee;
            color: #00875A;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .s3-section-title .ic svg {
            width: 13px;
            height: 13px;
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

        .upload-field-hint {
            font-size: 11px;
            color: var(--ink-500);
            margin-bottom: 8px;
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

        .upload-chip .status {
            flex-shrink: 0;
            font-weight: 700;
            color: var(--green-700);
            font-size: 11px;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 3])

        @if ($errors->any())
            <div class="login-alert" style="display:flex; margin-bottom:16px;">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('pengajuan.step3.post') }}" enctype="multipart/form-data">
            @csrf

            <div class="s3-section-title">
                <span class="ic">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </span>
                Dokumen & Anggaran
            </div>

            {{-- ===================== Dokumen Proposal (WAJIB) ===================== --}}
            <div class="upload-field">
                <div class="upload-field-label">Dokumen Proposal <span class="tag-wajib-field">WAJIB DIISI</span></div>
                <div class="upload-field-hint">Format PDF, ukuran maksimal 2 MB.</div>

                <label for="proposalInput" class="upload-box">
                    <span class="ub-icon">📄</span>
                    <span class="ub-text">
                        <b>Upload File Proposal</b>
                        <span>Klik untuk memilih file (PDF, maks. 2MB)</span>
                    </span>
                    <span class="ub-btn">Pilih File</span>
                </label>
                <input type="file" id="proposalInput" name="proposal" accept="application/pdf" style="display:none;"
                    onchange="showFileName(this, 'chipProposal')">

                @if ($w['proposal_path'])
                    <div class="upload-chip" id="chipProposal">
                        <span class="name">📄 {{ $w['proposal_nama_asli'] }} &middot;
                            {{ number_format($w['proposal_size'] / 1024, 0) }} KB</span>
                        <span class="status">Tersimpan</span>
                    </div>
                @else
                    <div class="upload-chip" id="chipProposal" style="display:none;"></div>
                @endif

                {{-- ===================== Dokumen Kontrak (OPSIONAL) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Dokumen Kontrak <span class="tag-opsional-field" style="background:#f3f4f6; color:#4b5563; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:700; margin-left:6px;">OPSIONAL</span></div>
                    <div class="upload-field-hint">Format PDF, ukuran maksimal 2 MB (opsional).</div>

                    <label for="kontrakInput" class="upload-box">
                        <span class="ub-icon">📄</span>
                        <span class="ub-text">
                            <b>Upload File Kontrak</b>
                            <span>Klik untuk memilih file (PDF, maks. 2MB)</span>
                        </span>
                        <span class="ub-btn">Pilih File</span>
                    </label>
                    <input type="file" id="kontrakInput" name="kontrak" accept="application/pdf" style="display:none;"
                        onchange="showFileName(this, 'chipKontrak')">

                    @if ($w['kontrak_path'])
                        <div class="upload-chip" id="chipKontrak">
                            <span class="name">📄 {{ $w['kontrak_nama_asli'] }} &middot;
                                {{ number_format($w['kontrak_size'] / 1024, 0) }} KB</span>
                            <span class="status">Tersimpan</span>
                        </div>
                    @else
                        <div class="upload-chip" id="chipKontrak" style="display:none;"></div>
                    @endif
                </div>

                {{-- ===================== Dokumen RAB (OPSIONAL) ===================== --}}
                <div class="upload-field">
                    <div class="upload-field-label">Dokumen RAB (Rencana Anggaran Biaya) <span class="tag-opsional-field" style="background:#f3f4f6; color:#4b5563; padding:2px 8px; border-radius:6px; font-size:11px; font-weight:700; margin-left:6px;">OPSIONAL</span></div>
                    <div class="upload-field-hint">Format PDF, ukuran maksimal 2 MB (opsional).</div>

                    <label for="rabInput" class="upload-box">
                        <span class="ub-icon">📄</span>
                        <span class="ub-text">
                            <b>Upload File RAB</b>
                            <span>Klik untuk memilih file (PDF, maks. 2MB)</span>
                        </span>
                        <span class="ub-btn">Pilih File</span>
                    </label>
                    <input type="file" id="rabInput" name="rab" accept="application/pdf" style="display:none;"
                        onchange="showFileName(this, 'chipRab')">

                    @if ($w['rab_path'])
                        <div class="upload-chip" id="chipRab">
                            <span class="name">📄 {{ $w['rab_nama_asli'] }} &middot;
                                {{ number_format($w['rab_size'] / 1024, 0) }} KB</span>
                            <span class="status">Tersimpan</span>
                        </div>
                    @else
                        <div class="upload-chip" id="chipRab" style="display:none;"></div>
                    @endif
                    <div class="alert-box alert-amber" style="margin-top:10px;">⚠️ Jika proposal diminta revisi oleh admin,
                        dokumen lama <b>tidak akan dihapus</b> dan tetap tersimpan. Anda cukup mengunggah dokumen proposal
                        versi
                        terbaru sebagai tambahan.</div>
                </div>

            </div>

            <div class="field">
                <label>Total Biaya Usulan (Rp)</label>
                <input type="text" id="total_biaya_display" placeholder="10.000.000" autocomplete="off"
                    inputmode="numeric">
                <input type="hidden" name="total_biaya" id="total_biaya" value="{{ $w['total_biaya'] }}">
            </div>

            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step2') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Selanjutnya</button>
            </div>
        </form>
    </div>

    <script>
        function showFileName(input, chipId) {
            const chip = document.getElementById(chipId);
            if (input.files && input.files[0]) {
                const f = input.files[0];
                chip.style.display = 'flex';
                chip.innerHTML =
                    `<span class="name">📄 ${f.name} &middot; ${Math.round(f.size/1024)} KB</span><span class="status">Siap diunggah</span>`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const display = document.getElementById('total_biaya_display');
            const hidden = document.getElementById('total_biaya');

            function formatRupiah(angka) {
                if (!angka) return '';
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            if (hidden.value) {
                display.value = formatRupiah(hidden.value);
            }

            display.addEventListener('input', function(e) {
                let angka = e.target.value.replace(/\D/g, '');
                hidden.value = angka;
                e.target.value = formatRupiah(angka);
            });
        });
    </script>
@endsection
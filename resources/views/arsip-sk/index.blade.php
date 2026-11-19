@extends('layouts.app')

@section('title', 'Arsip SK')
@section('crumbs', 'Menu Dosen / Arsip SK')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .arsip-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 22px;
            margin-bottom: 20px;
        }

        .arsip-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f3f5;
        }

        .arsip-header h2 {
            font-size: 19px;
            font-weight: 800;
            color: #0b3d2e;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .arsip-header p {
            font-size: 12.5px;
            color: #6b7280;
            margin: 0;
        }

        /* Filter Toolbar */
        .filter-panel {
            background: #fbfdfc;
            border: 1px solid #e2ece7;
            border-radius: 12px;
            padding: 16px 18px;
            margin-bottom: 22px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 12px;
            align-items: end;
        }

        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-item label {
            font-size: 11px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .filter-input, .filter-select {
            width: 100%;
            height: 38px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            font-size: 12.5px;
            color: #1f2937;
            outline: none;
            transition: all 0.2s ease;
        }

        .filter-input:focus, .filter-select:focus {
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.12);
        }

        .filter-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-filter-submit {
            height: 38px;
            padding: 0 16px;
            background: #00875A;
            color: #ffffff;
            border-radius: 8px;
            border: none;
            font-size: 12.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.2s ease;
        }

        .btn-filter-submit:hover {
            background: #0b6b48;
        }

        .btn-filter-reset {
            height: 38px;
            padding: 0 14px;
            background: #f3f4f6;
            color: #4b5563;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-filter-reset:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        /* Tabel Arsip SK */
        .arsip-table-wrap {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow-x: auto;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.03);
        }

        table.arsip-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            min-width: 820px;
        }

        table.arsip-table thead th {
            background: linear-gradient(180deg, #0b3d2e 0%, #064e3b 100%);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            text-align: left;
            padding: 13px 16px;
            white-space: nowrap;
        }

        table.arsip-table thead th:first-child {
            width: 50px;
            text-align: center;
        }

        table.arsip-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
            color: #374151;
        }

        table.arsip-table tbody tr:hover {
            background: #f7faf9;
        }

        table.arsip-table tbody tr:last-child td {
            border-bottom: none;
        }

        .no-sk-badge {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            color: #046a48;
            background: #e6f6ee;
            border: 1px solid #bce6d2;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 12px;
            display: inline-block;
        }

        .badge-pill {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .badge-penelitian {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .badge-pengabdian {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-simlitabkes {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .badge-mandiri {
            background: #f3e8ff;
            color: #6b21a8;
            border: 1px solid #e9d5ff;
        }

        /* Tombol File Action */
        .btn-file-action {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 11px;
            border-radius: 7px;
            font-size: 11.5px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-preview {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .btn-preview:hover {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-download {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .btn-download:hover {
            background: #d1fae5;
            color: #065f46;
        }

        /* Pagination custom wrapper */
        .pagination-container {
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #6b7280;
            flex-wrap: wrap;
            gap: 10px;
        }
    </style>

    <div class="arsip-card">
        {{-- Header Halaman --}}
        <div class="arsip-header">
            <h2>
                <span>🗎</span> Arsip Surat Keputusan (SK)
            </h2>
            <p>Daftar Surat Keputusan (SK) kegiatan penelitian dan pengabdian masyarakat resmi Poltekkes Kemenkes Medan.</p>
        </div>

        {{-- Form Filter Data --}}
        <div class="filter-panel">
            <form method="GET" action="{{ route('arsip-sk.index') }}" id="filterForm">
                <div class="filter-grid">
                    {{-- Filter Tahun --}}
                    <div class="filter-item">
                        <label for="filter_tahun">Tahun</label>
                        <select name="tahun" id="filter_tahun" class="filter-select">
                            <option value="">-- Semua Tahun --</option>
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ (string)$filterTahun === (string)$y ? 'selected' : '' }}>
                                    Tahun {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Jenis Kegiatan --}}
                    <div class="filter-item">
                        <label for="filter_jenis">Jenis Kegiatan</label>
                        <select name="jenis" id="filter_jenis" class="filter-select">
                            <option value="">-- Semua Jenis --</option>
                            <option value="penelitian" {{ $filterJenis === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="pengabdian" {{ $filterJenis === 'pengabdian' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
                        </select>
                    </div>

                    {{-- Filter Jalur Pengajuan --}}
                    <div class="filter-item">
                        <label for="filter_jalur">Jalur Pengajuan</label>
                        <select name="jalur" id="filter_jalur" class="filter-select">
                            <option value="">-- Semua Jalur --</option>
                            <option value="simlitabkes" {{ $filterJalur === 'simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
                            <option value="mandiri" {{ $filterJalur === 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                        </select>
                    </div>

                    {{-- Filter Skema Dinamis --}}
                    <div class="filter-item">
                        <label for="filter_skema">Skema</label>
                        <select name="skema_id" id="filter_skema" class="filter-select">
                            <option value="">-- Semua Skema --</option>
                        </select>
                    </div>

                    {{-- Pencarian No SK / Keterangan --}}
                    <div class="filter-item">
                        <label for="filter_q">Cari No SK</label>
                        <input type="text" name="q" id="filter_q" class="filter-input" placeholder="Ketik nomor SK..." value="{{ $q }}">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="filter-actions">
                        <button type="submit" class="btn-filter-submit">
                            🔍 Filter
                        </button>
                        @if ($filterTahun || $filterJenis || $filterJalur || $filterSkema || $q)
                            <a href="{{ route('arsip-sk.index') }}" class="btn-filter-reset" title="Reset filter">
                                ↺ Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Data Arsip SK --}}
        <div class="arsip-table-wrap">
            <table class="arsip-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. SK</th>
                        <th>Tahun</th>
                        <th>Jenis Kegiatan</th>
                        <th>Jalur</th>
                        <th>Skema</th>
                        <th>Keterangan</th>
                        <th style="text-align:center; width:190px;">File SK</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arsipSks as $index => $item)
                        <tr>
                            <td style="text-align:center; font-weight:600; color:#9ca3af;">
                                {{ $arsipSks->firstItem() + $index }}
                            </td>
                            <td>
                                <span class="no-sk-badge">{{ $item->no_sk }}</span>
                            </td>
                            <td style="font-weight:700; color:#111827;">
                                {{ $item->tahun }}
                            </td>
                            <td>
                                @if ($item->jenis === 'penelitian')
                                    <span class="badge-pill badge-penelitian">Penelitian</span>
                                @else
                                    <span class="badge-pill badge-pengabdian">Pengabdian</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->jalur === 'simlitabkes')
                                    <span class="badge-pill badge-simlitabkes">Simlitabkes</span>
                                @else
                                    <span class="badge-pill badge-mandiri">Mandiri</span>
                                @endif
                            </td>
                            <td style="font-weight:600; color:#1f2937;">
                                {{ $item->skema->nama ?? '-' }}
                            </td>
                            <td style="font-size:12px; color:#4b5563; max-width:200px;">
                                {{ $item->keterangan ?: '-' }}
                            </td>
                            <td style="text-align:center;">
                                <div style="display:inline-flex; gap:6px; justify-content:center;">
                                    {{-- Preview File PDF --}}
                                    <a href="{{ route('arsip-sk.preview', $item->id) }}" target="_blank"
                                        class="btn-file-action btn-preview" title="Preview Dokumen PDF">
                                        👁 Preview
                                    </a>
                                    {{-- Download File PDF --}}
                                    <a href="{{ route('arsip-sk.download', $item->id) }}"
                                        class="btn-file-action btn-download" title="Download File PDF">
                                        ⬇ Unduh
                                    </a>
                                </div>
                                <div style="font-size:10.5px; color:#9ca3af; margin-top:3px;">
                                    PDF &middot; {{ $item->fileSizeFormatted() }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:38px 20px; color:#9ca3af;">
                                <div style="font-size:32px; margin-bottom:8px;">📭</div>
                                <div style="font-weight:700; font-size:14px; color:#4b5563;">Tidak ada Arsip SK ditemukan</div>
                                <div style="font-size:12px; margin-top:3px;">
                                    @if ($filterTahun || $filterJenis || $filterJalur || $filterSkema || $q)
                                        Tidak ada dokumen yang sesuai dengan filter pencarian Anda.
                                        <div style="margin-top:10px;">
                                            <a href="{{ route('arsip-sk.index') }}" class="btn-filter-reset" style="display:inline-flex;">Reset Filter</a>
                                        </div>
                                    @else
                                        Belum ada dokumen Surat Keputusan (SK) yang diunggah oleh admin.
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($arsipSks->hasPages())
            <div class="pagination-container">
                <div>
                    Menampilkan <strong>{{ $arsipSks->firstItem() ?? 0 }}</strong> - <strong>{{ $arsipSks->lastItem() ?? 0 }}</strong> dari <strong>{{ $arsipSks->total() }}</strong> arsip
                </div>
                <div>
                    {{ $arsipSks->links() }}
                </div>
            </div>
        @endif
    </div>

    {{-- Script Dynamic Skema Selection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const allSkemas = @json($skemas);
            const selectJalur = document.getElementById('filter_jalur');
            const selectJenis = document.getElementById('filter_jenis');
            const selectSkema = document.getElementById('filter_skema');
            const selectedSkemaId = "{{ $filterSkema }}";

            function renderSkemaOptions() {
                const jalurVal = (selectJalur.value || '').trim().toLowerCase();
                const jenisVal = (selectJenis.value || '').trim().toLowerCase();

                // Kosongkan opsi sebelumnya
                selectSkema.innerHTML = '<option value="">-- Semua Skema --</option>';

                // Filter skema sesuai jalur (dan jenis jika dipilih)
                let filtered = allSkemas;

                if (jalurVal) {
                    filtered = filtered.filter(item => (item.jalur || '').trim().toLowerCase() === jalurVal);
                }

                if (jenisVal) {
                    filtered = filtered.filter(item => (item.jenis || '').trim().toLowerCase() === jenisVal);
                }

                filtered.forEach(function (skema) {
                    const opt = document.createElement('option');
                    opt.value = skema.id;
                    const jalurLabel = skema.jalur ? ' (' + skema.jalur.toUpperCase() + ')' : '';
                    opt.textContent = skema.nama + jalurLabel;
                    if (String(skema.id) === String(selectedSkemaId)) {
                        opt.selected = true;
                    }
                    selectSkema.appendChild(opt);
                });
            }

            // Inisialisasi awal saat load
            renderSkemaOptions();

            // Event listener saat jalur atau jenis berubah
            selectJalur.addEventListener('change', renderSkemaOptions);
            selectJenis.addEventListener('change', renderSkemaOptions);
        });
    </script>
@endsection

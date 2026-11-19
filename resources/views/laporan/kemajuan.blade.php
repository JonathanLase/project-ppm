@extends('layouts.app')

@section('title', 'Laporan Kemajuan')
@section('crumbs', 'Menu Dosen / Laporan Kemajuan')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .lk-table-wrap {
            margin-top: 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
        }

        table.lk-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }

        table.lk-table thead th {
            background: linear-gradient(180deg, #00875A 0%, #046a48 100%);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            text-align: left;
            padding: 14px 16px;
            border-bottom: 1px solid #036b4a;
            white-space: nowrap;
        }

        table.lk-table thead th:first-child {
            width: 48px;
            text-align: center;
            border-top-left-radius: 12px;
        }

        table.lk-table thead th:last-child {
            text-align: right;
            border-top-right-radius: 12px;
        }

        table.lk-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f3f5;
            vertical-align: middle;
            color: #1f2937;
        }

        table.lk-table tbody tr:last-child td {
            border-bottom: none;
        }

        table.lk-table tbody tr:hover {
            background: #f7faf9;
        }

        table.lk-table td:first-child {
            text-align: center;
            color: #9ca3af;
            font-weight: 600;
        }

        table.lk-table td:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .lk-judul {
            font-weight: 600;
            color: #111827;
            display: block;
        }

        .lk-kode {
            font-size: 11.5px;
            color: #6b7280;
            font-variant-numeric: tabular-nums;
        }

        .lk-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
        }

        .lk-badge.belum {
            background: #f1f5f9;
            color: #64748b;
        }

        .lk-badge.draft {
            background: #fef3c7;
            color: #b45309;
        }

        .lk-badge.proses {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .lk-badge.disetujui {
            background: #e6f4ee;
            color: #00875A;
        }

        .lk-badge.revisi {
            background: #fee2e2;
            color: #dc2626;
        }

        .lk-actions {
            display: inline-flex;
            gap: 6px;
        }

        .lk-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid transparent;
            white-space: nowrap;
            transition: all 0.15s ease;
        }

        .lk-btn svg {
            width: 13px;
            height: 13px;
            flex-shrink: 0;
        }

        /* Aksi utama: ajukan / lanjutkan mengisi */
        .lk-btn-primary {
            background: linear-gradient(180deg, #00875A 0%, #046a48 100%);
            color: #fff;
            box-shadow: 0 1px 2px rgba(0, 135, 90, 0.3);
        }

        .lk-btn-primary:hover {
            box-shadow: 0 3px 8px rgba(0, 135, 90, 0.4);
            transform: translateY(-1px);
        }

        /* Aksi edit revisi: kuning/amber, beda dari primary hijau */
        .lk-btn-warning {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
            color: #fff;
            box-shadow: 0 1px 2px rgba(217, 119, 6, 0.3);
        }

        .lk-btn-warning:hover {
            box-shadow: 0 3px 8px rgba(217, 119, 6, 0.4);
            transform: translateY(-1px);
        }

        /* Detail / lihat: biru-ungu lembut, netral tapi tetap berwarna */
        .lk-btn-detail {
            background: #eef2ff;
            color: #4338ca;
            border-color: #e0e7ff;
        }

        .lk-btn-detail:hover {
            background: #e0e7ff;
            border-color: #c7d2fe;
            transform: translateY(-1px);
        }

        /* Filter Bar */
        .lk-filter-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .lk-filter-search {
            position: relative;
            flex: 1;
            min-width: 220px;
        }

        .lk-filter-search svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 15px;
            height: 15px;
            color: #9ca3af;
            pointer-events: none;
        }

        .lk-filter-search input {
            width: 100%;
            padding: 9px 12px 9px 34px;
            border: 1px solid #d8dee3;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: #1f2937;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .lk-filter-search input:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15);
        }

        .lk-filter-year {
            position: relative;
        }

        .lk-filter-year select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            padding: 9px 32px 9px 12px;
            border: 1px solid #d8dee3;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: #1f2937;
            background: #fff;
            cursor: pointer;
            min-width: 150px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .lk-filter-year select:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 3px rgba(0, 135, 90, 0.15);
        }

        .lk-filter-year::after {
            content: '';
            position: absolute;
            top: 50%;
            right: 12px;
            width: 7px;
            height: 7px;
            border-right: 2px solid #6b7280;
            border-bottom: 2px solid #6b7280;
            transform: translateY(-65%) rotate(45deg);
            pointer-events: none;
        }

        .lk-filter-empty {
            text-align: center;
            color: var(--ink-500);
            padding: 28px 0;
            font-size: 13px;
            display: none;
        }
    </style>

    @if (session('success'))
        <div class="alert-box alert-info" style="margin-bottom:16px;">✅ {{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert-box alert-amber" style="margin-bottom:16px;">⚠️ {{ session('error') }}</div>
    @endif

    @if (isset($tanpaKegiatan) && $tanpaKegiatan)
        <div class="card">
            <div style="text-align:center; color:var(--ink-500); padding:24px; font-size:13px;">Belum ada pengajuan
                jalur Simlitabkes yang berada di tahap Laporan Kemajuan / Laporan Hasil.</div>
        </div>
    @else
        <div class="card">
            <h3 style="margin-bottom:2px;">Daftar Kegiatan — Laporan Kemajuan</h3>
            <div class="sub" style="font-size:11.5px; color:var(--ink-500); margin-bottom:14px;">Kegiatan jalur
                Simlitabkes yang sudah lolos validasi proposal admin akan muncul di sini. Klik "Kirim Laporan
                Kemajuan" untuk kegiatan yang belum pernah mengisi laporan.</div>

            @php $tahunTersedia = $daftarKegiatan->pluck('created_at')->map(fn($t) => $t->format('Y'))->unique()->sortDesc()->values(); @endphp

            <div class="lk-filter-row">
                <div class="lk-filter-search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="lkSearchInput" placeholder="Cari judul atau kode kegiatan...">
                </div>
                <div class="lk-filter-year">
                    <select id="lkYearFilter">
                        <option value="">Semua Tahun Diajukan</option>
                        @foreach ($tahunTersedia as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="lk-table-wrap">
                <table class="lk-table" id="lkTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kegiatan</th>
                            <th>Skema</th>
                            <th>Status Validasi</th>
                            <th>Tanggal Upload Pengajuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarKegiatan as $i => $keg)
                            @php $laporan = $laporanByPengajuan->get($keg->id); @endphp
                            <tr data-search="{{ strtolower($keg->judul . ' ' . $keg->kode) }}"
                                data-year="{{ $keg->created_at->format('Y') }}">
                                <td class="lk-row-no">{{ $i + 1 }}</td>
                                <td>
                                    <span class="lk-judul">{{ $keg->judul }}</span>
                                    <span class="lk-kode">{{ $keg->kode }}</span>
                                </td>
                                <td>{{ $keg->skema->nama ?? '-' }}</td>
                                <td>
                                    @if (!$laporan)
                                        <span class="lk-badge belum">Belum Diajukan</span>
                                    @elseif ($laporan->status === 'draft')
                                        <span class="lk-badge draft">Draft</span>
                                    @elseif ($laporan->status === 'proses')
                                        <span class="lk-badge proses">Sedang Diproses</span>
                                    @elseif ($laporan->status === 'disetujui')
                                        <span class="lk-badge disetujui">Disetujui</span>
                                    @elseif ($laporan->status === 'revisi')
                                        <span class="lk-badge revisi">Perlu Direvisi</span>
                                    @endif
                                </td>
                                <td>{{ $keg->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="lk-actions">
                                        @if (!$laporan)
                                            <a href="{{ route('laporan.kemajuan.form', $keg) }}"
                                                class="lk-btn lk-btn-primary">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <line x1="5" y1="12" x2="19" y2="12">
                                                    </line>
                                                </svg>
                                                Kirim Laporan Kemajuan
                                            </a>
                                        @elseif ($laporan->status === 'draft')
                                            <a href="{{ route('laporan.kemajuan.form', $keg) }}"
                                                class="lk-btn lk-btn-primary">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                </svg>
                                                Lanjutkan Mengisi
                                            </a>
                                        @elseif ($laporan->status === 'revisi')
                                            <a href="{{ route('laporan.kemajuan.detail', $keg) }}"
                                                class="lk-btn lk-btn-detail">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                Detail
                                            </a>
                                            <a href="{{ route('laporan.kemajuan.form', $keg) }}"
                                                class="lk-btn lk-btn-warning">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M12 20h9"></path>
                                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        @else
                                            <a href="{{ route('laporan.kemajuan.detail', $keg) }}"
                                                class="lk-btn lk-btn-detail">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                                Detail
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="lk-filter-empty" id="lkFilterEmpty">Tidak ada kegiatan yang cocok dengan pencarian/filter.
                </div>
            </div>
        </div>
    @endif

    <script>
        (function() {
            const searchInput = document.getElementById('lkSearchInput');
            const yearFilter = document.getElementById('lkYearFilter');
            const table = document.getElementById('lkTable');
            const emptyState = document.getElementById('lkFilterEmpty');
            if (!table) return;

            const rows = Array.from(table.querySelectorAll('tbody tr'));

            function applyFilter() {
                const q = (searchInput.value || '').trim().toLowerCase();
                const year = yearFilter.value;
                let visibleCount = 0;
                let no = 1;

                rows.forEach(row => {
                    const matchSearch = !q || row.dataset.search.includes(q);
                    const matchYear = !year || row.dataset.year === year;
                    const show = matchSearch && matchYear;
                    row.style.display = show ? '' : 'none';
                    if (show) {
                        row.querySelector('.lk-row-no').textContent = no++;
                        visibleCount++;
                    }
                });

                emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
                table.style.display = visibleCount === 0 ? 'none' : '';
            }

            searchInput.addEventListener('input', applyFilter);
            yearFilter.addEventListener('change', applyFilter);
        })();
    </script>
@endsection

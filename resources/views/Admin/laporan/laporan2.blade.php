laporan2.blade.php

@extends('layouts.admin')
@section('title', 'Laporan 2 - Detail Penelitian & Luaran')

@section('content')
    <style>
        .l2-header-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 4px 24px
        }

        .l2-header-left {
            display: flex;
            align-items: center;
            gap: 16px
        }

        .l2-header-icon {
            width: 52px;
            height: 52px;
            background: #f0faf4;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #1a6b3c;
            flex-shrink: 0
        }

        .l2-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 3px
        }

        .l2-bread {
            font-size: 12px;
            color: #999;
            margin: 0
        }

        .l2-bread span {
            color: #1a6b3c;
            font-weight: 600
        }

        .btn-xls-solid {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            background: linear-gradient(135deg, #1a6b3c, #27ae60);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(26, 107, 60, .3)
        }

        .btn-xls-solid:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26, 107, 60, .4)
        }

        .btn-pdf-ol {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            border: 2px solid #e74c3c;
            background: white;
            color: #e74c3c;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: all .2s
        }

        .btn-pdf-ol:hover {
            background: #e74c3c;
            color: white;
            transform: translateY(-1px)
        }

        .l2-filter {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden
        }

        .l2-filter-bg {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 90px;
            color: #27ae60;
            opacity: .06;
            pointer-events: none
        }

        .l2-fh {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px
        }

        .l2-fh-ic {
            width: 38px;
            height: 38px;
            background: #e8f5e9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #1a6b3c
        }

        .l2-fh-t {
            font-size: 15px;
            font-weight: 700;
            color: #1a6b3c;
            margin: 0 0 2px
        }

        .l2-fh-s {
            font-size: 12px;
            color: #888;
            margin: 0
        }

        .l2-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #555;
            margin-bottom: 6px
        }

        .siw {
            position: relative
        }

        .siw .siw-ic {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #bbb;
            pointer-events: none;
            z-index: 2
        }

        .siw select {
            width: 100%;
            padding: 11px 36px 11px 38px;
            border: 1.5px solid #e8e8e8;
            border-radius: 12px;
            font-size: 13px;
            color: #444;
            background: white;
            appearance: none;
            cursor: pointer;
            transition: border-color .2s, box-shadow .2s
        }

        .siw select:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, .12)
        }

        .siw .siw-cv {
            position: absolute;
            right: 13px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #bbb;
            pointer-events: none
        }

        .btn-cari {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 28px;
            background: linear-gradient(135deg, #1a6b3c, #27ae60);
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(26, 107, 60, .3)
        }

        .btn-cari:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(26, 107, 60, .4);
            color: white
        }

        .btn-reset {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 11px 22px;
            background: white;
            color: #555;
            border: 1.5px solid #e0e0e0;
            border-radius: 12px;
            font-size: 13px;
            cursor: pointer;
            transition: all .2s;
            text-decoration: none
        }

        .btn-reset:hover {
            border-color: #bbb;
            background: #f8f8f8;
            color: #333
        }

        .sum-card {
            background: white;
            border-radius: 16px;
            padding: 24px 28px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .06);
            margin-bottom: 20px
        }

        .sum-hd {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px
        }

        .sum-hd-ic {
            width: 36px;
            height: 36px;
            background: #e8f5e9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: #1a6b3c
        }

        .sum-hd-t {
            font-size: 15px;
            font-weight: 700;
            color: #1a6b3c;
            margin: 0 0 2px
        }

        .sum-hd-s {
            font-size: 12px;
            color: #888;
            margin: 0
        }

        .mini-st {
            background: white;
            border-radius: 14px;
            padding: 16px 18px;
            border: 1.5px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all .2s
        }

        .mini-st:hover {
            border-color: #ddd;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .08);
            transform: translateY(-2px)
        }

        .ms-ic {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0
        }

        .ms-g {
            background: #e8f5e9;
            color: #1a6b3c
        }

        .ms-b {
            background: #e8f0fd;
            color: #3949ab
        }

        .ms-p {
            background: #f3e5f5;
            color: #7b1fa2
        }

        .ms-o {
            background: #fff3e0;
            color: #e65100
        }

        .ms-t {
            background: #e0f2f1;
            color: #00695c
        }

        .ms-num {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a2e;
            line-height: 1
        }

        .ms-lbl {
            font-size: 11px;
            color: #888;
            margin-top: 2px;
            font-weight: 500
        }

        .tbl-card2 {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 14px rgba(0, 0, 0, .07);
            overflow: hidden
        }

        .tbl-hd2 {
            padding: 18px 24px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .tbl-hd2-t {
            font-size: 15px;
            font-weight: 700;
            color: #1a1a2e;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .tbl-hd2-ic {
            width: 36px;
            height: 36px;
            background: #e8f5e9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #1a6b3c
        }

        .tbl-cnt {
            background: #e8f5e9;
            color: #1a6b3c;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600
        }

        .l2-table {
            font-size: 12.5px
        }

        .l2-table thead th {
            background: #fafafa;
            color: #777;
            font-weight: 600;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 14px;
            border-top: none;
            border-bottom: 2px solid #f0f0f0;
            white-space: nowrap
        }

        .l2-table tbody tr {
            border-bottom: 1px solid #f7f7f7;
            transition: background .15s
        }

        .l2-table tbody tr:hover {
            background: #fafffe
        }

        .l2-table tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f7f7f7
        }

        .l2-table tbody tr:last-child td {
            border-bottom: none
        }

        .av-k {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1a6b3c, #27ae60);
            color: white;
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .av-a {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: #e8f5e9;
            color: #1a6b3c;
            font-size: 9px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center
        }

        .p2 {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600
        }

        .p2-pen {
            background: #e8f5e9;
            color: #1a6b3c
        }

        .p2-peng {
            background: #e8f0fd;
            color: #3949ab
        }

        .p2-sim {
            background: #fff3e0;
            color: #e65100
        }

        .p2-man {
            background: #f3e5f5;
            color: #7b1fa2
        }

        .p2-thn {
            background: #f1f8e9;
            color: #33691e;
            border-radius: 8px
        }

        .lb-box {
            background: #f8fffe;
            border: 1px solid #c8e6c9;
            border-left: 3px solid #27ae60;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 11px;
            line-height: 1.5;
            color: #2d4a2d
        }

        .lb-empty {
            font-size: 11px;
            color: #ccc;
            font-style: italic
        }

        .btn-lap {
            width: 32px;
            height: 32px;
            background: #e8f5e9;
            color: #1a6b3c;
            border: none;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all .2s;
            text-decoration: none
        }

        .btn-lap:hover {
            background: #1a6b3c;
            color: white;
            transform: scale(1.1)
        }

        .pg-wrap {
            padding: 16px 24px;
            border-top: 1px solid #f5f5f5;
            background: #fafffe;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px
        }

        .pg-info {
            font-size: 12px;
            color: #888
        }

        .pg-info b {
            color: #1a6b3c
        }

        .empty-box {
            padding: 64px 20px;
            text-align: center
        }
    </style>

    <div class="container-fluid px-2">

        {{-- HEADER --}}
        <div class="l2-header-box">
            <div class="l2-header-left">
                <div class="l2-header-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                <div>
                    <div class="l2-title">Laporan 2 – Detail Penelitian & Luaran</div>
                    <div class="l2-bread">Dashboard &rsaquo; Laporan &rsaquo; <span>Laporan 2</span></div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.laporan.laporan2.export.excel', request()->query()) }}" class="btn-xls-solid">
                    <i class="bi bi-file-earmark-excel-fill" style="font-size:18px"></i> Export Excel
                </a>
                <a href="{{ route('admin.laporan.laporan2.export.pdf', request()->query()) }}" class="btn-pdf-ol">
                    <i class="bi bi-file-earmark-pdf-fill" style="font-size:18px"></i> Export PDF
                </a>
            </div>
        </div>

        {{-- FILTER --}}
        <div class="l2-filter">
            <i class="bi bi-search l2-filter-bg"></i>
            <div class="l2-fh">
                <div class="l2-fh-ic"><i class="bi bi-sliders2-vertical"></i></div>
                <div>
                    <div class="l2-fh-t">Filter Data</div>
                    <div class="l2-fh-s">Gunakan filter di bawah untuk mencari data yang diinginkan</div>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.laporan.laporan2') }}">
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="l2-lbl">Tahun</div>
                        <div class="siw">
                            <i class="bi bi-calendar3 siw-ic"></i>
                            <select name="tahun">
                                <option value="">Pilih Tahun</option>
                                @foreach ($tahunList as $t)
                                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>
                                        {{ $t }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down siw-cv"></i>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="l2-lbl">Jurusan</div>
                        <div class="siw">
                            <i class="bi bi-building siw-ic"></i>
                            <select name="jurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach ($jurusanList as $j)
                                    <option value="{{ $j }}" {{ request('jurusan') == $j ? 'selected' : '' }}>
                                        {{ $j }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down siw-cv"></i>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="l2-lbl">Jenis Kegiatan</div>
                        <div class="siw">
                            <i class="bi bi-list-ul siw-ic"></i>
                            <select name="jenis">
                                <option value="">Semua Jenis Kegiatan</option>
                                @foreach ($jenisList as $val => $label)
                                    <option value="{{ $val }}" {{ request('jenis') == $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down siw-cv"></i>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="l2-lbl">Jalur</div>
                        <div class="siw">
                            <i class="bi bi-signpost-2 siw-ic"></i>
                            <select name="jalur">
                                <option value="">Semua Jalur</option>
                                @foreach ($jalurList as $val => $label)
                                    <option value="{{ $val }}" {{ request('jalur') == $val ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down siw-cv"></i>
                        </div>
                    </div>
                </div>
                <div class="row g-3 align-items-end">
                    <div class="col-6 col-md-3">
                        <div class="l2-lbl">Skema</div>
                        <div class="siw">
                            <i class="bi bi-layers siw-ic"></i>
                            <select name="skema_id">
                                <option value="">Semua Skema</option>
                                @foreach ($skemaList as $s)
                                    <option value="{{ $s->id }}" {{ request('skema_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->nama }}</option>
                                @endforeach
                            </select>
                            <i class="bi bi-chevron-down siw-cv"></i>
                        </div>
                    </div>
                    <div class="col-6 col-md-9 d-flex align-items-end justify-content-end gap-2">
                        <button type="submit" class="btn-cari"><i class="bi bi-search"></i> Cari</button>
                        @if (request()->hasAny(['tahun', 'jurusan', 'jenis', 'jalur', 'skema_id']))
                            <a href="{{ route('admin.laporan.laporan2') }}" class="btn-reset">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- SUMMARY --}}
        @php
            $totalKegiatan = $data->total();
            $totalPeneliti =
                $data->getCollection()->map(fn($p) => $p->pegawai_id)->unique()->count() +
                $data->getCollection()->flatMap(fn($p) => $p->anggotas->pluck('pegawai_id'))->unique()->count();
            $totalLuaran = $data->getCollection()->filter(fn($p) => $p->laporanKemajuan)->count();
            $totalSelesai = $data->getCollection()->filter(fn($p) => $p->status === 'disetujui')->count();
            $totalJurusan = $data->getCollection()->map(fn($p) => $p->pegawai?->jurusan)->filter()->unique()->count();
        @endphp
        <div class="sum-card">
            <div class="sum-hd">
                <div class="sum-hd-ic"><i class="bi bi-pie-chart-fill"></i></div>
                <div>
                    <div class="sum-hd-t">Ringkasan Data</div>
                    <div class="sum-hd-s">Ringkasan data berdasarkan filter yang dipilih</div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md">
                    <div class="mini-st">
                        <div class="ms-ic ms-g"><i class="bi bi-folder2-open"></i></div>
                        <div>
                            <div class="ms-num">{{ $totalKegiatan }}</div>
                            <div class="ms-lbl">Total Kegiatan</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="mini-st">
                        <div class="ms-ic ms-b"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <div class="ms-num">{{ $totalPeneliti }}</div>
                            <div class="ms-lbl">Total Peneliti</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="mini-st">
                        <div class="ms-ic ms-p"><i class="bi bi-file-earmark-text"></i></div>
                        <div>
                            <div class="ms-num">{{ $totalLuaran }}</div>
                            <div class="ms-lbl">Total Luaran</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="mini-st">
                        <div class="ms-ic ms-o"><i class="bi bi-building"></i></div>
                        <div>
                            <div class="ms-num">{{ $totalJurusan }}</div>
                            <div class="ms-lbl">Total Jurusan</div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md">
                    <div class="mini-st">
                        <div class="ms-ic ms-t"><i class="bi bi-patch-check-fill"></i></div>
                        <div>
                            <div class="ms-num">{{ $totalSelesai }}</div>
                            <div class="ms-lbl">Disetujui</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="tbl-card2">
            <div class="tbl-hd2">
                <div class="tbl-hd2-t">
                    <div class="tbl-hd2-ic"><i class="bi bi-table"></i></div>
                    Daftar Penelitian & Pengabdian
                </div>
                <span class="tbl-cnt">{{ $data->total() }} data</span>
            </div>

            @if ($data->isEmpty())
                <div class="empty-box">
                    <div style="font-size:48px;margin-bottom:14px;">📭</div>
                    <h6 class="fw-bold text-muted">Tidak ada data ditemukan</h6>
                    <p class="text-muted small">Coba ubah atau reset filter</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table l2-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4" width="45">No</th>
                                <th width="68">Tahun</th>
                                <th>Judul</th>
                                <th width="155">Ketua Peneliti</th>
                                <th width="130">Jurusan</th>
                                <th width="140">Anggota</th>
                                <th class="text-center" width="88">Jenis</th>
                                <th class="text-center" width="100">Jalur</th>
                                <th width="110">Skema</th>
                                <th width="170">Luaran Diusulkan</th>
                                <th width="170">Luaran Tercapai</th>
                                <th class="text-center" width="58">Lap.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $i => $p)
                                @php
                                    $lk = $p->laporanKemajuan;
                                    $toStr = function ($val): string {
                                        if (is_null($val)) {
                                            return '';
                                        }
                                        if (is_string($val)) {
                                            return $val;
                                        }
                                        if (is_array($val)) {
                                            return implode(
                                                '; ',
                                                array_map(function ($item) {
                                                    if (is_array($item)) {
                                                        return $item['nama'] ??
                                                            ($item['jenis'] ?? ($item['judul'] ?? json_encode($item)));
                                                    }
                                                    return (string) $item;
                                                }, $val),
                                            );
                                        }
                                        return (string) $val;
                                    };
                                    $dis = $lk
                                        ? $toStr($lk->luaran_diusulkan ?? ($lk->target_luaran ?? ($lk->luaran ?? null)))
                                        : '';
                                    $ter = $lk
                                        ? $toStr(
                                            $lk->luaran_tercapai ?? ($lk->capaian_luaran ?? ($lk->capaian ?? null)),
                                        )
                                        : '';
                                    $tval = $p->{$kolomTahun} ?? ($p->created_at?->year ?? '-');
                                @endphp
                                <tr>
                                    <td class="ps-4 text-muted fw-semibold">{{ $data->firstItem() + $i }}</td>
                                    <td><span class="p2 p2-thn px-2">{{ $tval }}</span></td>
                                    <td>
                                        <div class="fw-semibold" style="color:#1a1a2e;line-height:1.4"
                                            title="{{ $p->judul }}">{{ Str::limit($p->judul, 60) }}</div>
                                        <small class="text-muted">{{ $p->kode }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="av-k">{{ strtoupper(substr($p->pegawai?->nama ?? 'X', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-semibold" style="font-size:12px;line-height:1.3">
                                                    {{ $p->pegawai?->nama ?? '-' }}</div>
                                                <small class="text-muted">{{ $p->pegawai?->nidn ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span
                                            style="font-size:12px;color:#1a6b3c;font-weight:600">{{ $p->pegawai?->jurusan ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @forelse($p->anggotas as $a)
                                            <div class="d-flex align-items-center gap-1 mb-1">
                                                <div class="av-a">{{ strtoupper(substr($a->pegawai?->nama ?? 'X', 0, 1)) }}
                                                </div>
                                                <small style="font-size:11px">{{ $a->pegawai?->nama ?? '-' }}</small>
                                            </div>
                                        @empty<small class="text-muted">-</small>
                                        @endforelse
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="p2 {{ $p->jenis === 'penelitian' ? 'p2-pen' : 'p2-peng' }}">{{ ucfirst($p->jenis ?? '-') }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="p2 {{ $p->jalur === 'mandiri' ? 'p2-man' : 'p2-sim' }}">{{ ucfirst($p->jalur ?? '-') }}</span>
                                    </td>
                                    <td><small class="text-muted">{{ $p->skema?->nama ?? '-' }}</small></td>
                                    <td>
                                        @if ($lk && $dis)
                                            <div class="lb-box">{{ Str::limit($dis, 110) }}</div>
                                        @else
                                            <span class="lb-empty"><i
                                                    class="bi bi-dash-circle me-1"></i>{{ $lk ? 'Belum diisi' : 'Belum ada laporan' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($lk && $ter)
                                            <div class="lb-box" style="border-left-color:#2ecc71">
                                                {{ Str::limit($ter, 110) }}</div>
                                        @elseif($lk)
                                            <span class="lb-empty"><i class="bi bi-hourglass-split me-1"
                                                    style="color:#f39c12"></i>Belum diisi</span>
                                        @else
                                            <span class="lb-empty"><i class="bi bi-dash"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($lk)
                                            <a href="{{ route('admin.laporan-kemajuan.dokumen', $p->id) }}"
                                                class="btn-lap" target="_blank" title="Lap. Kemajuan">
                                                <i class="bi bi-file-earmark-text"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pg-wrap">
                    <div class="pg-info">
                        Menampilkan <b>{{ $data->firstItem() }}</b>–<b>{{ $data->lastItem() }}</b>
                        dari <b>{{ $data->total() }}</b> data
                    </div>
                    {{ $data->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection

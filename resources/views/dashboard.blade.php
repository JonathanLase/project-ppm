@extends('layouts.app')

@section('title', 'Dashboard')
@section('crumbs', 'Dashboard')

@section('content')
    <style>
        /* ══════════════════════════════════════════════════════════
                   DASHBOARD DOSEN — tema hijau & putih Poltekkes Kemenkes Medan
                   elegan, profesional, konsisten dengan identitas institusi
                ══════════════════════════════════════════════════════════ */
        :root {
            --db-950: #062e22;
            --db-900: #0b3d2e;
            --db-800: #0f5132;
            --db-700: #0f766e;
            --db-600: #00875A;
            --db-500: #10b981;
            --db-400: #34d399;
            --db-100: #d1fae5;
            --db-50: #ecfdf5;
            --db-ink: #0f172a;
            --db-line: #eef2f0;
        }

        * {
            box-sizing: border-box;
        }

        /* ── HERO / WELCOME BANNER ── */
        .db-hero {
            position: relative;
            overflow: hidden;
            background: linear-gradient(125deg, var(--db-950) 0%, var(--db-900) 40%, var(--db-600) 100%);
            border-radius: 22px;
            padding: 32px 34px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            flex-wrap: wrap;
            box-shadow: 0 16px 38px -16px rgba(6, 46, 34, .55);
        }

        .db-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(520px 220px at 88% -10%, rgba(255, 255, 255, .12), transparent 60%),
                radial-gradient(360px 200px at 105% 105%, rgba(16, 185, 129, .32), transparent 60%);
            pointer-events: none;
        }

        .db-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 15px;
            margin-bottom: 14px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 999px;
            color: #fff;
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            position: relative;
            z-index: 1;
        }

        .db-hero-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--db-400);
            box-shadow: 0 0 0 3px rgba(52, 211, 153, .3);
        }

        .db-hero-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 8px;
            letter-spacing: -.4px;
            position: relative;
            z-index: 1;
        }

        .db-hero-desc {
            font-size: 12.5px;
            color: rgba(255, 255, 255, .78);
            max-width: 460px;
            line-height: 1.65;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .db-hero-cta {
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 12px 22px;
            background: #fff;
            color: var(--db-800);
            border-radius: 12px;
            font-size: 12.5px;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 10px 22px -8px rgba(0, 0, 0, .35);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .db-hero-cta:hover {
            transform: translateY(-2px);
            color: var(--db-800);
            box-shadow: 0 14px 26px -8px rgba(0, 0, 0, .4);
        }

        .db-hero-cta svg {
            width: 16px;
            height: 16px;
        }

        /* ── STAT CARDS ── */
        .db-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width:1080px) {
            .db-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .db-stat {
            background: linear-gradient(165deg, var(--sc-bg, #f8fafc) 0%, #fff 60%);
            border: 1px solid var(--db-line);
            border-radius: 16px;
            padding: 20px 22px 18px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 3px 14px -8px rgba(15, 23, 42, .08);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .db-stat::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--sc-grad, #94a3b8);
        }

        .db-stat::after {
            content: '';
            position: absolute;
            top: -32px;
            right: -32px;
            width: 104px;
            height: 104px;
            border-radius: 50%;
            background: var(--sc-grad, #94a3b8);
            opacity: .09;
            pointer-events: none;
        }

        .db-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 30px -12px rgba(15, 23, 42, .16);
        }

        .db-stat .top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
            position: relative;
            z-index: 1;
        }

        .db-stat .ic {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            flex: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--sc-grad, #94a3b8);
            box-shadow: 0 6px 14px -4px var(--sc-shadow, rgba(0, 0, 0, .3));
        }

        .db-stat .ic svg {
            width: 19px;
            height: 19px;
        }

        .db-stat .label {
            font-size: 10.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #64748b;
        }

        .db-stat .num {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--sc-num, var(--db-ink));
            line-height: 1;
            position: relative;
            z-index: 1;
            font-variant-numeric: tabular-nums;
        }

        .db-stat.total {
            --sc-grad: linear-gradient(135deg, var(--db-800), var(--db-500));
            --sc-bg: var(--db-50);
            --sc-num: var(--db-800);
            --sc-shadow: rgba(15, 81, 50, .35);
        }

        .db-stat.proses {
            --sc-grad: linear-gradient(135deg, #b45309, #fbbf24);
            --sc-bg: #fffbeb;
            --sc-num: #b45309;
            --sc-shadow: rgba(180, 83, 9, .35);
        }

        .db-stat.disetujui {
            --sc-grad: linear-gradient(135deg, #0f766e, #2dd4bf);
            --sc-bg: #f0fdfa;
            --sc-num: #0f766e;
            --sc-shadow: rgba(15, 118, 110, .35);
        }

        .db-stat.revisi {
            --sc-grad: linear-gradient(135deg, #b91c1c, #f87171);
            --sc-bg: #fef2f2;
            --sc-num: #b91c1c;
            --sc-shadow: rgba(185, 28, 28, .35);
        }

        /* ── PANELS GRID ── */
        .db-panels {
            display: grid;
            grid-template-columns: 1.3fr 1fr 1fr;
            gap: 16px;
            align-items: stretch;
            margin-bottom: 18px;
        }

        @media (max-width:1080px) {
            .db-panels {
                grid-template-columns: 1fr;
            }
        }

        .db-panel {
            background: #fff;
            border: 1px solid var(--db-line);
            border-radius: 16px;
            padding: 20px 22px 22px;
            box-shadow: 0 3px 14px -8px rgba(15, 23, 42, .06);
            display: flex;
            flex-direction: column;
        }

        .db-panel h3 {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--db-ink);
            margin: 0 0 16px;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .db-panel h3 .ic {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            flex: none;
            background: var(--db-50);
            color: var(--db-700);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .db-panel h3 .ic svg {
            width: 15px;
            height: 15px;
        }

        /* ── TABEL PENGAJUAN TERBARU ── */
        .db-mini-table {
            width: 100%;
            border-collapse: collapse;
        }

        .db-mini-table thead th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #9ca3af;
            font-weight: 800;
            text-align: left;
            padding: 0 8px 10px;
            border-bottom: 1px solid var(--db-line);
        }

        .db-mini-table tbody td {
            padding: 11px 8px;
            font-size: 12px;
            border-bottom: 1px solid #f5f6f5;
            color: #1f2937;
            vertical-align: middle;
        }

        .db-mini-table tbody tr:last-child td {
            border-bottom: none;
        }

        .db-mini-table tbody tr {
            transition: background .15s ease;
        }

        .db-mini-table tbody tr:hover {
            background: var(--db-50);
        }

        .db-mini-table .judul {
            font-weight: 700;
            color: #111827;
        }

        .db-jenis-badge {
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 7px;
        }

        .db-jenis-badge.penelitian {
            background: var(--db-50);
            color: var(--db-800);
        }

        .db-jenis-badge.pengabdian {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .db-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .db-badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .db-badge.b-proses {
            background: #fef3c7;
            color: #b45309;
        }

        .db-badge.b-proses .dot {
            background: #f59e0b;
        }

        .db-badge.b-disetujui {
            background: var(--db-100);
            color: var(--db-800);
        }

        .db-badge.b-disetujui .dot {
            background: var(--db-600);
        }

        .db-badge.b-revisi {
            background: #fee2e2;
            color: #b91c1c;
        }

        .db-badge.b-revisi .dot {
            background: #ef4444;
        }

        .db-empty {
            padding: 36px 10px;
            text-align: center;
            color: #9ca3af;
            font-size: 12px;
        }

        .db-empty svg {
            width: 34px;
            height: 34px;
            color: #d1d5db;
            margin-bottom: 8px;
        }

        /* ── TIMELINE STATUS PROSES ── */
        .db-timeline {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .db-timeline li {
            position: relative;
            padding-left: 30px;
            padding-bottom: 22px;
        }

        .db-timeline li:last-child {
            padding-bottom: 0;
        }

        .db-timeline li .node {
            position: absolute;
            left: 0;
            top: 1px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #fff;
            border: 2.5px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .db-timeline li .node svg {
            width: 10px;
            height: 10px;
            color: #fff;
        }

        .db-timeline li::after {
            content: '';
            position: absolute;
            left: 9.5px;
            top: 22px;
            bottom: -6px;
            width: 1.5px;
            background: var(--db-line);
        }

        .db-timeline li:last-child::after {
            display: none;
        }

        .db-timeline li.done .node {
            border-color: var(--db-600);
            background: var(--db-600);
        }

        .db-timeline li.done::after {
            background: var(--db-600);
        }

        .db-timeline li.active .node {
            border-color: var(--db-600);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0, 135, 90, .16);
        }

        .db-timeline li.active .node::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--db-600);
        }

        .db-timeline b {
            font-size: 12.5px;
            color: var(--db-ink);
            display: block;
        }

        .db-timeline li.muted b {
            color: #9ca3af;
            font-weight: 600;
        }

        .db-timeline .t {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* ── PENGUMUMAN ── */
        .db-announce {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .db-announce li {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            padding: 11px 12px;
            border-radius: 10px;
            border-left: 3px solid transparent;
        }

        .db-announce li.unread {
            background: var(--db-50);
            border-left-color: var(--db-600);
        }

        .db-announce .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            margin-top: 5px;
            flex: none;
            background: #d1d5db;
        }

        .db-announce li.unread .dot {
            background: var(--db-600);
            box-shadow: 0 0 0 3px rgba(0, 135, 90, .16);
        }

        .db-announce b {
            font-size: 12px;
            color: #1f2937;
            display: block;
            line-height: 1.45;
            font-weight: 700;
        }

        .db-announce li.muted b {
            color: #6b7280;
            font-weight: 600;
        }

        .db-announce .t {
            font-size: 10.5px;
            color: #9ca3af;
            margin-top: 3px;
        }

        /* ── QUICK ACTIONS ── */
        .db-quick-card {
            background: #fff;
            border: 1px solid var(--db-line);
            border-radius: 16px;
            padding: 20px 22px 22px;
            box-shadow: 0 3px 14px -8px rgba(15, 23, 42, .06);
        }

        .db-quick-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 16px;
        }

        .db-quick-title .ic {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            background: var(--db-50);
            color: var(--db-700);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .db-quick-title .ic svg {
            width: 15px;
            height: 15px;
        }

        .db-quick-title h3 {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--db-ink);
            margin: 0;
        }

        .db-quick {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        @media (max-width:1080px) {
            .db-quick {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .db-quick-btn {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 18px 20px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--db-900), var(--db-600));
            color: #fff;
            text-decoration: none;
            box-shadow: 0 8px 18px -8px rgba(11, 61, 46, .5);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .db-quick-btn::after {
            content: '';
            position: absolute;
            top: -24px;
            right: -24px;
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .db-quick-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 26px -8px rgba(11, 61, 46, .6);
            color: #fff;
        }

        .db-quick-btn .qic {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(255, 255, 255, .16);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
        }

        .db-quick-btn .qic svg {
            width: 17px;
            height: 17px;
        }

        .db-quick-btn .qlabel {
            font-size: 12.5px;
            font-weight: 700;
            line-height: 1.4;
            position: relative;
            z-index: 1;
        }
    </style>

    {{-- ══ HERO / WELCOME BANNER ══ --}}
    <div class="db-hero">
        <div>
            <div class="db-hero-badge"><span class="dot"></span> Panel Peneliti &amp; Pengabdian</div>
            <h1 class="db-hero-title">Selamat Datang, {{ $user->nama ?? 'Dosen' }} 👋</h1>
            <p class="db-hero-desc">
                Pantau status pengajuan proposal, laporan kemajuan, dan luaran penelitian Anda
                secara real-time dari satu panel kontrol.
            </p>
        </div>
        <a href="{{ route('pengajuan.step1') }}" class="db-hero-cta">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                stroke-linejoin="round">
                <circle cx="12" cy="12" r="9" />
                <path d="M12 8v8M8 12h8" />
            </svg>
            Buat Pengajuan Baru
        </a>
    </div>

    {{-- ══ STAT CARDS ══ --}}
    <div class="db-stats">
        <div class="db-stat total">
            <div class="top">
                <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2h9l5 5v13a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z" />
                        <path d="M15 2v5h5" />
                        <path d="M8 13h8M8 17h5" />
                    </svg></div>
                <span class="label">Total Pengajuan</span>
            </div>
            <div class="num">{{ $stats['total'] }}</div>
        </div>
        <div class="db-stat proses">
            <div class="top">
                <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3.5 2" />
                    </svg></div>
                <span class="label">Dalam Proses</span>
            </div>
            <div class="num">{{ $stats['proses'] }}</div>
        </div>
        <div class="db-stat disetujui">
            <div class="top">
                <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M8 12.5l2.6 2.5L16 9.5" />
                    </svg></div>
                <span class="label">Disetujui</span>
            </div>
            <div class="num">{{ $stats['disetujui'] }}</div>
        </div>
        <div class="db-stat revisi">
            <div class="top">
                <div class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg></div>
                <span class="label">Perlu Revisi</span>
            </div>
            <div class="num">{{ $stats['revisi'] }}</div>
        </div>
    </div>

    {{-- ══ PANELS ══ --}}
    <div class="db-panels">
        {{-- Pengajuan Terbaru --}}
        <div class="db-panel">
            <h3>
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z" />
                    </svg></span>
                Pengajuan Terbaru
            </h3>

            @if (($riwayatTerbaru ?? collect())->isEmpty())
                <div class="db-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 7a2 2 0 0 1 2-2h4l2 2h8a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Z" />
                    </svg>
                    <div>Belum ada pengajuan yang tercatat.</div>
                </div>
            @else
                <table class="db-mini-table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Jenis</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($riwayatTerbaru as $r)
                            @php
                                $badgeMap = [
                                    'proses' => ['Dalam Proses', 'b-proses'],
                                    'disetujui' => ['Disetujui', 'b-disetujui'],
                                    'revisi' => ['Direvisi', 'b-revisi'],
                                ];
                                [$label, $class] = $badgeMap[$r['status']] ?? [ucfirst($r['status']), 'b-proses'];
                                $jenisClass = strtolower($r['jenis']) === 'penelitian' ? 'penelitian' : 'pengabdian';
                            @endphp
                            <tr>
                                <td class="judul">{{ $r['judul'] }}</td>
                                <td><span class="db-jenis-badge {{ $jenisClass }}">{{ $r['jenis'] }}</span></td>
                                <td><span class="db-badge {{ $class }}"><span
                                            class="dot"></span>{{ $label }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Status Proses Pengajuan --}}
        <div class="db-panel">
            <h3>
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4v16" />
                        <path d="M4 5h11l-2 3 2 3H4" />
                    </svg></span>
                Status Proses Pengajuan
            </h3>
            <ul class="db-timeline">
                <li class="done">
                    <span class="node"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12l4 4 10-10" />
                        </svg></span>
                    <b>Pengajuan</b>
                    <div class="t">Terkirim</div>
                </li>
                <li class="active">
                    <span class="node"></span>
                    <b>Validasi Admin</b>
                    <div class="t">Sedang diproses</div>
                </li>
                <li class="muted"><span class="node"></span><b>Pelaksanaan Kegiatan</b></li>
                <li class="muted"><span class="node"></span><b>Laporan Kemajuan</b></li>
                <li class="muted"><span class="node"></span><b>Laporan Hasil</b></li>
                <li class="muted"><span class="node"></span><b>Luaran</b></li>
            </ul>
        </div>

        {{-- Pengumuman --}}
        <div class="db-panel">
            <h3>
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 5 2 6 2 6H4s2-1 2-6" />
                        <path d="M10 21a2 2 0 0 0 4 0" />
                    </svg></span>
                Pengumuman
            </h3>
            <ul class="db-announce">
                <li class="unread">
                    <div class="dot"></div>
                    <div>
                        <b>Pengajuan "Sistem Informasi Klinik" diterima</b>
                        <div class="t">16 Mei 2026 &middot; 10:30</div>
                    </div>
                </li>
                <li class="unread">
                    <div class="dot"></div>
                    <div>
                        <b>Laporan kemajuan "AI untuk Kesehatan" sudah divalidasi</b>
                        <div class="t">15 Mei 2026 &middot; 09:48</div>
                    </div>
                </li>
                <li class="muted">
                    <div class="dot"></div>
                    <div>
                        <b>Laporan hasil "Pemberdayaan UMKM" disetujui</b>
                        <div class="t">10 Mei 2026 &middot; 13:20</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    {{-- ══ QUICK ACTIONS ══ --}}
    <div class="db-quick-card">
        <div class="db-quick-title">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 2 4 14h7l-1 8 9-12h-7l1-8z" />
                </svg></span>
            <h3>Aksi Cepat</h3>
        </div>
        <div class="db-quick">
            <a href="{{ route('pengajuan.step1') }}" class="db-quick-btn">
                <span class="qic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg></span>
                <span class="qlabel">Buat Pengajuan Baru</span>
            </a>
            <a href="{{ route('laporan.kemajuan') }}" class="db-quick-btn">
                <span class="qic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 18a4 4 0 0 1-1-7.9A5 5 0 0 1 15.9 8a4 4 0 0 1 1.1 7.9" />
                        <path d="M12 12v8M9 15l3-3 3 3" />
                    </svg></span>
                <span class="qlabel">Upload Laporan Kemajuan</span>
            </a>
            <a href="{{ route('laporan.index', 'hasil') }}" class="db-quick-btn">
                <span class="qic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 18a4 4 0 0 1-1-7.9A5 5 0 0 1 15.9 8a4 4 0 0 1 1.1 7.9" />
                        <path d="M12 12v8M9 15l3-3 3 3" />
                    </svg></span>
                <span class="qlabel">Upload Laporan Hasil</span>
            </a>
            <a href="{{ route('luaran.index') }}" class="db-quick-btn">
                <span class="qic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2l2.9 6.1 6.6.6-5 4.5 1.5 6.6L12 16.9 6 19.8l1.5-6.6-5-4.5 6.6-.6z" />
                    </svg></span>
                <span class="qlabel">Input Luaran</span>
            </a>
        </div>
    </div>
@endsection

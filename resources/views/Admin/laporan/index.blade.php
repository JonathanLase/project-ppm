@extends('layouts.admin')
@section('title', 'Laporan & Rekapitulasi')

@section('content')

    <style>
        /* ══════════════════════════════════════════════════════════
       DESIGN TOKENS — Hijau TUA & BENING (emerald deep, vivid)
    ══════════════════════════════════════════════════════════ */
        :root {
            --br-950: #022c22;
            --br-900: #064e3b;
            --br-800: #065f46;
            --br-700: #047857;
            --br-600: #059669;
            --br-500: #10b981;
            --br-400: #34d399;
            --br-300: #6ee7b7;
            --br-100: #d1fae5;
            --br-50: #ecfdf5;
            --ink: #0f172a;
            --muted: #94a3b8;
            --line: #e2e8f0;
            --orange: #f59e0b;
            --orange-bg: #fff7ed;
            --purple: #8b5cf6;
            --purple-bg: #f5f3ff;
            --red: #ef4444;
            --red-bg: #fef2f2;
            --blue: #3b82f6;
            --blue-bg: #eff6ff;
        }

        * {
            box-sizing: border-box;
        }

        .page-wrap {
            padding: 0 2px;
        }

        /* ══════════════════════════════════════════════════════════
       HERO — hijau tua bening, vivid, tidak pucat
    ══════════════════════════════════════════════════════════ */
        .hero-section {
            position: relative;
            background: linear-gradient(125deg, var(--br-900) 0%, var(--br-800) 35%, var(--br-600) 100%);
            border-radius: 24px;
            padding: 36px 40px;
            margin-bottom: 24px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            box-shadow: 0 18px 42px -14px rgba(4, 78, 57, .55);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(600px 260px at 85% -10%, rgba(255, 255, 255, .14), transparent 60%),
                radial-gradient(400px 220px at 105% 100%, rgba(16, 185, 129, .35), transparent 60%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            flex: 1;
            min-width: 280px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .28);
            color: #fff;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin-bottom: 16px;
        }

        .hero-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--br-300);
            box-shadow: 0 0 0 3px rgba(110, 231, 183, .3);
        }

        .hero-title {
            font-size: 2.15rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 10px;
            letter-spacing: -.5px;
        }

        .hero-desc {
            font-size: 13.5px;
            color: rgba(255, 255, 255, .82);
            max-width: 500px;
            line-height: 1.7;
            margin: 0 0 22px;
        }

        .hero-total-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #fff;
            color: var(--br-800);
            border-radius: 12px;
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 10px 22px -6px rgba(0, 0, 0, .3);
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .hero-total-btn:hover {
            transform: translateY(-2px);
            color: var(--br-800);
            box-shadow: 0 14px 26px -6px rgba(0, 0, 0, .35);
        }

        /* visual kanan */
        .hero-visual {
            position: relative;
            z-index: 2;
            width: 230px;
            height: 170px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 992px) {
            .hero-visual {
                display: none;
            }
        }

        .hero-card {
            width: 168px;
            height: 120px;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 20px 40px -8px rgba(0, 0, 0, .35);
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            gap: 10px;
        }

        .hero-card-bars {
            display: flex;
            align-items: flex-end;
            gap: 5px;
            height: 52px;
        }

        .hero-card-bars span {
            flex: 1;
            border-radius: 4px 4px 0 0;
            background: linear-gradient(180deg, var(--br-400), var(--br-700));
        }

        .hero-card-foot {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .hero-card-foot span {
            height: 5px;
            border-radius: 3px;
            background: #eef2f0;
        }

        .hero-card-foot span:nth-child(1) {
            width: 88%;
        }

        .hero-card-foot span:nth-child(2) {
            width: 65%;
        }

        .hero-card-foot span:nth-child(3) {
            width: 45%;
        }

        .hero-ring {
            position: absolute;
            top: 2px;
            right: 6px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: conic-gradient(var(--br-600) 0deg 250deg, var(--orange) 250deg 310deg, #eef2f0 310deg 360deg);
            border: 5px solid #fff;
            box-shadow: 0 10px 22px rgba(0, 0, 0, .25);
        }

        .hero-chip {
            position: absolute;
            display: flex;
            align-items: center;
            gap: 5px;
            background: #fff;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 10.5px;
            font-weight: 800;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .22);
        }

        .hero-chip-xlsx {
            bottom: 8px;
            left: -10px;
            color: var(--br-800);
        }

        .hero-chip-pdf {
            bottom: -8px;
            right: 18px;
            color: var(--red);
        }

        /* ══════════════════════════════════════════════════════════
       SUB NAV
    ══════════════════════════════════════════════════════════ */
        .lap-subnav {
            display: flex;
            gap: 6px;
            width: fit-content;
            background: #fff;
            padding: 6px;
            border-radius: 14px;
            border: 1px solid var(--line);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            margin-bottom: 24px;
        }

        .lap-subnav a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            color: #8a94a6;
            text-decoration: none;
            transition: all .18s ease;
        }

        .lap-subnav a.active {
            background: linear-gradient(120deg, var(--br-800), var(--br-600));
            color: #fff;
            box-shadow: 0 8px 18px -6px rgba(4, 78, 57, .55);
        }

        .lap-subnav a:not(.active):hover {
            background: var(--br-50);
            color: var(--br-800);
        }

        /* ══════════════════════════════════════════════════════════
       STAT CARDS — versi lebih berwarna: ikon gradasi, aksen kiri
       berwarna, wash gradasi lembut di background, angka ikut berwarna
    ══════════════════════════════════════════════════════════ */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 24px;
        }

        @media (max-width:1200px) {
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:576px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: linear-gradient(165deg, var(--sc-bg, #f8fafc) 0%, #ffffff 60%);
            border-radius: 18px;
            padding: 22px 24px 20px 26px;
            border: 1px solid var(--line);
            box-shadow: 0 4px 16px -8px rgba(15, 23, 42, .08);
            position: relative;
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            background: var(--sc-grad, #94a3b8);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -36px;
            right: -36px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--sc-grad, #94a3b8);
            opacity: .10;
            pointer-events: none;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 38px -14px rgba(15, 23, 42, .18);
        }

        .stat-card-top {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            background: var(--sc-grad, #94a3b8);
            box-shadow: 0 6px 14px -4px var(--sc-shadow, rgba(0, 0, 0, .3));
        }

        .stat-card-lbl {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #64748b;
            line-height: 1.35;
        }

        .stat-card-num {
            font-size: 2.35rem;
            font-weight: 800;
            color: var(--sc-num, var(--ink));
            line-height: 1;
            font-variant-numeric: tabular-nums;
            position: relative;
            z-index: 1;
        }

        .stat-card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px dashed rgba(0, 0, 0, .07);
            position: relative;
            z-index: 1;
        }

        .stat-card-desc {
            font-size: 11px;
            color: #94a3b8;
        }

        .stat-card-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 10px;
            font-weight: 800;
            padding: 4px 12px;
            border-radius: 20px;
            white-space: nowrap;
            color: #fff;
            background: var(--sc-grad, #94a3b8);
        }

        .stat-card-tag::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
            flex-shrink: 0;
            opacity: .9;
        }

        /* Palet — 4 warna cerah tapi tetap kalem/profesional (bukan neon) */
        .sc-1 {
            --sc-grad: linear-gradient(135deg, var(--br-700), var(--br-400));
            --sc-bg: var(--br-50);
            --sc-num: var(--br-800);
            --sc-shadow: rgba(4, 120, 87, .35);
        }

        .sc-2 {
            --sc-grad: linear-gradient(135deg, #1d4ed8, #60a5fa);
            --sc-bg: var(--blue-bg);
            --sc-num: #1d4ed8;
            --sc-shadow: rgba(29, 78, 216, .35);
        }

        .sc-3 {
            --sc-grad: linear-gradient(135deg, #b45309, #fbbf24);
            --sc-bg: var(--orange-bg);
            --sc-num: #b45309;
            --sc-shadow: rgba(180, 83, 9, .35);
        }

        .sc-4 {
            --sc-grad: linear-gradient(135deg, #6d28d9, #c084fc);
            --sc-bg: var(--purple-bg);
            --sc-num: #6d28d9;
            --sc-shadow: rgba(109, 40, 217, .35);
        }


        /* ══════════════════════════════════════════════════════════
       FILTER — flat, stabil
    ══════════════════════════════════════════════════════════ */
        .filter-panel {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: stretch;
        }

        .filter-box {
            flex: 1;
            min-width: 320px;
            background: #fff;
            border-radius: 16px;
            padding: 20px 22px;
            border: 1px solid var(--line);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            display: flex;
            align-items: flex-end;
            gap: 14px;
            flex-wrap: wrap;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 150px;
        }

        .field-label {
            font-size: 10.5px;
            font-weight: 800;
            color: #9aa5b1;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        /* ── SELECT WRAP — sudah tidak pakai animasi lampu keliling lagi ── */
        .select-wrap {
            position: relative;
            border-radius: 12px;
        }

        .field-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            position: relative;
            z-index: 1;
            width: 100%;
            height: 44px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 36px 0 14px;
            font-size: 13px;
            color: #334155;
            cursor: pointer;
            transition: border-color .15s ease, background .15s ease, box-shadow .15s ease;
        }

        .field-select:hover {
            background: #fff;
            border-color: #cbd5e1;
        }

        .field-select:focus {
            outline: none;
            background: #fff;
            border-color: var(--br-600);
            box-shadow: 0 0 0 4px rgba(5, 150, 105, .14);
        }

        .select-caret {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 11px;
            color: #94a3b8;
            pointer-events: none;
            z-index: 2;
        }

        .btn-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 44px;
            padding: 0 26px;
            background: linear-gradient(120deg, var(--br-800), var(--br-600));
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 18px -6px rgba(4, 78, 57, .5);
            transition: transform .15s ease;
            white-space: nowrap;
        }

        .btn-filter:hover {
            transform: translateY(-2px);
            color: #fff;
        }

        .btn-reset {
            width: 44px;
            height: 44px;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            color: #94a3b8;
            font-size: 15px;
            text-decoration: none;
            transition: all .15s ease;
        }

        .btn-reset:hover {
            border-color: var(--red);
            color: var(--red);
            background: var(--red-bg);
        }

        /* export box */
        .export-box {
            min-width: 300px;
            background: #fff;
            border-radius: 16px;
            padding: 18px 22px;
            border: 1px solid var(--line);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .04);
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 10px;
        }

        .export-box-label {
            font-size: 10.5px;
            font-weight: 800;
            color: #cbd5e1;
            text-transform: uppercase;
            letter-spacing: .6px;
        }

        .export-box-btns {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-exp {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 12.5px;
            font-weight: 700;
            text-decoration: none;
            border: 1.5px solid transparent;
            transition: all .15s ease;
            white-space: nowrap;
        }

        .btn-exp-excel-solid {
            background: var(--br-700);
            color: #fff;
            border-color: var(--br-700);
        }

        .btn-exp-excel-solid:hover {
            background: var(--br-800);
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-exp-excel-outline {
            background: #fff;
            color: var(--br-800);
            border-color: var(--br-300);
        }

        .btn-exp-excel-outline:hover {
            background: var(--br-50);
            transform: translateY(-2px);
        }

        .btn-exp-pdf-solid {
            background: var(--red);
            color: #fff;
            border-color: var(--red);
        }

        .btn-exp-pdf-solid:hover {
            background: #c0392b;
            color: #fff;
            transform: translateY(-2px);
        }

        .btn-exp-pdf-outline {
            background: #fff;
            color: var(--red);
            border-color: #fecaca;
        }

        .btn-exp-pdf-outline:hover {
            background: var(--red-bg);
            transform: translateY(-2px);
        }

        /* ══════════════════════════════════════════════════════════
       TABLE CARD — kompak, ada garis kotak, bisa digeser
    ══════════════════════════════════════════════════════════ */
        .tbl-card,
        .tbl-card2 {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--line);
            box-shadow: 0 6px 24px -8px rgba(0, 0, 0, .07);
        }

        .tbl-head-bar,
        .tbl-hd2 {
            padding: 16px 22px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            background: #fafffe;
        }

        .tbl-head-left,
        .tbl-hd2-t {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .tbl-head-icon,
        .tbl-hd2-ic {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--br-50);
            color: var(--br-700);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .tbl-head-title,
        .tbl-hd2-t {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--ink);
        }

        .tbl-head-sub {
            font-size: 11.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        .tbl-badge,
        .tbl-cnt {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: var(--br-50);
            color: var(--br-800);
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }

        .perpage-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 11.5px;
            color: var(--muted);
        }

        .perpage-wrap select {
            padding: 5px 10px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 11.5px;
            color: #475569;
        }

        /* ── SCROLL WRAPPER — bisa digeser horizontal, scrollbar hijau ── */
        .table-scroll {
            overflow-x: auto;
            overflow-y: visible;
            -webkit-overflow-scrolling: touch;
        }

        .table-scroll::-webkit-scrollbar {
            height: 10px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: var(--br-400);
            border-radius: 10px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--br-600);
        }

        /* ── TABEL — border grid rapi, kompak, profesional ── */
        .l1-table {
            font-size: 12.5px;
            margin: 0;
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }

        .l2-table {
            font-size: 11.5px;
            margin: 0;
            width: 100%;
            min-width: 1650px;
            border-collapse: collapse;
        }

        .l1-table thead th,
        .l2-table thead th {
            background: linear-gradient(120deg, var(--br-800), var(--br-600));
            color: #fff;
            font-weight: 700;
            font-size: 10.5px;
            text-transform: uppercase;
            letter-spacing: .6px;
            padding: 13px 14px;
            white-space: nowrap;
            border: 1px solid var(--br-700);
        }

        .l1-table tbody td,
        .l2-table tbody td {
            padding: 14px;
            vertical-align: middle;
            border: 1px solid #eef1f0;
        }

        .l1-table tbody tr.data-row,
        .l2-table tbody tr {
            transition: background .12s ease;
        }

        .l1-table tbody tr.data-row:hover,
        .l2-table tbody tr:hover {
            background: var(--br-50) !important;
        }

        .l1-table tbody tr.row-alt {
            background: #fbfdfc;
        }

        .tfoot-tot {
            background: var(--br-50);
        }

        .tfoot-tot td {
            padding: 13px 14px !important;
            font-weight: 800;
            font-size: 11.5px;
            border: 1px solid var(--br-100) !important;
        }

        /* Jurusan avatar */
        .jur-av {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--br-700), var(--br-400));
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(4, 78, 57, .3);
        }

        /* ── Total cell — angka besar, bersih, tanpa pill warna-warni ── */
        .total-cell {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1px;
        }

        .total-cell-num {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--br-800);
            line-height: 1.1;
        }

        .total-cell-lbl {
            font-size: 9.5px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── Jalur — tag netral dengan titik warna, tidak mencolok ── */
        .jalur-tags {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-start;
        }

        .jalur-tag {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 11px;
            color: #475569;
            font-weight: 600;
            background: #f8fafc;
            border: 1px solid #eef1f0;
            border-radius: 8px;
            padding: 4px 10px 4px 8px;
            white-space: nowrap;
        }

        .jalur-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .jalur-dot-sim {
            background: var(--orange);
        }

        .jalur-dot-man {
            background: var(--purple);
        }

        .jalur-tag b {
            color: var(--ink);
            font-weight: 800;
            margin-left: 2px;
        }

        /* ══════════════════════════════════════════════════════════
       ✅ RINCIAN SKEMA — daftar rapi bergaris, nama tampil penuh
    ══════════════════════════════════════════════════════════ */
        .skema-list {
            display: flex;
            flex-direction: column;
        }

        .skema-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 7px 0;
            border-bottom: 1px dashed #eef1f0;
        }

        .skema-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .skema-row:first-child {
            padding-top: 0;
        }

        .skema-name {
            font-size: 11.5px;
            font-weight: 600;
            color: #334155;
            line-height: 1.45;
        }

        .skema-count {
            flex-shrink: 0;
            background: var(--br-50);
            color: var(--br-800);
            font-weight: 800;
            font-size: 10.5px;
            border-radius: 999px;
            padding: 2px 10px;
            margin-top: 1px;
        }

        .skema-empty {
            font-size: 11px;
            color: #cbd5e1;
            font-style: italic;
        }

        /* ══════════════════════════════════════════════════════════
       LAPORAN 2 TABLE
    ══════════════════════════════════════════════════════════ */
        .av-k {
            width: 30px;
            height: 30px;
            border-radius: 9px;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--br-700), var(--br-400));
            color: #fff;
            font-weight: 800;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 7px rgba(4, 78, 57, .25);
        }

        .av-a {
            width: 18px;
            height: 18px;
            border-radius: 5px;
            background: var(--br-50);
            color: var(--br-800);
            font-size: 8.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .p2 {
            display: inline-flex;
            align-items: center;
            padding: 4px 11px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 700;
        }

        .p2-pen {
            background: var(--br-50);
            color: var(--br-800);
        }

        .p2-peng {
            background: var(--blue-bg);
            color: #2563eb;
        }

        .p2-sim {
            background: var(--orange-bg);
            color: #b45309;
        }

        .p2-man {
            background: var(--purple-bg);
            color: #6d28d9;
        }

        .p2-thn {
            background: #f1f5f9;
            color: #475569;
            border-radius: 8px;
            font-size: 11px;
        }

        /* ✅ ANGGOTA TIM — nama + jurusan, kolom rapi & bisa digeser vertikal */
        .anggota-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            max-width: 190px;
            max-height: 96px;
            overflow-y: auto;
            overflow-x: hidden;
            padding-right: 5px;
        }

        .anggota-list::-webkit-scrollbar {
            width: 5px;
        }

        .anggota-list::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .anggota-list::-webkit-scrollbar-thumb {
            background: var(--br-400);
            border-radius: 10px;
        }

        .anggota-list::-webkit-scrollbar-thumb:hover {
            background: var(--br-600);
        }

        .anggota-item {
            display: flex;
            align-items: center;
            gap: 6px;
            background: #fafffe;
            border: 1px solid var(--br-100);
            border-radius: 8px;
            padding: 4px 8px 4px 3px;
        }

        .anggota-item-txt {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .anggota-item-txt small {
            font-size: 10px;
            font-weight: 600;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 145px;
        }

        .anggota-item-jur {
            font-size: 9px;
            color: #64748b;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 145px;
        }

        .anggota-empty {
            color: #cbd5e1;
            font-size: 10.5px;
        }

        /* Skema — dipertegas, bukan abu-abu samar lagi */
        .skema-txt {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--br-800);
        }

        .lb-box {
            background: var(--br-50);
            border: 1px solid var(--br-100);
            border-left: 3px solid var(--br-600);
            border-radius: 7px;
            padding: 7px 10px;
            font-size: 10.5px;
            line-height: 1.5;
            color: #134e3a;
        }

        .lb-empty {
            font-size: 10.5px;
            color: #cbd5e1;
            font-style: italic;
        }

        /* ══════════════════════════════════════════════════════════
       ✅ LUARAN — daftar rapi, nama jelas, ada info tambahan dosen
    ══════════════════════════════════════════════════════════ */
        .luaran-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-width: 230px;
        }

        .luaran-item {
            background: var(--br-50);
            border: 1px solid var(--br-100);
            border-left: 3px solid var(--br-600);
            border-radius: 7px;
            padding: 6px 10px;
        }

        .luaran-item-name {
            font-size: 10.5px;
            font-weight: 700;
            color: #134e3a;
            line-height: 1.4;
        }

        .luaran-item-opsi {
            display: inline-block;
            margin-top: 3px;
            font-size: 9px;
            font-weight: 700;
            color: var(--br-700);
            background: #fff;
            border: 1px solid var(--br-100);
            border-radius: 6px;
            padding: 1px 7px;
        }

        .luaran-item.is-tercapai {
            border-left-color: #22c55e;
            background: #f0fdf4;
        }

        .luaran-item.is-tercapai .luaran-item-name {
            color: #14532d;
        }

        .luaran-item-real {
            margin-top: 5px;
            padding-top: 5px;
            border-top: 1px dashed #bbf7d0;
            font-size: 9.5px;
            color: #166534;
            line-height: 1.5;
        }

        .luaran-item-real a {
            color: #166534;
            font-weight: 700;
            text-decoration: underline;
        }

        .luaran-tercapai-badge {
            display: inline-block;
            font-size: 8.5px;
            font-weight: 800;
            color: #166534;
            background: #dcfce7;
            border-radius: 5px;
            padding: 1px 6px;
            margin-left: 6px;
            vertical-align: middle;
        }

        .pg-wrap {
            padding: 16px 22px;
            border-top: 1px solid var(--line);
            background: #fafffe;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .pg-info {
            font-size: 11.5px;
            color: var(--muted);
        }

        .pg-info b {
            color: var(--br-800);
        }

        .empty-box {
            padding: 80px 20px;
            text-align: center;
        }

        .empty-box .ic {
            font-size: 54px;
            color: var(--br-100);
            margin-bottom: 14px;
        }

        /* Hint geser tabel */
        .scroll-hint {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: var(--br-700);
            background: var(--br-50);
            padding: 6px 14px;
            border-top: 1px solid var(--br-100);
            font-weight: 600;
        }
    </style>

    <div class="page-wrap">

        {{-- ══ HERO ══ --}}
        <div class="hero-section">
            <div class="hero-content">
                <div class="hero-badge"><span class="dot"></span> Pusat Monitoring Laporan</div>
                <h1 class="hero-title">Laporan & Rekapitulasi</h1>
                <p class="hero-desc">
                    Pantau rekapitulasi penelitian per jurusan dan detail kegiatan tim peneliti
                    secara real-time, lengkap dengan fitur export ke Excel dan PDF.
                </p>
                <a href="#" class="hero-total-btn">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    Total: {{ $totalJurusan }} Jurusan
                </a>
            </div>

            <div class="hero-visual">
                <div class="hero-card">
                    <div class="hero-card-bars">
                        <span style="height:35%"></span>
                        <span style="height:55%"></span>
                        <span style="height:40%"></span>
                        <span style="height:80%"></span>
                        <span style="height:65%"></span>
                        <span style="height:95%"></span>
                    </div>
                    <div class="hero-card-foot"><span></span><span></span><span></span></div>
                </div>
                <div class="hero-ring"></div>
                <div class="hero-chip hero-chip-xlsx"><i class="bi bi-file-earmark-excel-fill"></i> XLSX</div>
                <div class="hero-chip hero-chip-pdf"><i class="bi bi-file-earmark-pdf-fill"></i> PDF</div>
            </div>
        </div>

        {{-- ══ SUB-NAV ══ --}}
        <div class="lap-subnav">
            <a href="{{ route('admin.laporan', ['tab' => 'laporan1']) }}"
                class="{{ $activeTab === 'laporan1' ? 'active' : '' }}">
                <i class="bi bi-bar-chart-steps"></i> Laporan 1 &bull; Rekap per Jurusan
            </a>
            <a href="{{ route('admin.laporan', ['tab' => 'laporan2']) }}"
                class="{{ $activeTab === 'laporan2' ? 'active' : '' }}">
                <i class="bi bi-table"></i> Laporan 2 &bull; Detail Kegiatan & Luaran
            </a>
        </div>

        {{-- ══════════════════════════════════════════
         LAPORAN 1
    ══════════════════════════════════════════ --}}
        @if ($activeTab === 'laporan1')

            {{-- STAT CARDS --}}
            <div class="stat-grid">
                <div class="stat-card sc-1">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                        <div class="stat-card-lbl">Total Pengajuan</div>
                    </div>
                    <div class="stat-card-num">{{ $rekapJurusan->sum('total') }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Keseluruhan database</span>
                        <span class="stat-card-tag">Aktif</span>
                    </div>
                </div>
                <div class="stat-card sc-2">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-diagram-3-fill"></i></div>
                        <div class="stat-card-lbl">Jalur Simlitabkes</div>
                    </div>
                    <div class="stat-card-num">{{ $totalPerJalur->get('simlitabkes', 0) }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Dana dari pusat</span>
                        <span class="stat-card-tag">Simlitabkes</span>
                    </div>
                </div>
                <div class="stat-card sc-3">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-person-fill"></i></div>
                        <div class="stat-card-lbl">Jalur Mandiri</div>
                    </div>
                    <div class="stat-card-num">{{ $totalPerJalur->get('mandiri', 0) }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Dana mandiri</span>
                        <span class="stat-card-tag">Mandiri</span>
                    </div>
                </div>
                <div class="stat-card sc-4">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-building-fill"></i></div>
                        <div class="stat-card-lbl">Jurusan Aktif</div>
                    </div>
                    <div class="stat-card-num">{{ $rekapJurusan->count() }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Terdaftar</span>
                        <span class="stat-card-tag">Valid</span>
                    </div>
                </div>
            </div>

            {{-- FILTER PANEL --}}
            <form method="GET" action="{{ route('admin.laporan') }}">
                <input type="hidden" name="tab" value="laporan1">
                <div class="filter-panel">
                    <div class="filter-box">
                        <div class="field">
                            <label class="field-label">Pilih Jurusan</label>
                            <div class="select-wrap">
                                <select name="jurusan1" class="field-select">
                                    <option value="">-- Pilih Jurusan --</option>
                                    @foreach ($jurusanList as $j)
                                        <option value="{{ $j }}"
                                            {{ ($jurusan1 ?? '') == $j ? 'selected' : '' }}>
                                            {{ $j }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label">Pilih Tahun</label>
                            <div class="select-wrap">
                                <select name="tahun" class="field-select">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($tahunList as $t)
                                        <option value="{{ $t }}" {{ $tahun1 == $t ? 'selected' : '' }}>
                                            {{ $t }}</option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>

                        <div class="field">
                            <label class="field-label">Jenis Laporan</label>
                            <div class="select-wrap">
                                <select name="jenis" class="field-select">
                                    <option value="">-- Semua Jenis --</option>
                                    @foreach ($jenisList as $val => $label)
                                        <option value="{{ $val }}" {{ $jenis1 === $val ? 'selected' : '' }}>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>

                        @if ($tahun1 || $jenis1 || ($jurusan1 ?? null))
                            <a href="{{ route('admin.laporan', ['tab' => 'laporan1']) }}" class="btn-reset"
                                title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>

                    <div class="export-box">
                        <span class="export-box-label">Export</span>
                        <div class="export-box-btns">
                            <a href="{{ route('admin.laporan.laporan1.export.excel', array_filter(['tahun' => $tahun1, 'jenis' => $jenis1, 'jurusan1' => $jurusan1 ?? null])) }}"
                                class="btn-exp btn-exp-excel-solid">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </a>
                            <a href="{{ route('admin.laporan.laporan1.export.pdf', array_filter(['tahun' => $tahun1, 'jenis' => $jenis1, 'jurusan1' => $jurusan1 ?? null])) }}"
                                class="btn-exp btn-exp-pdf-solid">
                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                            </a>
                            <a href="{{ route('admin.laporan.laporan1.export.excel') }}"
                                class="btn-exp btn-exp-excel-outline">
                                <i class="bi bi-file-earmark-excel"></i> Excel Semua
                            </a>
                            <a href="{{ route('admin.laporan.laporan1.export.pdf') }}"
                                class="btn-exp btn-exp-pdf-outline">
                                <i class="bi bi-file-earmark-pdf"></i> PDF Semua
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="tbl-card">
                <div class="tbl-head-bar">
                    <div class="tbl-head-left">
                        <div class="tbl-head-icon"><i class="bi bi-bar-chart-steps"></i></div>
                        <div>
                            <div class="tbl-head-title">Rekap Per Jurusan</div>
                            <div class="tbl-head-sub">Ringkasan jumlah pengajuan per jurusan</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="perpage-wrap">
                            <span>Tampilkan:</span>
                            <select onchange="changePerPage(this.value)">
                                @foreach ([10, 25, 50, 100] as $pp)
                                    <option value="{{ $pp }}" {{ request('perpage', 10) == $pp ? 'selected' : '' }}>
                                        {{ $pp }}</option>
                                @endforeach
                            </select>
                            <span>/ halaman</span>
                        </div>
                        <span class="tbl-badge">
                            <i class="bi bi-building"></i>
                            {{ $rekapJurusan->count() }} Jurusan
                        </span>
                    </div>
                </div>

                @if ($rekapJurusan->isEmpty())
                    <div class="empty-box">
                        <div class="ic"><i class="bi bi-inbox"></i></div>
                        <h6 class="fw-bold text-muted">Belum ada data</h6>
                        <p class="text-muted small">Pilih tahun atau reset filter</p>
                    </div>
                @else
                    <div class="table-scroll">
                        <table class="table l1-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" width="45">No</th>
                                    <th width="220">Jurusan</th>
                                    <th class="text-center" width="90">Total</th>
                                    <th width="150">Jalur</th>
                                    <th width="380">Rincian Per Skema</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($rekapJurusan as $jurusan => $data)
                                    <tr class="data-row {{ $loop->iteration % 2 === 0 ? 'row-alt' : '' }}">
                                        <td class="ps-3 text-muted fw-semibold" style="font-size:11px;">
                                            {{ $no++ }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="jur-av">{{ strtoupper(substr($jurusan, 0, 1)) }}</div>
                                                <div>
                                                    <div class="fw-semibold" style="color:#0f172a;font-size:12.5px;">
                                                        {{ $jurusan }}</div>
                                                    <div style="font-size:10px;color:#94a3b8;margin-top:1px;">
                                                        @foreach ($data['per_jenis'] as $j => $jml)
                                                            {{ ucfirst($j) }}: <b
                                                                style="color:#64748b">{{ $jml }}</b>
                                                            @if (!$loop->last)
                                                                &nbsp;&middot;&nbsp;
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="total-cell">
                                                <span class="total-cell-num">{{ $data['total'] }}</span>
                                                <span class="total-cell-lbl">Pengajuan</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="jalur-tags">
                                                @if (($data['per_jalur']['simlitabkes'] ?? 0) > 0)
                                                    <span class="jalur-tag"><span
                                                            class="jalur-dot jalur-dot-sim"></span>Simlitabkes
                                                        <b>{{ $data['per_jalur']['simlitabkes'] }}</b></span>
                                                @endif
                                                @if (($data['per_jalur']['mandiri'] ?? 0) > 0)
                                                    <span class="jalur-tag"><span
                                                            class="jalur-dot jalur-dot-man"></span>Mandiri
                                                        <b>{{ $data['per_jalur']['mandiri'] }}</b></span>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Rincian per skema — daftar rapi, nama tampil penuh --}}
                                        <td>
                                            @if ($data['per_skema']->isNotEmpty())
                                                <div class="skema-list">
                                                    @foreach ($data['per_skema'] as $sk => $jml)
                                                        <div class="skema-row">
                                                            <span class="skema-name">{{ $sk }}</span>
                                                            <span class="skema-count">{{ $jml }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="skema-empty">Tidak ada skema</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="tfoot-tot">
                                <tr>
                                    <td colspan="2" class="ps-3 text-end" style="color:#047857;">GRAND TOTAL</td>
                                    <td class="text-center">
                                        <span class="total-cell-num"
                                            style="font-size:1.15rem;">{{ $rekapJurusan->sum('total') }}</span>
                                    </td>
                                    <td>
                                        <div class="jalur-tags">
                                            <span class="jalur-tag"><span
                                                    class="jalur-dot jalur-dot-sim"></span>Simlitabkes
                                                <b>{{ $rekapJurusan->sum(fn($d) => $d['per_jalur']['simlitabkes'] ?? 0) }}</b></span>
                                            <span class="jalur-tag"><span class="jalur-dot jalur-dot-man"></span>Mandiri
                                                <b>{{ $rekapJurusan->sum(fn($d) => $d['per_jalur']['mandiri'] ?? 0) }}</b></span>
                                        </div>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        @endif
        {{-- END LAPORAN 1 --}}

        {{-- ══════════════════════════════════════════
         LAPORAN 2
    ══════════════════════════════════════════ --}}
        @if ($activeTab === 'laporan2')

            @php
                $totalKegiatan = $data2->total();
                $totalPeneliti =
                    $data2->getCollection()->map(fn($p) => $p->pegawai_id)->unique()->count() +
                    $data2->getCollection()->flatMap(fn($p) => $p->anggotas->pluck('pegawai_id'))->unique()->count();
                $totalLuaran = $data2->getCollection()->filter(fn($p) => $p->laporanKemajuan)->count();
                $totalJurusan2 = $data2
                    ->getCollection()
                    ->map(fn($p) => $p->pegawai?->jurusan)
                    ->filter()
                    ->unique()
                    ->count();
            @endphp

            <div class="stat-grid">
                <div class="stat-card sc-1">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-calendar3"></i></div>
                        <div class="stat-card-lbl">Total Kegiatan</div>
                    </div>
                    <div class="stat-card-num">{{ $totalKegiatan }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Kegiatan tercatat</span>
                        <span class="stat-card-tag">Total</span>
                    </div>
                </div>
                <div class="stat-card sc-2">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-card-lbl">Total Peneliti</div>
                    </div>
                    <div class="stat-card-num">{{ $totalPeneliti }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Ketua & anggota</span>
                        <span class="stat-card-tag">Peneliti</span>
                    </div>
                </div>
                <div class="stat-card sc-3">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-award-fill"></i></div>
                        <div class="stat-card-lbl">Total Luaran</div>
                    </div>
                    <div class="stat-card-num">{{ $totalLuaran }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Sudah lapor kemajuan</span>
                        <span class="stat-card-tag">Luaran</span>
                    </div>
                </div>
                <div class="stat-card sc-4">
                    <div class="stat-card-top">
                        <div class="stat-card-icon"><i class="bi bi-building-fill"></i></div>
                        <div class="stat-card-lbl">Total Jurusan</div>
                    </div>
                    <div class="stat-card-num">{{ $totalJurusan2 }}</div>
                    <div class="stat-card-bottom">
                        <span class="stat-card-desc">Terlibat</span>
                        <span class="stat-card-tag">Jurusan</span>
                    </div>
                </div>
            </div>

            {{-- FILTER PANEL LAPORAN 2 --}}
            <form method="GET" action="{{ route('admin.laporan') }}">
                <input type="hidden" name="tab" value="laporan2">
                <div class="filter-panel">
                    <div class="filter-box">
                        <div class="field">
                            <label class="field-label">Pilih Tahun</label>
                            <div class="select-wrap">
                                <select name="tahun2" class="field-select">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach ($tahunList as $t)
                                        <option value="{{ $t }}"
                                            {{ ($filters2['tahun2'] ?? '') == $t ? 'selected' : '' }}>{{ $t }}</option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field-label">Pilih Jurusan</label>
                            <div class="select-wrap">
                                <select name="jurusan2" class="field-select">
                                    <option value="">-- Semua Jurusan --</option>
                                    @foreach ($jurusanList as $j)
                                        <option value="{{ $j }}"
                                            {{ ($filters2['jurusan2'] ?? '') == $j ? 'selected' : '' }}>{{ $j }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field-label">Jenis Kegiatan</label>
                            <div class="select-wrap">
                                <select name="jenis2" class="field-select">
                                    <option value="">-- Semua Jenis --</option>
                                    @foreach ($jenisList as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ ($filters2['jenis2'] ?? '') == $val ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field-label">Jalur</label>
                            <div class="select-wrap">
                                <select name="jalur2" class="field-select">
                                    <option value="">-- Semua Jalur --</option>
                                    @foreach ($jalurList as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ ($filters2['jalur2'] ?? '') == $val ? 'selected' : '' }}>{{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>
                        <div class="field">
                            <label class="field-label">Skema</label>
                            <div class="select-wrap">
                                <select name="skema_id2" class="field-select">
                                    <option value="">-- Semua Skema --</option>
                                    @foreach ($skemaList as $s)
                                        <option value="{{ $s->id }}"
                                            {{ ($filters2['skema_id2'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="bi bi-chevron-down select-caret"></i>
                            </div>
                        </div>
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-funnel-fill"></i> Filter
                        </button>
                        @if (array_filter($filters2))
                            <a href="{{ route('admin.laporan', ['tab' => 'laporan2']) }}" class="btn-reset"
                                title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>

                    <div class="export-box">
                        <span class="export-box-label">Export</span>
                        <div class="export-box-btns">
                            <a href="{{ route(
                                'admin.laporan.laporan2.export.excel',
                                array_filter([
                                    'tahun2' => $filters2['tahun2'] ?? null,
                                    'jurusan2' => $filters2['jurusan2'] ?? null,
                                    'jenis2' => $filters2['jenis2'] ?? null,
                                    'jalur2' => $filters2['jalur2'] ?? null,
                                    'skema_id2' => $filters2['skema_id2'] ?? null,
                                ]),
                            ) }}"
                                class="btn-exp btn-exp-excel-solid">
                                <i class="bi bi-file-earmark-excel-fill"></i> Excel
                            </a>
                            <a href="{{ route(
                                'admin.laporan.laporan2.export.pdf',
                                array_filter([
                                    'tahun2' => $filters2['tahun2'] ?? null,
                                    'jurusan2' => $filters2['jurusan2'] ?? null,
                                    'jenis2' => $filters2['jenis2'] ?? null,
                                    'jalur2' => $filters2['jalur2'] ?? null,
                                    'skema_id2' => $filters2['skema_id2'] ?? null,
                                ]),
                            ) }}"
                                class="btn-exp btn-exp-pdf-solid">
                                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                            </a>
                            <a href="{{ route('admin.laporan.laporan2.export.excel') }}"
                                class="btn-exp btn-exp-excel-outline">
                                <i class="bi bi-file-earmark-excel"></i> Excel Semua
                            </a>
                            <a href="{{ route('admin.laporan.laporan2.export.pdf') }}"
                                class="btn-exp btn-exp-pdf-outline">
                                <i class="bi bi-file-earmark-pdf"></i> PDF Semua
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE L2 --}}
            <div class="tbl-card2">
                <div class="tbl-hd2">
                    <div class="tbl-hd2-t">
                        <div class="tbl-hd2-ic"><i class="bi bi-table"></i></div>
                        Daftar Penelitian & Pengabdian
                    </div>
                    <span class="tbl-badge">{{ $data2->total() }} data</span>
                </div>

                @if ($data2->isEmpty())
                    <div class="empty-box">
                        <div class="ic"><i class="bi bi-inbox"></i></div>
                        <h6 class="fw-bold text-muted">Tidak ada data ditemukan</h6>
                        <p class="text-muted small">Coba ubah atau reset filter</p>
                    </div>
                @else
                    <div class="table-scroll">
                        <table class="table l2-table mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-3" width="40">No</th>
                                    <th width="62">Tahun</th>
                                    <th width="280">Judul</th>
                                    <th width="150">Ketua Peneliti</th>
                                    <th width="120">Jurusan</th>
                                    <th width="190">Anggota Tim</th>
                                    <th class="text-center" width="80">Jenis</th>
                                    <th class="text-center" width="90">Jalur</th>
                                    <th width="150">Skema</th>
                                    <th width="240">Luaran Diusulkan</th>
                                    <th width="240">Luaran Tercapai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data2 as $i => $p)
                                    @php
                                        $lk = $p->laporanKemajuan;
                                        // Semua luaran yang diusulkan di proposal (nama dari luaran_master + opsi yang dipilih)
                                        $luaranDiusulkan = $p->luaran ?? collect();
                                        // ID pengajuan_luaran yang ditandai "tercapai" pada laporan kemajuan
                                        $idTercapai = collect($lk?->luaran_tercapai ?? []);
                                        $luaranTercapai = $luaranDiusulkan->whereIn('id', $idTercapai->all());
                                        $tval = $p->{$kolomTahun} ?? ($p->created_at?->year ?? '-');
                                    @endphp
                                    <tr>
                                        <td class="ps-3 text-muted fw-semibold" style="font-size:11px;">
                                            {{ $data2->firstItem() + $i }}
                                        </td>
                                        <td><span class="p2 p2-thn">{{ $tval }}</span></td>
                                        <td>
                                            <div class="fw-semibold"
                                                style="color:#0f172a;line-height:1.4;font-size:11.5px;"
                                                title="{{ $p->judul }}">
                                                {{ Str::limit($p->judul, 55) }}
                                            </div>
                                            <small
                                                style="color:#64748b;font-size:10px;font-weight:600;">{{ $p->kode ?? '' }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="av-k">
                                                    {{ strtoupper(substr($p->pegawai?->nama ?? 'X', 0, 1)) }}</div>
                                                <div>
                                                    <div class="fw-semibold" style="font-size:11px;line-height:1.3;">
                                                        {{ $p->pegawai?->nama ?? '-' }}
                                                    </div>
                                                    <small
                                                        style="color:#64748b;font-size:10px;font-weight:600;">{{ $p->pegawai?->nidn ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-size:11px;color:#047857;font-weight:600;">
                                                {{ $p->pegawai?->jurusan ?? '-' }}
                                            </span>
                                        </td>

                                        {{-- ✅ ANGGOTA TIM — nama + jurusan, rapi & bisa digeser vertikal --}}
                                        <td>
                                            @forelse($p->anggotas as $a)
                                                @if ($loop->first)
                                                    <div class="anggota-list">
                                                @endif
                                                <div class="anggota-item" title="{{ $a->pegawai?->nama ?? '-' }}">
                                                    <div class="av-a">
                                                        {{ strtoupper(substr($a->pegawai?->nama ?? 'X', 0, 1)) }}</div>
                                                    <div class="anggota-item-txt">
                                                        <small>{{ $a->pegawai?->nama ?? '-' }}</small>
                                                        <span
                                                            class="anggota-item-jur">{{ $a->pegawai?->jurusan ?? '-' }}</span>
                                                    </div>
                                                </div>
                                                @if ($loop->last)
                    </div>
                @endif
            @empty
                <span class="anggota-empty">-</span>
        @endforelse
        </td>

        <td class="text-center">
            <span class="p2 {{ $p->jenis === 'penelitian' ? 'p2-pen' : 'p2-peng' }}">
                {{ ucfirst($p->jenis ?? '-') }}
            </span>
        </td>
        <td class="text-center">
            <span class="p2 {{ $p->jalur === 'mandiri' ? 'p2-man' : 'p2-sim' }}">
                {{ ucfirst($p->jalur ?? '-') }}
            </span>
        </td>
        <td>
            <span class="skema-txt">{{ $p->skema?->nama ?? '-' }}</span>
        </td>

        {{-- ✅ LUARAN DIUSULKAN — daftar nama luaran dari proposal --}}
        <td>
            @if ($luaranDiusulkan->isNotEmpty())
                <div class="luaran-list">
                    @foreach ($luaranDiusulkan as $lu)
                        <div class="luaran-item {{ $idTercapai->contains($lu->id) ? 'is-tercapai' : '' }}">
                            <div class="luaran-item-name">
                                {{ $lu->luaranMaster?->nama ?? 'Luaran tanpa nama' }}
                                @if ($idTercapai->contains($lu->id))
                                    <span class="luaran-tercapai-badge">Tercapai</span>
                                @endif
                            </div>
                            @if ($lu->opsi_dipilih)
                                <span class="luaran-item-opsi">{{ $lu->opsi_dipilih }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <span class="lb-empty">Belum ada luaran diusulkan</span>
            @endif
        </td>

        {{-- ✅ LUARAN TERCAPAI — hanya yang ditandai tercapai, lengkap dengan info realisasi dari dosen --}}
        <td>
            @if (!$lk)
                <span class="lb-empty"><i class="bi bi-dash-circle me-1"></i>Belum ada laporan</span>
            @elseif($luaranTercapai->isEmpty())
                <span class="lb-empty"><i class="bi bi-hourglass-split me-1" style="color:#f59e0b;"></i>Belum ada luaran
                    tercapai</span>
            @else
                <div class="luaran-list">
                    @foreach ($luaranTercapai as $lu)
                        <div class="luaran-item is-tercapai">
                            <div class="luaran-item-name">{{ $lu->luaranMaster?->nama ?? 'Luaran tanpa nama' }}</div>
                            @if ($lu->opsi_dipilih)
                                <span class="luaran-item-opsi">{{ $lu->opsi_dipilih }}</span>
                            @endif
                            @if ($lu->realisasi)
                                <div class="luaran-item-real">
                                    @if ($lu->realisasi->keterangan)
                                        {{ Str::limit($lu->realisasi->keterangan, 90) }}<br>
                                    @endif
                                    @if ($lu->realisasi->link_bukti)
                                        <a href="{{ $lu->realisasi->link_bukti }}" target="_blank" rel="noopener">Lihat
                                            bukti</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </td>
        </tr>
        @endforeach
        </tbody>
        </table>
    </div>
    <div class="scroll-hint">
        <i class="bi bi-arrow-left-right"></i> Geser tabel ke kanan/kiri untuk melihat semua kolom
    </div>

    <div class="pg-wrap">
        <div class="pg-info">
            Menampilkan <b>{{ $data2->firstItem() }}</b>&ndash;<b>{{ $data2->lastItem() }}</b>
            dari <b>{{ $data2->total() }}</b> data
        </div>
        {{ $data2->appends(array_merge($filters2, ['tab' => 'laporan2']))->links() }}
    </div>
    @endif
    </div>
    @endif
    {{-- END LAPORAN 2 --}}

    </div>
@endsection

@push('scripts')
    <script>
        function changePerPage(val) {
            const url = new URL(window.location.href);
            url.searchParams.set('perpage', val);
            url.searchParams.set('tab', 'laporan1');
            window.location.href = url.toString();
        }
    </script>
@endpush

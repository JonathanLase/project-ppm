@extends('layouts.app')

@section('title', 'Pengajuan Baru')
@section('crumbs', 'Menu Dosen / Pengajuan Baru')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/wizard.css') }}">

    <style>
        .s5-section {
            border: 1px solid #eef2f0;
            border-radius: 12px;
            margin-bottom: 14px;
            overflow: hidden;
        }

        .s5-section-head {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            background: #f8fafc;
            border-bottom: 1px solid #eef2f0;
            font-weight: 800;
            font-size: 12.5px;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .s5-section-head .ic {
            width: 24px;
            height: 24px;
            border-radius: 7px;
            background: #e6f4ee;
            color: #00875A;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .s5-section-head .ic svg {
            width: 12px;
            height: 12px;
        }

        .s5-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 11px 16px;
            border-bottom: 1px solid #f4f5f6;
            font-size: 13px;
        }

        .s5-row:last-child {
            border-bottom: none;
        }

        .s5-row .k {
            color: #6b7280;
            flex-shrink: 0;
            min-width: 130px;
        }

        .s5-row .v {
            color: #111827;
            font-weight: 600;
            text-align: right;
        }
    </style>

    <div class="card wizard-card">
        @include('pengajuan._stepper', ['current' => 5])

        <div class="s5-section">
            <div class="s5-section-head">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M9 11l3 3L22 4" />
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                    </svg></span>
                Jalur & Skema
            </div>
            <div class="s5-row"><span class="k">Jenis</span><span class="v">{{ ucfirst($w['jenis']) }}</span>
            </div>
            <div class="s5-row"><span class="k">Jalur</span><span
                    class="v">{{ $w['jalur'] === 'mandiri' ? 'Mandiri' : 'Simlitabkes' }}</span></div>
            <div class="s5-row"><span class="k">Skema</span><span class="v">{{ $skema->nama ?? '-' }}</span></div>
            <div class="s5-row"><span class="k">Rumpun Ilmu</span><span
                    class="v">{{ $rumpunIlmu->nama ?? '-' }}</span></div>
            <div class="s5-row"><span class="k">Judul</span><span class="v">{{ $w['judul'] }}</span></div>
            <div class="s5-row"><span class="k">Tahun</span><span class="v">Anggaran {{ $w['tahun_anggaran'] }} ·
                    Pengajuan {{ $w['tahun_pengajuan'] }} · Pelaksanaan Tahap {{ $w['tahun_pelaksanaan'] }} · Capaian
                    {{ $w['tahun_capaian'] }}</span></div>
        </div>

        <div class="s5-section">
            <div class="s5-section-head">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                    </svg></span>
                Ketua & Tim
            </div>
            <div class="s5-row"><span class="k">Ketua</span><span class="v">{{ $ketua->nama }} (NIP
                    {{ $ketua->nip }}){{ $ketua->jurusan ? ' — ' . $ketua->jurusan : '' }}</span></div>
            @foreach ($anggotaTim as $a)
                <div class="s5-row"><span class="k">Anggota</span><span class="v">{{ $a->nama }} (NIP
                        {{ $a->nip }}){{ $a->jurusan ? ' — ' . $a->jurusan : '' }}</span></div>
            @endforeach
            @foreach ($anggotaLuar as $luar)
                <div class="s5-row"><span class="k">Anggota (luar sistem)</span><span
                        class="v">{{ $luar['nama'] }}{{ $luar['instansi'] ? ' — ' . $luar['instansi'] : '' }}</span>
                </div>
            @endforeach
            @if ($anggotaTim->isEmpty() && empty($anggotaLuar))
                <div class="s5-row"><span class="k">Anggota</span><span class="v">Tidak ada</span></div>
            @endif
        </div>

        <div class="s5-section">
            <div class="s5-section-head">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg></span>
                Dokumen & Anggaran
            </div>
            <div class="s5-row"><span class="k">Proposal</span><span class="v">{{ $w['proposal_nama_asli'] }}
                    ({{ number_format($w['proposal_size'] / 1024, 0) }} KB)</span></div>
            <div class="s5-row"><span class="k">Kontrak</span><span class="v">{{ $w['kontrak_nama_asli'] ? $w['kontrak_nama_asli'] . ' (' . number_format($w['kontrak_size'] / 1024, 0) . ' KB)' : '-' }}</span></div>
            <div class="s5-row"><span class="k">RAB</span><span class="v">{{ $w['rab_nama_asli'] ? $w['rab_nama_asli'] . ' (' . number_format($w['rab_size'] / 1024, 0) . ' KB)' : '-' }}</span></div>
            <div class="s5-row"><span class="k">Total Biaya Usulan</span><span class="v">Rp
                    {{ number_format($w['total_biaya'], 0, ',', '.') }}</span></div>
        </div>

        <div class="s5-section">
            <div class="s5-section-head">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2l3 7h7l-5.5 4.5L18.5 21 12 16.5 5.5 21l2-7.5L2 9h7z" />
                    </svg></span>
                Rencana Luaran
            </div>
            @forelse ($luaranWajibDipilih as $l)
                <div class="s5-row"><span class="k">Wajib</span><span
                        class="v">{{ $l->nama }}{{ $w['luaran_wajib'][$l->id] ?? null ? ' — ' . $w['luaran_wajib'][$l->id] : '' }}</span>
                </div>
            @empty
                <div class="s5-row"><span class="k">Wajib</span><span class="v">Tidak ada</span></div>
            @endforelse
            @forelse ($luaranTambahanDipilih as $l)
                <div class="s5-row"><span class="k">Tambahan</span><span
                        class="v">{{ $l->nama }}{{ $w['luaran_tambahan'][$l->id] ?? null ? ' — ' . $w['luaran_tambahan'][$l->id] : '' }}</span>
                </div>
            @empty
                <div class="s5-row"><span class="k">Tambahan</span><span class="v">Tidak ada</span></div>
            @endforelse
            @if ($w['inovasi_produk'])
                <div class="s5-row"><span class="k">Inovasi Produk</span><span
                        class="v">{{ $w['inovasi_produk'] }}</span></div>
            @endif
        </div>

        <div class="alert-box alert-info">ℹ️ Setelah dikirim, status pengajuan menjadi <b>Dalam Proses</b>.
            {{ $w['jalur'] === 'mandiri' ? 'Alur selanjutnya: Proposal → langsung Laporan Hasil (tanpa Laporan Kemajuan).' : 'Alur selanjutnya: Proposal → Laporan Kemajuan → Laporan Hasil, masing-masing divalidasi bertahap.' }}
        </div>

        <form method="POST" action="{{ route('pengajuan.submit') }}">
            @csrf
            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="{{ route('pengajuan.step4') }}" class="btn btn-outline">Kembali</a>
                <button class="btn btn-primary" type="submit">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
@endsection
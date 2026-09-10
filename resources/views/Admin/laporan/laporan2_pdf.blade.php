<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan 2 - Detail Penelitian</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8.5px;
            color: #333;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1a6b3c;
        }

        .header h2 {
            font-size: 13px;
            color: #1a6b3c;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 9px;
            color: #666;
        }

        .filter-info {
            background: #f1f8e9;
            border: 1px solid #c5e1a5;
            border-left: 4px solid #1a6b3c;
            padding: 5px 10px;
            margin-bottom: 10px;
            font-size: 8px;
            border-radius: 3px;
        }

        .filter-info strong {
            color: #1a6b3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background-color: #1a6b3c;
            color: white;
            padding: 6px 5px;
            text-align: center;
            font-size: 8px;
            border: 1px solid #145a32;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #e0e0e0;
            vertical-align: top;
            font-size: 8px;
            line-height: 1.4;
        }

        tr:nth-child(even) td {
            background-color: #f9fbe7;
        }

        tr:nth-child(odd) td {
            background-color: #ffffff;
        }

        .badge-penelitian {
            background: #e8f5e9;
            color: #1a6b3c;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-pengabdian {
            background: #e3f2fd;
            color: #1565c0;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .badge-jalur {
            background: #fff3e0;
            color: #e65100;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-muted {
            color: #9e9e9e;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #9e9e9e;
            font-style: italic;
        }

        .footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #e0e0e0;
            font-size: 8px;
            color: #9e9e9e;
            display: flex;
            justify-content: space-between;
        }

        .total-info {
            background: #e8f5e9;
            border: 1px solid #a5d6a7;
            padding: 5px 10px;
            margin-bottom: 10px;
            border-radius: 3px;
            font-size: 9px;
            color: #1a6b3c;
            font-weight: bold;
        }

        /* ✅ FIX: anggota (nama + jurusan) & luaran ditampilkan per baris (list) */
        .line {
            display: block;
        }

        .line+.line {
            margin-top: 3px;
        }

        .sub {
            color: #9e9e9e;
            font-size: 7px;
        }

        /* Kartu luaran, konsisten dengan gaya list di tabel web */
        .luaran-card {
            border-left: 2px solid #1a6b3c;
            background: #f3faf5;
            padding: 3px 5px;
            margin-bottom: 3px;
            border-radius: 2px;
        }

        .luaran-card.tercapai {
            border-left-color: #2e7d32;
            background: #eaf7ec;
        }

        .luaran-card b {
            color: #1a6b3c;
        }

        .luaran-card .opsi {
            display: inline-block;
            font-size: 6.5px;
            color: #1a6b3c;
            background: #fff;
            border: 1px solid #c5e1a5;
            border-radius: 3px;
            padding: 0 4px;
            margin-left: 3px;
        }

        .luaran-card .real {
            display: block;
            margin-top: 2px;
            font-size: 7px;
            color: #2e7d32;
            border-top: 1px dashed #c5e1a5;
            padding-top: 2px;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header">
        <h2>LAPORAN 2 – DETAIL PENELITIAN DAN CAPAIAN LUARAN</h2>
        <p>Sistem Informasi Penelitian & Pengabdian – Poltekkes Kemenkes</p>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Filter Info — ✅ FIX: skema ikut ditampilkan, sebelumnya tidak ada --}}
    @if (array_filter($filters, fn($v) => !empty($v) && $v !== null))
        <div class="filter-info">
            <strong>Filter yang diterapkan:</strong>
            @if (!empty($filters['tahun']))
                &nbsp;Tahun: <strong>{{ $filters['tahun'] }}</strong>
            @endif
            @if (!empty($filters['jurusan']))
                &nbsp;| Jurusan: <strong>{{ $filters['jurusan'] }}</strong>
            @endif
            @if (!empty($filters['jenis']))
                &nbsp;| Jenis: <strong>{{ ucfirst($filters['jenis']) }}</strong>
            @endif
            @if (!empty($filters['jalur']))
                &nbsp;| Jalur: <strong>{{ ucfirst($filters['jalur']) }}</strong>
            @endif
            @if (!empty($filters['skema_id']))
                &nbsp;| Skema ID: <strong>{{ $filters['skema_id'] }}</strong>
            @endif
        </div>
    @endif

    {{-- Total Info --}}
    <div class="total-info">
        Total Data: {{ $data->count() }} penelitian/pengabdian
    </div>

    {{-- Tabel --}}
    <table>
        <thead>
            <tr>
                <th width="18">No</th>
                <th width="32">Tahun</th>
                <th width="110">Judul</th>
                <th width="70">Ketua Peneliti</th>
                <th width="60">Jurusan</th>
                <th width="90">Anggota Tim</th>
                <th width="48">Jenis</th>
                <th width="45">Jalur</th>
                <th width="55">Skema</th>
                <th width="105">Luaran Diusulkan</th>
                <th width="115">Luaran Tercapai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $kolomTahun = $filters['kolomTahun'] ?? 'tahun_pengajuan';
            @endphp

            @forelse($data as $p)
                @php
                    $lk = $p->laporanKemajuan;

                    // ✅ FIX TOTAL: luaran diusulkan diambil dari relasi $p->luaran
                    // (PengajuanLuaran -> LuaranMaster), bukan dari field yang tidak ada
                    $luaranSemua = $p->luaran ?? collect();

                    // ✅ FIX TOTAL: cocokkan ID di laporan_kemajuan.luaran_tercapai
                    // ke koleksi luaran supaya dapat nama aslinya
                    $idTercapai = collect($lk?->luaran_tercapai ?? []);
                    $luaranTercapaiItems = $luaranSemua->whereIn('id', $idTercapai->all());

                    // Nilai tahun
                    $tahunVal = '-';
                    try {
                        $tahunVal = $p->{$kolomTahun} ?? ($p->created_at?->year ?? '-');
                    } catch (\Exception $e) {
                        $tahunVal = $p->created_at?->year ?? '-';
                    }

                    $judulStr = is_string($p->judul) ? $p->judul : (string) ($p->judul ?? '-');
                    $ketuaNama = is_string($p->pegawai?->nama) ? $p->pegawai->nama : '-';
                    $ketuaJurusan = is_string($p->pegawai?->jurusan) ? $p->pegawai->jurusan : '-';
                    $jenisStr = is_string($p->jenis) ? $p->jenis : '-';
                    $jalurStr = is_string($p->jalur) ? $p->jalur : '-';
                    $skemaNama = is_string($p->skema?->nama) ? $p->skema->nama : '-';
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="text-center">{{ $tahunVal }}</td>
                    <td>{{ $judulStr }}</td>
                    <td>{{ $ketuaNama }}</td>
                    <td>{{ $ketuaJurusan }}</td>
                    <td>
                        {{-- ✅ FIX: anggota tim sekarang ikut menampilkan jurusan --}}
                        @forelse($p->anggotas as $a)
                            <span class="line">
                                {{ $a->pegawai?->nama ?? '-' }}
                                <span class="sub">({{ $a->pegawai?->jurusan ?? '-' }})</span>
                            </span>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </td>
                    <td class="text-center">
                        <span class="{{ $jenisStr === 'penelitian' ? 'badge-penelitian' : 'badge-pengabdian' }}">
                            {{ ucfirst($jenisStr) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge-jalur">{{ ucfirst($jalurStr) }}</span>
                    </td>
                    <td>{{ $skemaNama }}</td>

                    {{-- ✅ FIX TOTAL: Luaran Diusulkan — daftar nama luaran asli dari proposal --}}
                    <td>
                        @forelse($luaranSemua as $lu)
                            <div class="luaran-card {{ $idTercapai->contains($lu->id) ? 'tercapai' : '' }}">
                                <b>{{ $lu->luaranMaster?->nama ?? 'Luaran tanpa nama' }}</b>
                                @if ($lu->opsi_dipilih)
                                    <span class="opsi">{{ $lu->opsi_dipilih }}</span>
                                @endif
                            </div>
                        @empty
                            <span class="text-muted">Belum ada luaran diusulkan</span>
                        @endforelse
                    </td>

                    {{-- ✅ FIX TOTAL: Luaran Tercapai — nama asli + info realisasi dari dosen --}}
                    <td>
                        @if (!$lk)
                            <span class="text-muted">Belum ada laporan</span>
                        @elseif($luaranTercapaiItems->isEmpty())
                            <span class="text-muted">Belum ada luaran tercapai</span>
                        @else
                            @foreach ($luaranTercapaiItems as $lu)
                                <div class="luaran-card tercapai">
                                    <b>{{ $lu->luaranMaster?->nama ?? 'Luaran tanpa nama' }}</b>
                                    @if ($lu->opsi_dipilih)
                                        <span class="opsi">{{ $lu->opsi_dipilih }}</span>
                                    @endif
                                    @if ($lu->realisasi)
                                        <span class="real">
                                            @if ($lu->realisasi->keterangan)
                                                {{ $lu->realisasi->keterangan }}
                                            @endif
                                            @if ($lu->realisasi->link_bukti)
                                                <br>Bukti: {{ $lu->realisasi->link_bukti }}
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="no-data">
                        Tidak ada data yang ditemukan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <span>
            Total: {{ $data->count() }} data
            @if (!empty($filters['tahun']))
                | Tahun: {{ $filters['tahun'] }}
            @endif
        </span>
        <span>Dicetak: {{ now()->format('d/m/Y H:i') }}</span>
    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan 1 - Rekapitulasi Penelitian</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 14px;
        }

        .header h2 {
            font-size: 15px;
            color: #1a5276;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        .filter-info {
            background: #eef6f2;
            border: 1px solid #b7e0c9;
            border-left: 4px solid #1a6b3c;
            padding: 5px 10px;
            margin-bottom: 12px;
            font-size: 9px;
            border-radius: 3px;
        }

        .filter-info strong {
            color: #1a6b3c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th {
            background-color: #1a5276;
            color: white;
            padding: 8px 10px;
            text-align: center;
            font-size: 10px;
        }

        td {
            padding: 7px 10px;
            border: 1px solid #ddd;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background-color: #f8f9fa;
        }

        .tfoot-row td {
            background-color: #d5d8dc !important;
            font-weight: bold;
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            margin: 1px;
        }

        .badge-primary {
            background: #1a5276;
            color: white;
        }

        .badge-success {
            background: #27ae60;
            color: white;
        }

        .badge-warning {
            background: #f39c12;
            color: white;
        }

        .badge-info {
            background: #2980b9;
            color: white;
        }

        .badge-secondary {
            background: #95a5a6;
            color: white;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* ✅ FIX: rincian per skema ditampilkan per baris (list), bukan digabung
           dengan " | " dalam satu baris panjang — konsisten dgn tampilan web */
        .skema-line {
            display: block;
            line-height: 1.6;
        }

        .skema-line b {
            color: #1a6b3c;
        }

        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #999;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN 1 – REKAPITULASI JUMLAH PENELITIAN PER JURUSAN</h2>
        <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- ✅ FIX: tampilkan semua filter yang aktif (tahun, jenis, jurusan), bukan tahun saja --}}
    @if ($tahun || ($jenis ?? null) || ($jurusan ?? null))
        <div class="filter-info">
            <strong>Filter yang diterapkan:</strong>
            @if ($tahun)
                &nbsp;Tahun: <strong>{{ $tahun }}</strong>
            @endif
            @if ($jenis ?? null)
                &nbsp;| Jenis: <strong>{{ ucfirst($jenis) }}</strong>
            @endif
            @if ($jurusan ?? null)
                &nbsp;| Jurusan: <strong>{{ $jurusan }}</strong>
            @endif
        </div>
    @else
        <div class="filter-info">Menampilkan data <strong>semua tahun, semua jenis, semua jurusan</strong></div>
    @endif

    <table>
        <thead>
            <tr>
                <th width="30">No</th>
                <th>Jurusan</th>
                <th width="60">Total</th>
                <th width="80">Penelitian</th>
                <th width="80">Pengabdian</th>
                <th width="90">Simlitabkes</th>
                <th width="70">Mandiri</th>
                <th>Rincian Per Skema</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $grandTotal = 0;
                $totalPen = 0;
                $totalPng = 0;
                $totalSim = 0;
                $totalMan = 0;
            @endphp

            @forelse($rekapJurusan as $jurusanNama => $data)
                @php
                    $pen = $data['per_jenis']['penelitian'] ?? 0;
                    $png = $data['per_jenis']['pengabdian'] ?? 0;
                    $sim = $data['per_jalur']['simlitabkes'] ?? 0;
                    $man = $data['per_jalur']['mandiri'] ?? 0;
                    $grandTotal += $data['total'];
                    $totalPen += $pen;
                    $totalPng += $png;
                    $totalSim += $sim;
                    $totalMan += $man;
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><strong>{{ $data['jurusan'] ?? $jurusanNama }}</strong></td>
                    <td class="text-center">
                        <span class="badge badge-primary">{{ $data['total'] }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success">{{ $pen }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-info">{{ $png }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-warning">{{ $sim }}</span>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-secondary">{{ $man }}</span>
                    </td>
                    <td>
                        {{-- ✅ FIX: nama skema ditulis lengkap satu per baris, tidak dipotong --}}
                        @forelse($data['per_skema'] as $skema => $jml)
                            <span class="skema-line">{{ $skema }}: <b>{{ $jml }}</b></span>
                        @empty
                            <span class="text-center" style="color:#aaa;">Tidak ada skema</span>
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding:20px;color:#999;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="tfoot-row">
                <td colspan="2" class="text-right"><strong>TOTAL</strong></td>
                <td>{{ $grandTotal }}</td>
                <td>{{ $totalPen }}</td>
                <td>{{ $totalPng }}</td>
                <td>{{ $totalSim }}</td>
                <td>{{ $totalMan }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        Sistem Informasi Penelitian & Pengabdian – Poltekkes Kemenkes
    </div>
</body>

</html>

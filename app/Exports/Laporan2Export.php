<?php

namespace App\Exports;

use App\Http\Controllers\Admin\LaporanAdminController;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class Laporan2Export implements FromArray, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(private array $filters) {}

    public function array(): array
    {
        $controller = new LaporanAdminController();
        $data       = $controller->getLaporan2Data($this->filters);

        $rows   = [];
        $rows[] = ["LAPORAN 2 - DETAIL PENELITIAN DAN CAPAIAN LUARAN"];
        $rows[] = ["Dicetak: " . now()->format('d/m/Y H:i')];
        // ✅ FIX: baris kosong pakai [''] (bukan [] murni) — array kosong murni
        // di-skip oleh Maatwebsite Excel sehingga semua baris di bawahnya
        // kegeser naik 1 baris dan style header jadi salah tempat.
        $rows[] = [''];
        // ✅ FIX: kolom "Status" dihapus supaya sama persis dengan tabel di web
        $rows[] = [
            'No',
            'Tahun',
            'Judul',
            'Ketua Peneliti',
            'Jurusan Ketua',
            'Anggota Tim',
            'Jenis',
            'Jalur',
            'Skema',
            'Luaran Diusulkan',
            'Luaran Tercapai',
        ];

        $no         = 1;
        $kolomTahun = $this->filters['kolomTahun'] ?? 'tahun_pengajuan';

        foreach ($data as $p) {
            // ✅ FIX: anggota tim sekarang ikut menampilkan jurusan, satu per baris
            $anggotaStr = $p->anggotas
                ->map(fn($a) => trim(($a->pegawai?->nama ?? '-') . ' (' . ($a->pegawai?->jurusan ?? '-') . ')'))
                ->implode("\n");

            $lk = $p->laporanKemajuan;
            $lh = $p->laporanHasil;

            // ✅ FIX TOTAL: luaran diusulkan diambil dari relasi $p->luaran (PengajuanLuaran -> LuaranMaster),
            //    bukan dari field yang tidak pernah ada di tabel laporan_kemajuan
            $luaranSemua = $p->luaran ?? collect();
            $luaranDiusulkanStr = $luaranSemua->isEmpty()
                ? '-'
                : $luaranSemua->map(function ($lu) {
                    $nama = $lu->luaranMaster?->nama ?? 'Luaran tanpa nama';
                    return $lu->opsi_dipilih ? "{$nama} ({$lu->opsi_dipilih})" : $nama;
                })->implode("\n");

            // ✅ FIX TOTAL: "tercapai" sekarang pakai luaranTercapaiFinal yang sudah dihitung
            //    controller (getLaporan2Data -> resolveLuaranTercapai) — prioritas data FINAL
            //    dari Laporan Hasil (dgn link bukti + luaran tambahan), fallback ke checklist
            //    Laporan Kemajuan kalau Laporan Hasil belum ada
            $luaranTercapaiFinal = $p->luaranTercapaiFinal ?? collect();

            $luaranTercapaiStr = '-';
            if (!$lk && !$lh) {
                $luaranTercapaiStr = 'Belum ada laporan';
            } elseif ($luaranTercapaiFinal->isEmpty()) {
                $luaranTercapaiStr = 'Belum ada luaran tercapai';
            } else {
                $luaranTercapaiStr = $luaranTercapaiFinal->map(function ($lt) {
                    $line = $lt['opsi'] ? "{$lt['nama']} ({$lt['opsi']})" : $lt['nama'];
                    if ($lt['tambahan']) {
                        $line .= ' [Luaran Tambahan]';
                    }
                    if (!empty($lt['link'])) {
                        $line .= ' - Bukti: ' . $lt['link'];
                    }
                    return $line;
                })->implode("\n");
            }

            // Nilai tahun
            $tahunVal = $p->{$kolomTahun} ?? $p->created_at?->year ?? '-';

            $rows[] = [
                $no++,
                $tahunVal,
                $p->judul,
                $p->pegawai?->nama ?? '-',
                $p->pegawai?->jurusan ?? '-',
                $anggotaStr ?: '-',
                ucfirst($p->jenis ?? '-'),
                ucfirst($p->jalur ?? '-'),
                $p->skema?->nama ?? '-',
                $luaranDiusulkanStr,
                $luaranTercapaiStr,
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $lastRow = $sheet->getHighestRow();

        // ✅ FIX: merge & border sekarang sampai kolom K (bukan L), karena kolom
        //    Status sudah dihapus supaya sesuai dengan tabel di web
        $sheet->mergeCells('A1:K1');
        $sheet->mergeCells('A2:K2');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 13,
                'color' => ['rgb' => '1A5276'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Style header tabel (baris 4)
        $sheet->getStyle('A4:K4')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1A5276'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Border
        if ($lastRow >= 4) {
            $sheet->getStyle("A4:K{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
        }

        // Wrap text + rata atas untuk kolom yang isinya bisa banyak baris
        if ($lastRow >= 5) {
            $sheet->getStyle("C5:K{$lastRow}")->applyFromArray([
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => Alignment::VERTICAL_TOP,
                ],
            ]);
        }

        // Lebar kolom manual
        $sheet->getColumnDimension('C')->setWidth(40); // Judul
        $sheet->getColumnDimension('F')->setWidth(30); // Anggota Tim (nama + jurusan)
        $sheet->getColumnDimension('J')->setWidth(38); // Luaran diusulkan
        $sheet->getColumnDimension('K')->setWidth(42); // Luaran tercapai

        // Warna selang-seling
        for ($row = 5; $row <= $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F4F6F7'],
                    ],
                ]);
            }
        }

        return null;
    }

    public function title(): string
    {
        return 'Laporan 2';
    }
}

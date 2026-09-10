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

class Laporan1Export implements FromArray, WithStyles, WithTitle, ShouldAutoSize
{
    public function __construct(
        private ?string $tahun,
        private string $kolomTahun = 'tahun_pengajuan',
        private ?string $jenis = null,
        private ?string $jurusan = null,
    ) {}

    public function array(): array
    {
        $controller = new LaporanAdminController();

        // ✅ FIX: pakai method yang sama & filter yang sama dengan tabel di web
        //    (getLaporan1DataFiltered, bukan getLaporan1Data lama yang tidak
        //    memperhitungkan filter jenis & jurusan)
        $rekapJurusan = $controller->getLaporan1DataFiltered(
            $this->tahun,
            $this->jenis,
            $this->kolomTahun,
            $this->jurusan
        );

        $tahunLabel   = $this->tahun ? "Tahun {$this->tahun}" : 'Semua Tahun';
        $jenisLabel   = $this->jenis ? ' | Jenis: ' . ucfirst($this->jenis) : '';
        $jurusanLabel = $this->jurusan ? ' | Jurusan: ' . $this->jurusan : '';

        $rows   = [];
        $rows[] = ["LAPORAN 1 - REKAPITULASI PENELITIAN PER JURUSAN"];
        $rows[] = [$tahunLabel . $jenisLabel . $jurusanLabel];
        $rows[] = ["Dicetak: " . now()->format('d/m/Y H:i')];
        $rows[] = [];
        $rows[] = [
            'No',
            'Jurusan',
            'Total',
            'Penelitian',
            'Pengabdian',
            'Simlitabkes',
            'Mandiri',
            'Per Skema (nama : jumlah)',
        ];

        $no         = 1;
        $grandTotal = 0;

        foreach ($rekapJurusan as $data) {
            // ✅ FIX: nama skema ditulis satu per baris (list rapi), bukan digabung
            //    koma dalam satu baris panjang — biar konsisten dgn tampilan di web
            $skemaStr = collect($data['per_skema'])
                ->map(fn($count, $skema) => "{$skema}: {$count}")
                ->implode("\n");

            $rows[] = [
                $no++,
                $data['jurusan'],
                $data['total'],
                $data['per_jenis']['penelitian'] ?? 0,
                $data['per_jenis']['pengabdian'] ?? 0,
                $data['per_jalur']['simlitabkes'] ?? 0,
                $data['per_jalur']['mandiri'] ?? 0,
                $skemaStr,
            ];

            $grandTotal += $data['total'];
        }

        $rows[] = ['', 'TOTAL', $grandTotal, '', '', '', '', ''];

        return $rows;
    }

    public function styles(Worksheet $sheet): ?array
    {
        $lastRow = $sheet->getHighestRow();

        // Merge title rows
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'size'  => 14,
                'color' => ['rgb' => '1A5276'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:A3')->applyFromArray([
            'font'      => ['bold' => true],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Style header tabel (baris 5)
        $sheet->getStyle('A5:H5')->applyFromArray([
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

        // Style total row (baris terakhir)
        $sheet->getStyle("A{$lastRow}:H{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D5D8DC'],
            ],
        ]);

        // Border seluruh tabel
        if ($lastRow >= 5) {
            $sheet->getStyle("A5:H{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'CCCCCC'],
                    ],
                ],
            ]);
        }

        // Center kolom angka
        if ($lastRow >= 5) {
            $sheet->getStyle("C5:G{$lastRow}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // ✅ FIX: aktifkan wrap text + rata atas untuk kolom "Per Skema"
        //    supaya baris per-baris (\n) yang kita buat di array() kebaca rapi
        if ($lastRow >= 5) {
            $sheet->getStyle("H5:H{$lastRow}")->applyFromArray([
                'alignment' => [
                    'wrapText'   => true,
                    'vertical'   => Alignment::VERTICAL_TOP,
                ],
            ]);
            $sheet->getColumnDimension('H')->setWidth(45);
        }

        // Warna baris data selang-seling
        for ($row = 6; $row < $lastRow; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle("A{$row}:H{$row}")->applyFromArray([
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
        return 'Laporan 1';
    }
}

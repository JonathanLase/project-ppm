<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use App\Models\Pegawai;
use App\Models\Skema;
use App\Exports\Laporan1Export;
use App\Exports\Laporan2Export;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;

class LaporanAdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Deteksi nama kolom tahun secara otomatis
    |--------------------------------------------------------------------------
    */
    private function getKolomTahun(): string
    {
        $columns = Schema::getColumnListing('pengajuan');

        $candidates = [
            'tahun_pengajuan',
            'tahun_anggaran',
            'tahun_pelaksanaan',
            'tahun',
        ];

        foreach ($candidates as $col) {
            if (in_array($col, $columns)) {
                return $col;
            }
        }

        return 'created_at';
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX (Unified Laporan 1 & 2 via Tabs)
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $kolomTahun  = $this->getKolomTahun();
        $tahunList   = $this->getTahunList($kolomTahun);
        $jurusanList = $this->getJurusanList();
        $skemaList   = Skema::orderBy('nama')->get();

        $jenisList = ['penelitian' => 'Penelitian', 'pengabdian' => 'Pengabdian'];
        $jalurList = ['simlitabkes' => 'Simlitabkes', 'mandiri' => 'Mandiri'];

        // Tab aktif
        $activeTab = $request->get('tab', 'laporan1');

        // ── LAPORAN 1 DATA ──
        $tahun1   = $request->get('tahun');
        $jenis1   = $request->get('jenis');
        $jurusan1 = $request->get('jurusan1'); // ✅ NEW: filter jurusan laporan 1

        $rekapJurusan = $this->getLaporan1DataFiltered($tahun1, $jenis1, $kolomTahun, $jurusan1);

        $allData1 = Pengajuan::with(['pegawai', 'skema'])
            ->when($tahun1, function ($q) use ($tahun1, $kolomTahun) {
                $kolomTahun === 'created_at'
                    ? $q->whereYear('created_at', $tahun1)
                    : $q->where($kolomTahun, $tahun1);
            })
            ->when($jenis1, fn($q) => $q->where('jenis', $jenis1))
            ->when($jurusan1, fn($q) => $q->whereHas('pegawai', fn($qq) => $qq->where('jurusan', $jurusan1))) // ✅ NEW
            ->get();

        $totalPerJalur = $allData1->groupBy('jalur')->map->count();
        $totalPerJenis = $allData1->groupBy('jenis')->map->count();
        $totalJurusan  = $allData1->map(fn($p) => $p->pegawai?->jurusan)->filter()->unique()->count();

        // ── LAPORAN 2 DATA ──
        $filters2 = $request->only(['tahun2', 'jurusan2', 'jenis2', 'jalur2', 'skema_id2']);

        $query2 = Pengajuan::with([
                'pegawai', 'anggotas.pegawai',
                'skema', 'laporanKemajuan', 'laporanHasil',
                // ✅ FIX: load nama luaran (luaranMaster) & detail realisasi yang diinput dosen
                'luaran.luaranMaster',
                'luaran.realisasi',
            ])
            ->when($filters2['tahun2'] ?? null, function ($q) use ($filters2, $kolomTahun) {
                $kolomTahun === 'created_at'
                    ? $q->whereYear('created_at', $filters2['tahun2'])
                    : $q->where($kolomTahun, $filters2['tahun2']);
            })
            ->when($filters2['jurusan2'] ?? null, fn($q) => $q->whereHas('pegawai', fn($qq) => $qq->where('jurusan', $filters2['jurusan2'])))
            ->when($filters2['jenis2']    ?? null, fn($q) => $q->where('jenis',    $filters2['jenis2']))
            ->when($filters2['jalur2']    ?? null, fn($q) => $q->where('jalur',    $filters2['jalur2']))
            ->when($filters2['skema_id2'] ?? null, fn($q) => $q->where('skema_id', $filters2['skema_id2']))
            ->orderByDesc($kolomTahun === 'created_at' ? 'created_at' : $kolomTahun);

        $data2 = $query2->paginate(15)->withQueryString();

        // Summary laporan 2
        $sum2Kegiatan = $data2->total();
        $sum2Peneliti = $data2->getCollection()->map(fn($p) => $p->pegawai_id)->unique()->count()
            + $data2->getCollection()->flatMap(fn($p) => $p->anggotas->pluck('pegawai_id'))->unique()->count();
        $sum2Luaran   = $data2->getCollection()->filter(fn($p) => $p->laporanKemajuan)->count();
        $sum2Jurusan  = $data2->getCollection()->map(fn($p) => $p->pegawai?->jurusan)->filter()->unique()->count();
        $sum2Selesai  = $data2->getCollection()->filter(fn($p) => $p->status === 'disetujui')->count();

        // Pastikan $kolomTahun ikut di-compact
        return view('admin.laporan.index', compact(
            'activeTab',
            'kolomTahun',      // ← wajib ada untuk laporan2
            'tahunList',
            'jurusanList',
            'skemaList',
            'jenisList',
            'jalurList',
            // Laporan 1
            'rekapJurusan',
            'tahun1',
            'jenis1',
            'jurusan1',        // ✅ NEW
            'totalPerJalur',
            'totalPerJenis',
            'totalJurusan',
            // Laporan 2
            'data2',
            'filters2',
            'sum2Kegiatan',
            'sum2Peneliti',
            'sum2Luaran',
            'sum2Jurusan',
            'sum2Selesai',
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Exports Laporan 1
    |--------------------------------------------------------------------------
    */
    public function laporan1ExportExcel(Request $request)
    {
        $kolomTahun = $this->getKolomTahun();
        $tahun      = $request->get('tahun');
        $jenis      = $request->get('jenis');
        $jurusan    = $request->get('jurusan1'); // ✅ NEW (aman meski Export class belum pakai)

        return Excel::download(
            new Laporan1Export($tahun, $kolomTahun, $jenis, $jurusan),
            'laporan1' . ($tahun ? "_$tahun" : '') . '.xlsx'
        );
    }

    public function laporan1ExportPdf(Request $request)
    {
        $kolomTahun = $this->getKolomTahun();
        $tahun      = $request->get('tahun');
        $jenis      = $request->get('jenis');
        $jurusan    = $request->get('jurusan1'); // ✅ NEW
        $rekapJurusan = $this->getLaporan1DataFiltered($tahun, $jenis, $kolomTahun, $jurusan);

        // ✅ FIX: ikutkan jenis & jurusan supaya keterangan filter di PDF akurat
        $pdf = Pdf::loadView('admin.laporan.laporan1_pdf', compact('rekapJurusan', 'tahun', 'jenis', 'jurusan'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan1.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Exports Laporan 2
    |--------------------------------------------------------------------------
    */
    public function laporan2ExportExcel(Request $request)
    {
        $kolomTahun = $this->getKolomTahun();
        $filters = [
            'tahun'      => $request->get('tahun2'),
            'jurusan'    => $request->get('jurusan2'),
            'jenis'      => $request->get('jenis2'),
            'jalur'      => $request->get('jalur2'),
            'skema_id'   => $request->get('skema_id2'),
            'kolomTahun' => $kolomTahun,
        ];

        return Excel::download(new Laporan2Export($filters), 'laporan2_detail.xlsx');
    }

    public function laporan2ExportPdf(Request $request)
    {
        $kolomTahun = $this->getKolomTahun();
        $filters = [
            'tahun'      => $request->get('tahun2'),
            'jurusan'    => $request->get('jurusan2'),
            'jenis'      => $request->get('jenis2'),
            'jalur'      => $request->get('jalur2'),
            'skema_id'   => $request->get('skema_id2'),
            'kolomTahun' => $kolomTahun,
        ];

        $data = $this->getLaporan2Data($filters);

        $pdf = Pdf::loadView('admin.laporan.laporan2_pdf', compact('data', 'filters'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan2_detail.pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Konversi nilai luaran (any type) -> string bersih
     */
    public static function luaranToString(mixed $val): string
    {
        if (is_null($val)) return '-';
        if (is_string($val)) return $val === '' ? '-' : $val;
        if (is_array($val)) {
            $parts = [];
            foreach ($val as $item) {
                if (is_array($item)) {
                    $parts[] = $item['nama']
                        ?? $item['jenis']
                        ?? $item['judul']
                        ?? implode(', ', array_map('strval', array_values($item)));
                } else {
                    $parts[] = (string) $item;
                }
            }
            return implode('; ', array_filter($parts)) ?: '-';
        }
        return (string) $val;
    }

    public function getLaporan1DataFiltered(?string $tahun, ?string $jenis, string $kolomTahun, ?string $jurusan = null): \Illuminate\Support\Collection
    {
        return Pengajuan::with(['pegawai', 'skema'])
            ->when($tahun, function ($q) use ($tahun, $kolomTahun) {
                $kolomTahun === 'created_at'
                    ? $q->whereYear('created_at', $tahun)
                    : $q->where($kolomTahun, $tahun);
            })
            ->when($jenis, fn($q) => $q->where('jenis', $jenis))
            ->when($jurusan, fn($q) => $q->whereHas('pegawai', fn($qq) => $qq->where('jurusan', $jurusan))) // ✅ NEW
            ->get()
            ->groupBy(fn($p) => $p->pegawai?->jurusan ?? 'Tidak Diketahui')
            ->map(fn($items, $jurusan) => [
                'jurusan'   => $jurusan,
                'total'     => $items->count(),
                'per_jalur' => $items->groupBy('jalur')->map->count(),
                'per_skema' => $items->groupBy(fn($p) => $p->skema?->nama ?? 'Tanpa Skema')->map->count(),
                'per_jenis' => $items->groupBy('jenis')->map->count(),
                'items'     => $items,
            ])
            ->sortByDesc('total');
            // ⚠️ ->values() DIHAPUS supaya key tetap nama jurusan (bukan 0,1,2...)
    }

    public function getLaporan1Data(?string $tahun, string $kolomTahun = 'tahun_pengajuan'): \Illuminate\Support\Collection
    {
        return Pengajuan::with(['pegawai', 'skema'])
            ->when($tahun, function ($q) use ($tahun, $kolomTahun) {
                if ($kolomTahun === 'created_at') {
                    $q->whereYear('created_at', $tahun);
                } else {
                    $q->where($kolomTahun, $tahun);
                }
            })
            ->get()
            ->groupBy(fn($p) => $p->pegawai?->jurusan ?? 'Tidak Diketahui')
            ->map(function ($items, $jurusan) {
                return [
                    'jurusan'   => $jurusan,
                    'total'     => $items->count(),
                    'per_jalur' => $items->groupBy('jalur')->map->count(),
                    'per_skema' => $items->groupBy(fn($p) => $p->skema?->nama ?? 'Tanpa Skema')->map->count(),
                    'per_jenis' => $items->groupBy('jenis')->map->count(),
                    'items'     => $items,
                ];
            })
            ->sortKeys();
    }

    public function getLaporan2Data(array $filters): \Illuminate\Support\Collection
    {
        $kolomTahun = $filters['kolomTahun'] ?? $this->getKolomTahun();

        return Pengajuan::with([
                'pegawai',
                'anggotas.pegawai',
                'skema',
                'laporanKemajuan',
                'laporanHasil',
                // ✅ FIX: load nama luaran (luaranMaster) & detail realisasi yang diinput dosen
                'luaran.luaranMaster',
                'luaran.realisasi',
            ])
            ->when($filters['tahun'] ?? null, function ($q) use ($filters, $kolomTahun) {
                if ($kolomTahun === 'created_at') {
                    $q->whereYear('created_at', $filters['tahun']);
                } else {
                    $q->where($kolomTahun, $filters['tahun']);
                }
            })
            ->when($filters['jurusan']  ?? null, fn($q) => $q->whereHas('pegawai', fn($qq) => $qq->where('jurusan', $filters['jurusan'])))
            ->when($filters['jenis']    ?? null, fn($q) => $q->where('jenis',    $filters['jenis']))
            ->when($filters['jalur']    ?? null, fn($q) => $q->where('jalur',    $filters['jalur']))
            ->when($filters['skema_id'] ?? null, fn($q) => $q->where('skema_id', $filters['skema_id']))
            ->orderByDesc('created_at')
            ->get();
    }

    public function getTahunList(string $kolomTahun = ''): array
    {
        if (empty($kolomTahun)) {
            $kolomTahun = $this->getKolomTahun();
        }

        if ($kolomTahun === 'created_at') {
            return Pengajuan::selectRaw('YEAR(created_at) as tahun')
                ->distinct()
                ->orderByDesc('tahun')
                ->pluck('tahun')
                ->toArray();
        }

        return Pengajuan::select($kolomTahun)
            ->distinct()
            ->orderByDesc($kolomTahun)
            ->pluck($kolomTahun)
            ->toArray();
    }

    private function getJurusanList(): array
    {
        return Pegawai::select('jurusan')
            ->distinct()
            ->whereNotNull('jurusan')
            ->where('jurusan', '!=', '')
            ->orderBy('jurusan')
            ->pluck('jurusan')
            ->toArray();
    }
}

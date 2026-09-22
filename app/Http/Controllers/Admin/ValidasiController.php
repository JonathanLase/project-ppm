<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\LaporanKemajuan;
use App\Models\LaporanHasil;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\RiwayatValidasi;

class ValidasiController extends Controller
{
    // ==========================================
    // BAGIAN: VALIDASI PROPOSAL
    // ==========================================
    public function index(Request $request)
    {
        $title = 'Daftar Validasi Proposal';

        $query = Pengajuan::with(['pegawai', 'skema', 'rumpunIlmu', 'validator']);

        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $pengajuans = $query->paginate(10)->withQueryString();

        return view('Admin.validasi.proposal', compact('title', 'pengajuans'));
    }

    public function proposal($id)
    {
        $title = 'Validasi Proposal';

        $pengajuan = Pengajuan::with([
            'pegawai',
            'skema',
            'rumpunIlmu',
            'tim.pegawai',
            'luaran.luaranMaster',
            'validator',
            'riwayatValidasi.admin', // <-- semua riwayat untuk timeline tracking
        ])->findOrFail($id);

        $selected = $pengajuan;

        return view('Admin.validasi.proposal_detail', compact('title', 'pengajuan', 'selected'));
    }

    public function updateValidasi(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan'   => 'nullable|string|required_if:keputusan,revisi',
        ], [
            'catatan.required_if' => 'Catatan revisi wajib diisi saat memilih keputusan "Perlu Revisi".',
        ]);

        $pengajuan  = Pengajuan::findOrFail($id);
        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';

        // Tabel utama tetap di-update supaya status TERKINI & alur lama tidak berubah
        $dataUpdate = [
            'status'            => $statusBaru,
            'catatan_validator' => $request->catatan,
            'divalidasi_oleh'   => auth()->id(),
            'divalidasi_pada'   => now(),
        ];

        // Jalur Mandiri hanya 2 tahap (Proposal -> Laporan Hasil)
        if ($statusBaru === 'disetujui') {
            $dataUpdate['tahap'] = $pengajuan->jalur === 'mandiri' ? 'laporan_hasil' : 'laporan_kemajuan';
        }

        $pengajuan->update($dataUpdate);

        // >>> RIWAYAT: selalu INSERT baris baru, tidak pernah menimpa yang lama <<<
        RiwayatValidasi::catat($pengajuan, $statusBaru, $request->catatan, 'proposal');

        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Melakukan validasi proposal "' . $pengajuan->judul . '" dengan hasil: ' . strtoupper($statusBaru),
            'tipe'      => 'validasi',
        ]);

        Notification::create([
            'user_id' => $pengajuan->pegawai_id,
            'type'    => 'validasi',
            'title'   => $statusBaru === 'disetujui' ? 'Proposal Disetujui' : 'Proposal Perlu Direvisi',
            'message' => $statusBaru === 'disetujui'
                ? ('Proposal "' . $pengajuan->judul . '" telah disetujui. Silakan lanjutkan ke tahap '
                    . ($pengajuan->jalur === 'mandiri' ? 'Laporan Hasil.' : 'Laporan Kemajuan.'))
                : 'Proposal "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
        ]);

        return redirect()->route('admin.validasi.proposal')->with('success', 'Keputusan proposal berhasil dikirim dan status diperbarui!');
    }

    // ==========================================
    // BAGIAN: VALIDASI LAPORAN KEMAJUAN
    // ==========================================
    public function kemajuanIndex(Request $request)
    {
        $title = 'Daftar Validasi Laporan Kemajuan';

        $query = LaporanKemajuan::with(['pengajuan.pegawai', 'pengajuan.skema', 'validator']);

        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $laporans = $query->paginate(10)->withQueryString();

        return view('Admin.validasi.laporan_kemajuan', compact('title', 'laporans'));
    }

    public function kemajuanDetail($id)
    {
        $title = 'Validasi Laporan Kemajuan';

        $laporan = LaporanKemajuan::with([
            'pengajuan.pegawai',
            'pengajuan.skema',
            'validator',
            'riwayatValidasi.admin',
        ])->findOrFail($id);

        $selected = $laporan;

        return view('Admin.validasi.laporan_kemajuan_detail', compact('title', 'laporan', 'selected'));
    }

    public function kemajuanUpdate(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan'   => 'nullable|string|required_if:keputusan,revisi',
        ], [
            'catatan.required_if' => 'Catatan revisi wajib diisi saat memilih keputusan "Perlu Revisi".',
        ]);

        $laporan    = LaporanKemajuan::findOrFail($id);
        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';

        $laporan->update([
            'status'            => $statusBaru,
            'catatan_validator' => $request->catatan,
            'divalidasi_oleh'   => auth()->id(),
            'divalidasi_pada'   => now(),
        ]);

        // >>> RIWAYAT: INSERT baris baru <<<
        RiwayatValidasi::catat($laporan, $statusBaru, $request->catatan, 'laporan_kemajuan');

        $pengajuan = $laporan->pengajuan;
        if ($statusBaru === 'disetujui' && $pengajuan) {
            $pengajuan->update(['tahap' => 'laporan_hasil']);
        }

        $judulPengajuan = $pengajuan->judul ?? 'Laporan Kemajuan';
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Melakukan validasi laporan kemajuan untuk "' . $judulPengajuan . '" dengan hasil: ' . strtoupper($statusBaru),
            'tipe'      => 'validasi',
        ]);

        if ($pengajuan) {
            Notification::create([
                'user_id' => $pengajuan->pegawai_id,
                'type'    => 'laporan_kemajuan',
                'title'   => $statusBaru === 'disetujui' ? 'Laporan Kemajuan Disetujui' : 'Laporan Kemajuan Perlu Direvisi',
                'message' => $statusBaru === 'disetujui'
                    ? 'Laporan kemajuan untuk "' . $pengajuan->judul . '" telah disetujui. Silakan lanjutkan ke tahap Laporan Hasil.'
                    : 'Laporan kemajuan untuk "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
            ]);
        }

        return redirect()->route('admin.validasi.laporan-kemajuan')->with('success', 'Keputusan laporan kemajuan berhasil dikirim!');
    }

    // ==========================================
    // BAGIAN: VALIDASI LAPORAN HASIL
    // ==========================================
    public function hasilIndex(Request $request)
    {
        $title = 'Daftar Validasi Laporan Hasil';

        $query = LaporanHasil::with(['pengajuan.pegawai', 'pengajuan.skema', 'validator']);

        $sort = $request->get('sort', 'latest');
        if ($sort == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $laporans = $query->paginate(10)->withQueryString();

        return view('Admin.validasi.laporan_hasil', compact('title', 'laporans'));
    }

    public function hasilDetail($id)
    {
        $title = 'Validasi Laporan Hasil';

        $laporan = LaporanHasil::with([
            'pengajuan.pegawai',
            'pengajuan.skema',
            'validator',
            'riwayatValidasi.admin',
        ])->findOrFail($id);

        $selected = $laporan;

        return view('Admin.validasi.laporan_hasil_detail', compact('title', 'laporan', 'selected'));
    }

    public function hasilUpdate(Request $request, $id)
    {
        $request->validate([
            'keputusan' => 'required|in:setuju,revisi',
            'catatan'   => 'nullable|string|required_if:keputusan,revisi',
        ], [
            'catatan.required_if' => 'Catatan revisi wajib diisi saat memilih keputusan "Perlu Revisi".',
        ]);

        $laporan    = LaporanHasil::findOrFail($id);
        $statusBaru = $request->keputusan == 'setuju' ? 'disetujui' : 'revisi';

        $laporan->update([
            'status'            => $statusBaru,
            'catatan_validator' => $request->catatan,
            'divalidasi_oleh'   => auth()->id(),
            'divalidasi_pada'   => now(),
        ]);

        // >>> RIWAYAT: INSERT baris baru <<<
        RiwayatValidasi::catat($laporan, $statusBaru, $request->catatan, 'laporan_hasil');

        $pengajuan = $laporan->pengajuan;

        $judulPengajuan = $pengajuan->judul ?? 'Laporan Hasil';
        ActivityLog::create([
            'user_id'   => auth()->id(),
            'aktivitas' => 'Melakukan validasi laporan hasil untuk "' . $judulPengajuan . '" dengan hasil: ' . strtoupper($statusBaru),
            'tipe'      => 'validasi',
        ]);

        if ($pengajuan) {
            Notification::create([
                'user_id' => $pengajuan->pegawai_id,
                'type'    => 'laporan',
                'title'   => $statusBaru === 'disetujui' ? 'Laporan Hasil Disetujui' : 'Laporan Hasil Perlu Direvisi',
                'message' => $statusBaru === 'disetujui'
                    ? 'Laporan hasil untuk "' . $pengajuan->judul . '" telah disetujui. Kegiatan penelitian/pengabdian ini telah selesai divalidasi.'
                    : 'Laporan hasil untuk "' . $pengajuan->judul . '" perlu direvisi. Silakan cek catatan validator.',
            ]);
        }

        return redirect()->route('admin.validasi.laporan_hasil')->with('success', 'Keputusan laporan hasil berhasil dikirim!');
    }
}

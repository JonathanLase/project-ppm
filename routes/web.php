 <?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\Dosen\AuthController as DosenAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\Admin\ValidasiController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LuaranController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Redirect Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes (Dosen & Admin Terpisah)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // Login Dosen
    Route::get('/login', [DosenAuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [DosenAuthController::class, 'login'])
        ->name('login.submit');


    // Login Admin
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('admin.login');

    Route::post('/admin/login', [AdminAuthController::class, 'login'])
        ->name('admin.login.submit');
});


/*
|--------------------------------------------------------------------------
| Logout Dosen
|--------------------------------------------------------------------------
*/

Route::post('/logout', [DosenAuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Berkas (Preview / Download File)
| Wajib Login, Dosen & Admin
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/berkas', [BerkasController::class, 'show'])
        ->name('berkas.show');
});


/*
|--------------------------------------------------------------------------
| Area Dosen
| Wajib Login + Role Dosen
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:dosen'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Dosen
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Ubah Password
    |--------------------------------------------------------------------------
    */

    Route::get('/ubah-password', [DosenAuthController::class, 'showUbahPassword'])
        ->name('ubah-password');

    Route::post('/ubah-password', [DosenAuthController::class, 'ubahPassword'])
        ->name('ubah-password.submit');


    /*
    |--------------------------------------------------------------------------
    | Profil
    |--------------------------------------------------------------------------
    */

    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil');

    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');

    Route::post('/profil/foto', [ProfilController::class, 'updateFoto'])
        ->name('profil.foto');

});

/*
|--------------------------------------------------------------------------
| Area Bersama Dosen & Petugas PPM
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen,petugas_ppm'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Pengajuan Baru
    |--------------------------------------------------------------------------
    */

    Route::prefix('pengajuan-baru')->group(function () {

        // Step 1
        Route::get('/', [PengajuanController::class, 'step1'])
            ->name('pengajuan.step1');

        Route::post('/', [PengajuanController::class, 'postStep1'])
            ->name('pengajuan.step1.post');

        // Daftar (tabel) Pengajuan Proposal
        Route::get('/pengajuan-proposal', [PengajuanController::class, 'daftar'])
            ->name('pengajuan.daftar');


        // Step 2 - Tim
        Route::get('/tim', [PengajuanController::class, 'step2'])
            ->name('pengajuan.step2');

        Route::post('/tim', [PengajuanController::class, 'postStep2'])
            ->name('pengajuan.step2.post');


        // Step 3 - Proposal
        Route::get('/proposal', [PengajuanController::class, 'step3'])
            ->name('pengajuan.step3');

        Route::post('/proposal', [PengajuanController::class, 'postStep3'])
            ->name('pengajuan.step3.post');


        // Step 4 - Luaran
        Route::get('/luaran', [PengajuanController::class, 'step4'])
            ->name('pengajuan.step4');

        Route::post('/luaran', [PengajuanController::class, 'postStep4'])
            ->name('pengajuan.step4.post');


        // Step 5 - Review
        Route::get('/review', [PengajuanController::class, 'step5'])
            ->name('pengajuan.step5');


        // Kirim Pengajuan
        Route::post('/kirim', [PengajuanController::class, 'submit'])
            ->name('pengajuan.submit');


        // Batalkan Pengajuan
        Route::post('/batal', [PengajuanController::class, 'batal'])
            ->name('pengajuan.batal');


        // Halaman Sukses
        Route::get('/sukses', [PengajuanController::class, 'sukses'])
            ->name('pengajuan.sukses');
    });


    /*
    |--------------------------------------------------------------------------
    | Riwayat Pengajuan
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat', [RiwayatController::class, 'index'])
        ->name('riwayat');

    Route::get('/pengajuan/{pengajuan}', [RiwayatController::class, 'detail'])
        ->name('pengajuan.detail');


    /*
    |--------------------------------------------------------------------------
    | Edit Pengajuan
    |--------------------------------------------------------------------------
    */

    Route::get('/pengajuan/{pengajuan}/edit', [PengajuanController::class, 'edit'])
        ->name('pengajuan.edit');

    Route::put('/pengajuan/{pengajuan}', [PengajuanController::class, 'update'])
        ->name('pengajuan.update');


    /*
    |--------------------------------------------------------------------------
    | Laporan Kemajuan
    |--------------------------------------------------------------------------
    */

    // Daftar (tabel) kegiatan
    Route::get('/laporan/kemajuan', [LaporanController::class, 'kemajuan'])
        ->name('laporan.kemajuan');

    // Form isi / lanjutkan draft / edit revisi
    Route::get('/laporan/kemajuan/{pengajuan}/isi', [LaporanController::class, 'kemajuanForm'])
        ->name('laporan.kemajuan.form');

    // Detail read-only
    Route::get('/laporan/kemajuan/{pengajuan}/detail', [LaporanController::class, 'kemajuanDetail'])
        ->name('laporan.kemajuan.detail');

    Route::post('/laporan/kemajuan/{pengajuan}', [LaporanController::class, 'kemajuanStore'])
        ->name('laporan.kemajuan.store');

    Route::get('/laporan/kemajuan/{pengajuan}/sukses', [LaporanController::class, 'kemajuanSukses'])
        ->name('laporan.kemajuan.sukses');

    Route::post('/laporan/kemajuan/{pengajuan}/hapus-file', [LaporanController::class, 'kemajuanHapusFile'])
        ->name('laporan.kemajuan.hapus-file');

    Route::post('/laporan/kemajuan/{pengajuan}/hapus-dokumentasi/{index}', [LaporanController::class, 'kemajuanHapusDokumentasi'])
        ->name('laporan.kemajuan.hapus-dokumentasi');


    /*
    |--------------------------------------------------------------------------
    | Laporan Hasil
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan/{tipe}', [LaporanController::class, 'index'])
        ->whereIn('tipe', ['hasil'])
        ->name('laporan.index');

    Route::get('/laporan/{tipe}/{pengajuan}', [LaporanController::class, 'form'])
        ->whereIn('tipe', ['hasil'])
        ->name('laporan.form');

    Route::post('/laporan/{tipe}/{pengajuan}', [LaporanController::class, 'store'])
        ->whereIn('tipe', ['hasil'])
        ->name('laporan.store');

    Route::get('/laporan/hasil/{pengajuan}/sukses', [LaporanController::class, 'sukses'])
        ->name('laporan.hasil.sukses');

    Route::post('/laporan/hasil/{pengajuan}/hapus-file', [LaporanController::class, 'hasilHapusFile'])
        ->name('laporan.hasil.hapus-file');

    Route::post('/laporan/hasil/{pengajuan}/hapus-kwitansi', [LaporanController::class, 'hasilHapusKwitansi'])
        ->name('laporan.hasil.hapus-kwitansi');

    Route::post('/laporan/hasil/{pengajuan}/hapus-bukti-pajak', [LaporanController::class, 'hasilHapusBuktiPajak'])
        ->name('laporan.hasil.hapus-bukti-pajak');

    Route::post('/laporan/hasil/{pengajuan}/hapus-berita-acara', [LaporanController::class, 'hasilHapusBeritaAcara'])
        ->name('laporan.hasil.hapus-berita-acara');

    Route::post('/laporan/hasil/{pengajuan}/hapus-dokumentasi/{index}', [LaporanController::class, 'hasilHapusDokumentasi'])
        ->name('laporan.hasil.hapus-dokumentasi');


    /*
    |--------------------------------------------------------------------------
    | Luaran
    |--------------------------------------------------------------------------
    */

    Route::get('/luaran', [LuaranController::class, 'index'])
        ->name('luaran.index');

    Route::get('/luaran/{luaran}', [LuaranController::class, 'form'])
        ->name('luaran.form');

    Route::post('/luaran/{luaran}', [LuaranController::class, 'store'])
        ->name('luaran.store');


    /*
    |--------------------------------------------------------------------------
    | Notifikasi Dosen
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifikasi',
        [\App\Http\Controllers\Dosen\NotificationController::class, 'index']
    )->name('notifikasi');

    Route::post(
        '/notifikasi/read-all',
        [\App\Http\Controllers\Dosen\NotificationController::class, 'markAllAsRead']
    )->name('notifikasi.readAll');

    Route::post(
        '/notifikasi/{id}/read',
        [\App\Http\Controllers\Dosen\NotificationController::class, 'markAsRead']
    )->name('notifikasi.read');
});


/*
|--------------------------------------------------------------------------
| Area Admin
| Wajib Login + Role Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,petugas_ppm'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout Admin
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');


    /*
    |--------------------------------------------------------------------------
    | Dashboard Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Menu Pengajuan (Penelitian / Pengabdian / Semua)
    |--------------------------------------------------------------------------
    */

    // Panggil method penelitian()/pengabdian() (auto-filter jenis),
    // bukan semua() untuk keduanya.
    Route::get('/penelitian', [AdminPengajuanController::class, 'penelitian'])
        ->name('penelitian');

    Route::get('/pengabdian', [AdminPengajuanController::class, 'pengabdian'])
        ->name('pengabdian');

    Route::get('/semua-pengajuan', [AdminPengajuanController::class, 'semua'])
        ->name('semua-pengajuan');


    /*
    |--------------------------------------------------------------------------
    | Dokumen Proposal
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pengajuan/{id}/dokumen',
        [AdminPengajuanController::class, 'showDokumen']
    )->name('pengajuan.dokumen');

    Route::get(
        '/pengajuan/{id}/download',
        [AdminPengajuanController::class, 'downloadDokumen']
    )->name('pengajuan.download');


    /*
    |--------------------------------------------------------------------------
    | Dokumen Laporan Kemajuan
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/laporan-kemajuan/{id}/dokumen',
        [AdminPengajuanController::class, 'showLaporanKemajuan']
    )->name('laporan-kemajuan.dokumen');

    Route::get(
        '/laporan-kemajuan/{id}/download',
        [AdminPengajuanController::class, 'downloadLaporanKemajuan']
    )->name('laporan-kemajuan.download');


    /*
    |--------------------------------------------------------------------------
    | Dokumen Laporan Hasil
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/laporan-hasil/{id}/dokumen',
        [AdminPengajuanController::class, 'showLaporanHasil']
    )->name('laporan-hasil.dokumen');

    Route::get(
        '/laporan-hasil/{id}/download',
        [AdminPengajuanController::class, 'downloadLaporanHasil']
    )->name('laporan-hasil.download');


    /*
    |--------------------------------------------------------------------------
    | Validasi
    |--------------------------------------------------------------------------
    */

    Route::prefix('validasi')
        ->name('validasi.')
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Validasi Proposal
        | (Rute {id} diletakkan lebih dulu supaya tidak bentrok dengan index)
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/proposal/{id}',
            [ValidasiController::class, 'proposal']
        )->name('proposal.detail');

        Route::post(
            '/update/{id}',
            [ValidasiController::class, 'updateValidasi']
        )->name('proposal.update');

        Route::get(
            '/proposal',
            [ValidasiController::class, 'index']
        )->name('proposal.index');

        Route::get(
            '/proposal',
            [ValidasiController::class, 'index']
        )->name('proposal');


        /*
        |--------------------------------------------------------------------------
        | Validasi Laporan Kemajuan
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan-kemajuan/{id}',
            [ValidasiController::class, 'kemajuanDetail']
        )->name('laporan-kemajuan.detail');

        Route::post(
            '/laporan-kemajuan/{id}',
            [ValidasiController::class, 'kemajuanUpdate']
        )->name('laporan-kemajuan.update');

        Route::get(
            '/laporan-kemajuan',
            [ValidasiController::class, 'kemajuanIndex']
        )->name('laporan-kemajuan');


        /*
        |--------------------------------------------------------------------------
        | Validasi Laporan Hasil
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/laporan_hasil/{id}',
            [ValidasiController::class, 'hasilDetail']
        )->name('laporan_hasil.detail');

        Route::post(
            '/laporan_hasil/{id}',
            [ValidasiController::class, 'hasilUpdate']
        )->name('laporan_hasil.update');

        Route::get(
            '/laporan_hasil',
            [ValidasiController::class, 'hasilIndex']
        )->name('laporan_hasil');
    });


    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::prefix('master')
        ->name('master.')
        ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Skema
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/skema',
            [MasterDataController::class, 'skemaIndex']
        )->name('skema');

        Route::post(
            '/skema',
            [MasterDataController::class, 'skemaStore']
        )->name('skema.store');

        Route::put(
            '/skema/{id}',
            [MasterDataController::class, 'skemaUpdate']
        )->name('skema.update');

        Route::delete(
            '/skema/{id}',
            [MasterDataController::class, 'skemaDestroy']
        )->name('skema.destroy');


        /*
        |--------------------------------------------------------------------------
        | Pegawai
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pegawai',
            [MasterDataController::class, 'pegawaiIndex']
        )->name('pegawai');

        Route::post(
            '/pegawai/import',
            [MasterDataController::class, 'pegawaiImport']
        )->name('pegawai.import');

        Route::get(
            '/pegawai/template',
            [MasterDataController::class, 'pegawaiDownloadTemplate']
        )->name('pegawai.template');

        Route::post(
            '/pegawai',
            [MasterDataController::class, 'pegawaiStore']
        )->name('pegawai.store');

        Route::put(
            '/pegawai/{id}',
            [MasterDataController::class, 'pegawaiUpdate']
        )->name('pegawai.update');

        Route::delete(
            '/pegawai/{id}',
            [MasterDataController::class, 'pegawaiDestroy']
        )->name('pegawai.destroy');


        /*
        |--------------------------------------------------------------------------
        | Rumpun Ilmu
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/rumpun-ilmu',
            [MasterDataController::class, 'rumpunIlmuIndex']
        )->name('rumpun');

        Route::post(
            '/rumpun-ilmu',
            [MasterDataController::class, 'rumpunStore']
        )->name('rumpun.store');

        Route::put(
            '/rumpun-ilmu/{id}',
            [MasterDataController::class, 'rumpunUpdate']
        )->name('rumpun.update');

        Route::delete(
            '/rumpun-ilmu/{id}',
            [MasterDataController::class, 'rumpunDestroy']
        )->name('rumpun.destroy');


        /*
        |--------------------------------------------------------------------------
        | Luaran
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/luaran',
            [MasterDataController::class, 'luaranIndex']
        )->name('luaran');

        Route::post(
            '/luaran',
            [MasterDataController::class, 'luaranStore']
        )->name('luaran.store');

        Route::put(
            '/luaran/{id}',
            [MasterDataController::class, 'luaranUpdate']
        )->name('luaran.update');

        Route::delete(
            '/luaran/{id}',
            [MasterDataController::class, 'luaranDestroy']
        )->name('luaran.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | Laporan Admin
    |--------------------------------------------------------------------------
    */

    Route::get('/laporan', [\App\Http\Controllers\Admin\LaporanAdminController::class, 'index'])
        ->name('laporan');

    Route::get('/laporan/export-laporan1-excel', [\App\Http\Controllers\Admin\LaporanAdminController::class, 'laporan1ExportExcel'])
        ->name('laporan.laporan1.export.excel');

    Route::get('/laporan/export-laporan1-pdf', [\App\Http\Controllers\Admin\LaporanAdminController::class, 'laporan1ExportPdf'])
        ->name('laporan.laporan1.export.pdf');

    Route::get('/laporan/export-laporan2-excel', [\App\Http\Controllers\Admin\LaporanAdminController::class, 'laporan2ExportExcel'])
        ->name('laporan.laporan2.export.excel');

    Route::get('/laporan/export-laporan2-pdf', [\App\Http\Controllers\Admin\LaporanAdminController::class, 'laporan2ExportPdf'])
        ->name('laporan.laporan2.export.pdf');


    /*
    |--------------------------------------------------------------------------
    | Activity Log
    |--------------------------------------------------------------------------
    */

    Route::get('/activity-log', [ActivityLogController::class, 'index'])
        ->name('activity_log');

    Route::delete('/activity-log/clear', [ActivityLogController::class, 'clear'])
        ->name('activity_log.clear');


    /*
    |--------------------------------------------------------------------------
    | Notifikasi Admin
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifikasi',
        [NotificationController::class, 'index']
    )->name('notifikasi');

    Route::post(
        '/notifikasi/read-all',
        [NotificationController::class, 'markAllAsRead']
    )->name('notifikasi.readAll');

    Route::post(
        '/notifikasi/{id}/read',
        [NotificationController::class, 'markAsRead']
    )->name('notifikasi.read');
});


/*
|--------------------------------------------------------------------------
| Area Petugas PPM
| Wajib Login + Role Petugas PPM
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:petugas_ppm'])
    ->prefix('petugas')
    ->name('petugas.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard Petugas
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profil Petugas
    |--------------------------------------------------------------------------
    */
    Route::get('/profil', [ProfilController::class, 'index'])
        ->name('profil');
    Route::put('/profil', [ProfilController::class, 'update'])
        ->name('profil.update');
    Route::post('/profil/foto', [ProfilController::class, 'updateFoto'])
        ->name('profil.foto');

    Route::get('/ubah-password', [DosenAuthController::class, 'showUbahPassword'])
        ->name('ubah-password');
    Route::post('/ubah-password', [DosenAuthController::class, 'ubahPassword'])
        ->name('ubah-password.submit');
});

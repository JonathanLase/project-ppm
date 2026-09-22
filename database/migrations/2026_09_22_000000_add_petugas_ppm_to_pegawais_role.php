<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Tambahkan nilai 'petugas_ppm' ke enum role di tabel pegawais.
     * MySQL tidak mendukung ALTER ENUM langsung lewat Blueprint,
     * jadi kita pakai raw SQL.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pegawais MODIFY COLUMN role ENUM('admin', 'dosen', 'petugas_ppm') NOT NULL DEFAULT 'dosen'");
    }

    public function down(): void
    {
        // Kembalikan ke enum semula (hapus petugas_ppm)
        // Pastikan tidak ada data dengan role petugas_ppm sebelum rollback
        DB::statement("ALTER TABLE pegawais MODIFY COLUMN role ENUM('admin', 'dosen') NOT NULL DEFAULT 'dosen'");
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('arsip_sks', function (Blueprint $table) {
            $table->id();
            $table->string('no_sk', 100);
            $table->year('tahun');
            $table->enum('jenis', ['penelitian', 'pengabdian']);
            $table->enum('jalur', ['simlitabkes', 'mandiri']);
            $table->foreignId('skema_id')->nullable()->constrained('skema')->nullOnDelete();
            $table->string('file_path');
            $table->string('file_nama_asli')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('pegawais')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_sks');
    }
};

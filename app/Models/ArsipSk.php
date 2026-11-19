<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipSk extends Model
{
    protected $table = 'arsip_sks';

    protected $fillable = [
        'no_sk',
        'tahun',
        'jenis',
        'jalur',
        'skema_id',
        'file_path',
        'file_nama_asli',
        'file_size',
        'keterangan',
        'uploaded_by',
    ];

    public function skema()
    {
        return $this->belongsTo(Skema::class, 'skema_id');
    }

    public function uploader()
    {
        return $this->belongsTo(Pegawai::class, 'uploaded_by');
    }

    public function jenisLabel(): string
    {
        return match ($this->jenis) {
            'penelitian' => 'Penelitian',
            'pengabdian' => 'Pengabdian Masyarakat',
            default => ucfirst($this->jenis),
        };
    }

    public function jalurLabel(): string
    {
        return match ($this->jalur) {
            'simlitabkes' => 'Simlitabkes',
            'mandiri' => 'Mandiri',
            default => ucfirst($this->jalur),
        };
    }

    public function fileSizeFormatted(): string
    {
        if (!$this->file_size) {
            return '-';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($this->file_size, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skema extends Model
{
    protected $table = 'skema';

    protected $fillable = ['jenis', 'jalur', 'nama', 'kode', 'aktif'];

    protected function casts(): array
    {
        return ['aktif' => 'boolean'];
    }

    public function pengajuan()
    {
        return $this->hasMany(Pengajuan::class);
    }

    public function arsipSk()
    {
        return $this->hasMany(ArsipSk::class, 'skema_id');
    }
}

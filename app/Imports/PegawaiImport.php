<?php

namespace App\Imports;

use App\Models\Pegawai;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PegawaiImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            // Lewati baris kosong
            if (empty($row['nip'])) {
                continue;
            }

            // Tentukan password: ambil dari file jika ada, atau gunakan NIP / default
            $passwordSource = !empty($row['password']) ? $row['password'] : ($row['nip'] ?? 'password123');

            Pegawai::updateOrCreate(
                ['nip' => $row['nip']],
                [
                    'nama'                 => $row['nama'] ?? $row['nama_lengkap'] ?? '-',
                    'password'             => Hash::make($passwordSource),
                    'role'                 => $row['role'] ?? 'dosen',
                    'jabatan'              => $row['jabatan'] ?? null,
                    'pangkat'              => $row['pangkat'] ?? null,
                    'jurusan'              => $row['jurusan'] ?? null,
                    'prodi'                => $row['prodi'] ?? null,
                    'email'                => $row['email'] ?? null,
                    'hp'                   => $row['hp'] ?? $row['no_hp'] ?? null,
                    'nidn'                 => $row['nidn'] ?? null,
                    'must_change_password' => 1,
                ]
            );
        }
    }

    public function chunkSize(): int
    {
        return 100; // Memproses 100 baris per batch untuk menghemat memori & waktu
    }
}

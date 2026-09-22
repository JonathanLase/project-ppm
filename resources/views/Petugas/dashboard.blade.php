@extends('layouts.petugas')

@section('title', 'Dashboard Petugas PPM')
@section('header_title', 'Dashboard')
@section('header_breadcrumb', 'Petugas PPM / Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

    {{-- Stat: Total Pengajuan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-file-alt text-emerald-600 text-xl"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $stats->total_pengajuan }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-0.5">Total Pengajuan</div>
        </div>
    </div>

    {{-- Stat: Menunggu Validasi --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-hourglass-half text-amber-500 text-xl"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $stats->menunggu_validasi }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-0.5">Menunggu Validasi</div>
        </div>
    </div>

    {{-- Stat: Disetujui --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-check text-sky-500 text-xl"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $stats->disetujui }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-0.5">Disetujui</div>
        </div>
    </div>

    {{-- Stat: Revisi / Ditolak --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center shrink-0">
            <i class="fa-solid fa-circle-xmark text-rose-500 text-xl"></i>
        </div>
        <div>
            <div class="text-2xl font-extrabold text-slate-900">{{ $stats->revisi_ditolak }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-0.5">Revisi / Ditolak</div>
        </div>
    </div>
</div>

{{-- Tabel Pengajuan Terbaru --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-extrabold text-slate-900 text-sm">Pengajuan Terbaru</h2>
            <p class="text-xs text-slate-400 mt-0.5">5 pengajuan terakhir yang masuk</p>
        </div>
        <a href="{{ route('admin.semua-pengajuan') }}"
            class="text-xs text-emerald-700 font-bold hover:underline">Lihat Semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-xs">
            <thead>
                <tr class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wide text-[10px]">
                    <th class="px-6 py-3 text-left">Judul</th>
                    <th class="px-6 py-3 text-left">Pengaju</th>
                    <th class="px-6 py-3 text-left">Skema</th>
                    <th class="px-6 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pengajuanTerbaru as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-3 font-medium text-slate-800 max-w-xs truncate">{{ $p->judul ?? '-' }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $p->pegawai->nama ?? '-' }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $p->skema->nama ?? '-' }}</td>
                        <td class="px-6 py-3">
                            @php
                                $statusMap = [
                                    'menunggu'    => ['text' => 'Menunggu',    'color' => 'bg-amber-100 text-amber-700'],
                                    'proses'      => ['text' => 'Diproses',    'color' => 'bg-sky-100 text-sky-700'],
                                    'disetujui'   => ['text' => 'Disetujui',   'color' => 'bg-emerald-100 text-emerald-700'],
                                    'revisi'      => ['text' => 'Revisi',      'color' => 'bg-orange-100 text-orange-700'],
                                    'ditolak'     => ['text' => 'Ditolak',     'color' => 'bg-rose-100 text-rose-700'],
                                    'perbaikan'   => ['text' => 'Perbaikan',   'color' => 'bg-purple-100 text-purple-700'],
                                    'perlu_revisi'=> ['text' => 'Perlu Revisi','color' => 'bg-yellow-100 text-yellow-700'],
                                ];
                                $s = $statusMap[$p->status] ?? ['text' => ucfirst($p->status), 'color' => 'bg-slate-100 text-slate-600'];
                            @endphp
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $s['color'] }}">{{ $s['text'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada pengajuan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

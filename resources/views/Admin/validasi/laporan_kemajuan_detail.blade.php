@extends('layouts.admin')

@section('title', $title ?? 'Detail Validasi Laporan Kemajuan - SIPPM')
@section('header_title', 'Validasi Laporan Kemajuan')
@section('header_breadcrumb', 'Menu Admin / Validasi / Laporan Kemajuan / Detail')

@section('content')
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('admin.validasi.laporan-kemajuan') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            <i class="fa-solid fa-arrow-left text-emerald-800"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KOLOM KIRI: Informasi Pengajuan, Capaian, Luaran, & Dokumen -->
        <div class="lg:col-span-7 space-y-5">

            <!-- Informasi Pengajuan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Informasi Pengajuan</h3>

                <div class="grid grid-cols-1 gap-3.5">
                    <div>
                        <span
                            class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Judul</span>
                        <span
                            class="font-bold text-slate-900 text-xs leading-snug block">{{ $selected->pengajuan->judul ?? '-' }}</span>
                    </div>

                    <div>
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Ketua
                            Peneliti</span>
                        <span
                            class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->pegawai->nama ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Jenis
                                Kegiatan</span>
                            <span
                                class="font-bold text-slate-900 text-xs block">{{ ucfirst($selected->pengajuan->jenis ?? '-') }}</span>
                        </div>
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Jalur</span>
                            <span
                                class="font-bold text-slate-900 text-xs block">{{ ucfirst($selected->pengajuan->jalur ?? '-') }}</span>
                        </div>
                    </div>

                    <div>
                        <span
                            class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Skema</span>
                        <span
                            class="font-bold text-slate-900 text-xs block">{{ $selected->pengajuan->skema->nama ?? '-' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Tanggal
                                Dikirim / Diajukan</span>
                            <span
                                class="font-bold text-slate-900 text-xs block">{{ $selected->created_at ? $selected->created_at->format('d F Y, H:i') : '-' }}</span>
                        </div>
                        <div>
                            <span
                                class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-0.5">Status
                                Saat Ini</span>
                            <span
                                class="inline-block mt-0.5 px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                {{ ucfirst($selected->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Capaian Kegiatan -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Capaian Kegiatan</h3>

                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-1">Kegiatan yang
                        Telah Dilakukan</span>
                    <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $selected->kegiatan_dilakukan ?? '-' }}
                    </p>
                </div>
                <div>
                    <span
                        class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-1">Kendala</span>
                    <p class="text-slate-800 leading-relaxed whitespace-pre-line">{{ $selected->kendala ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-semibold block text-[11px] uppercase tracking-wider mb-1">Rencana
                        Berikutnya</span>
                    <p class="text-slate-800 leading-relaxed whitespace-pre-line">
                        {{ $selected->rencana_berikutnya ?? '-' }}</p>
                </div>
            </div>

            <!-- Status Luaran Tercapai -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Status Luaran yang Dicentang Ketua Peneliti</h3>
                <div class="space-y-2.5">
                    @forelse($selected->pengajuan->luaran ?? [] as $pl)
                        @php
                            $tercapai = in_array($pl->id, $selected->luaran_tercapai ?? []);
                        @endphp
                        <div
                            class="flex items-center justify-between gap-3 p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <i
                                    class="fa-solid {{ $tercapai ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-slate-300' }}"></i>
                                <span class="font-semibold text-slate-800">{{ $pl->luaranMaster->nama ?? '-' }}</span>
                            </div>
                            <span
                                class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $pl->is_wajib ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-600' }} uppercase">
                                {{ $pl->is_wajib ? 'Wajib' : 'Tambahan' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-slate-400">Tidak ada data luaran.</p>
                    @endforelse
                </div>
            </div>


            <!-- Dokumen Kemajuan (PDF) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3
                    class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i class="fa-solid fa-file-pdf text-rose-500"></i> Dokumen Kemajuan
                </h3>
                @if ($selected->file_path)
                    <div
                        class="flex items-center justify-between p-3.5 bg-slate-50/50 rounded-2xl border border-slate-200/80 gap-3">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div
                                class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-base shrink-0 shadow-2xs">
                                <i class="fa-solid fa-file-pdf"></i>
                            </div>
                            <div class="min-w-0">
                                <span class="font-bold text-slate-900 block text-xs truncate"
                                    title="{{ $selected->file_nama_asli ?? basename($selected->file_path) }}">
                                    {{ $selected->file_nama_asli ?? basename($selected->file_path) }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Format PDF siap
                                    diverifikasi</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Tombol Pratinjau --}}
                            <a href="{{ asset('storage/' . $selected->file_path) }}" target="_blank"
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 font-bold text-xs transition shadow-2xs">
                                <i class="fa-solid fa-eye text-slate-500 text-[11px]"></i> Pratinjau
                            </a>
                            {{-- Tombol Unduh --}}
                            <a href="{{ asset('storage/' . $selected->file_path) }}" download
                                class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition shadow-2xs">
                                <i class="fa-solid fa-download text-[11px]"></i> Unduh
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400 italic">Belum ada dokumen yang diunggah.</p>
                @endif
            </div>
            <!-- Dokumen Pendukung (PDF atau Gambar) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-3 text-xs">
                <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3">
                    Dokumen Pendukung</h3>
                @if (!empty($selected->dokumentasi))
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach ($selected->dokumentasi as $dok)
                            @php
                                $ext = strtolower(pathinfo($dok['nama'] ?? ($dok['path'] ?? ''), PATHINFO_EXTENSION));
                                $isPdf = ($ext === 'pdf');
                            @endphp
                            @if ($isPdf)
                                <a href="{{ asset('storage/' . ($dok['path'] ?? '')) }}" target="_blank"
                                    class="flex flex-col items-center justify-center p-3 rounded-xl border border-slate-200 aspect-square bg-slate-50 hover:bg-slate-100 transition text-center group">
                                    <i class="fa-solid fa-file-pdf text-red-500 text-2xl mb-1.5 group-hover:scale-110 transition"></i>
                                    <span class="text-[10px] text-slate-700 font-bold truncate max-w-full px-1" title="{{ $dok['nama'] ?? 'Dokumen PDF' }}">
                                        {{ $dok['nama'] ?? 'Dokumen PDF' }}
                                    </span>
                                    <span class="text-[9px] text-emerald-700 font-semibold mt-1">Pratinjau PDF</span>
                                </a>
                            @else
                                <a href="{{ asset('storage/' . ($dok['path'] ?? '')) }}" target="_blank"
                                    class="block rounded-xl overflow-hidden border border-slate-100 aspect-square bg-slate-50 relative group">
                                    <img src="{{ asset('storage/' . ($dok['path'] ?? '')) }}"
                                        alt="{{ $dok['nama'] ?? 'dokumentasi' }}" class="w-full h-full object-cover group-hover:scale-105 transition">
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-400">Belum ada dokumen pendukung yang diunggah.</p>
                @endif
            </div>
        </div>

        <!-- KOLOM KANAN: Form Keputusan Validasi (Sticky & Professional Card Design) -->
        <div class="lg:col-span-5 space-y-5 lg:sticky lg:top-6">
            <form action="{{ route('admin.validasi.laporan-kemajuan.update', $selected->id) }}" method="POST"
                class="space-y-5" id="formValidasiKemajuan">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                    <!-- Header Box -->
                    <div class="flex items-center gap-2 border-b border-slate-100 pb-3">
                        <div
                            class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-clipboard-check"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-900 text-sm uppercase tracking-wide">
                            Keputusan Validasi</h3>
                    </div>

                    <!-- Pilihan Card Interaktif -->
                    <div class="space-y-3">
                        <!-- Opsi Setuju -->
                        <label
                            class="flex items-start gap-3.5 p-3.5 rounded-xl border {{ $selected->status == 'disetujui' ? 'border-emerald-500 bg-emerald-50/20' : 'border-slate-200 hover:border-slate-300 bg-white' }} cursor-pointer transition">
                            <input type="radio" name="keputusan" value="setuju" id="keputusanSetuju"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'disetujui' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs">Setujui Laporan</span>
                                <span class="text-[11px] text-slate-400 leading-relaxed block mt-0.5">Laporan kemajuan
                                    dinyatakan valid dan dapat dilanjutkan ke tahap Laporan Hasil.</span>
                            </div>
                        </label>

                        <!-- Opsi Revisi -->
                        <label
                            class="flex items-start gap-3.5 p-3.5 rounded-xl border {{ $selected->status == 'revisi' ? 'border-amber-500 bg-amber-50/20' : 'border-slate-200 hover:border-slate-300 bg-white' }} cursor-pointer transition">
                            <input type="radio" name="keputusan" value="revisi" id="keputusanRevisi"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                {{ $selected->status == 'revisi' ? 'checked' : '' }} required>
                            <div>
                                <span class="font-bold text-slate-800 block text-xs">Perlu Revisi</span>
                                <span class="text-[11px] text-slate-400 leading-relaxed block mt-0.5">Laporan dikembalikan
                                    kepada ketua peneliti untuk diperbaiki.</span>
                            </div>
                        </label>
                    </div>

                    <!-- Catatan / Catatan Revisi -->
                    <div class="space-y-1.5 pt-1">
                        <label class="block font-semibold text-slate-700 text-xs">
                            Catatan / Catatan Revisi <span class="text-rose-500 font-normal">(Jika revisi wajib
                                diisi)</span>
                        </label>
                        <textarea name="catatan" id="catatanValidasi" rows="3"
                            placeholder="Tuliskan catatan atau instruksi perbaikan..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $selected->catatan_validator ?? '' }}</textarea>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-[#047857] text-white font-bold py-2.5 px-4 rounded-xl hover:bg-emerald-800 transition shadow-sm text-xs flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Keputusan
                        </button>
                        <a href="{{ route('admin.validasi.laporan-kemajuan') }}"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

                            {{-- ===================== RIWAYAT VALIDASI (TRACKING, TIDAK PERNAH DITIMPA) ===================== --}}
                @include('partials.timeline-validasi', [
                    'sumber' => [['objek' => $selected, 'tahap' => 'laporan_kemajuan']],
                    'judul'  => 'Riwayat Validasi Laporan Kemajuan',
                ])
        </div>
    </div>

    <script>
        (function() {
            const radioSetuju = document.getElementById('keputusanSetuju');
            const radioRevisi = document.getElementById('keputusanRevisi');
            const catatan = document.getElementById('catatanValidasi');
            const form = document.getElementById('formValidasiKemajuan');

            function syncCatatanRequired() {
                if (radioRevisi.checked) {
                    catatan.setAttribute('required', 'required');
                    catatan.setCustomValidity('');
                } else {
                    catatan.removeAttribute('required');
                    catatan.setCustomValidity('');
                }
            }

            radioSetuju.addEventListener('change', syncCatatanRequired);
            radioRevisi.addEventListener('change', syncCatatanRequired);

            form.addEventListener('submit', function(e) {
                if (radioRevisi.checked && catatan.value.trim() === '') {
                    e.preventDefault();
                    catatan.setCustomValidity(
                        'Catatan revisi wajib diisi sebelum mengirim keputusan "Perlu Revisi".');
                    catatan.reportValidity();
                    catatan.focus();
                }
            });

            syncCatatanRequired();
        })();
    </script>
@endsection

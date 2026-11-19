@extends('layouts.admin')

@section('title', 'Detail Validasi Proposal - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Validasi Proposal')
@section('header_breadcrumb', 'Menu Admin / Validasi / Proposal / Detail')

@section('content')
    <!-- Tombol Kembali -->
    <div class="mb-6">
        <a href="{{ route('admin.validasi.proposal') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
            <i class="fa-solid fa-arrow-left text-emerald-800"></i> Kembali
        </a>
    </div>

    <!-- Grid Utama: 7 Kolom Kiri, 5 Kolom Kanan -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KOLOM KIRI (Informasi Utama, Tim Peneliti, Dokumen Proposal, & Luaran) - 7 Kolom -->
        <div class="lg:col-span-7 space-y-6">

            <!-- 1. CARD INFORMASI PENGAJUAN -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <div
                            class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xs font-bold">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                            Informasi Pengajuan Proposal
                        </h3>
                    </div>
                    <span
                        class="inline-flex px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                        {{ $pengajuan->status ?? 'Proses' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-xs">
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Jenis
                            Kegiatan</span>
                        <span class="font-bold text-slate-800 capitalize mt-0.5 block">{{ $pengajuan->jenis ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Jalur
                            Pembiayaan</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->jalur ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Kode
                            Pengajuan</span>
                        <span class="font-mono font-bold text-emerald-800 mt-0.5 block">{{ $pengajuan->kode ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Tanggal
                            Diajukan</span>
                        <span
                            class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->created_at ? $pengajuan->created_at->format('d F Y, H:i') : '-' }}</span>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Judul
                            Proposal</span>
                        <span
                            class="font-extrabold text-slate-900 text-sm leading-snug mt-0.5 block">{{ $pengajuan->judul ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Skema</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->skema->nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Rumpun Ilmu</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->rumpunIlmu->nama ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Tahun
                            Anggaran</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->tahun_anggaran ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Tahun
                            Pengajuan</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->tahun_pengajuan ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Tahun
                            Capaian</span>
                        <span class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->tahun_capaian ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-bold block uppercase text-[10px] tracking-wide">Tahun
                            Pelaksanaan</span>
                        <span
                            class="font-bold text-slate-800 mt-0.5 block">{{ $pengajuan->tahun_pelaksanaan ?? '-' }}</span>
                    </div>
                    <div class="sm:col-span-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wide">Total Biaya Usulan</span>
                        <span class="font-extrabold text-emerald-800 text-sm">Rp
                            {{ number_format($pengajuan->total_biaya ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- 2. CARD TIM PENELITI -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
                    <div
                        class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                        Tim Peneliti & Pelaksana
                    </h3>
                </div>

                <div class="space-y-3">
                    @if ($pengajuan->pegawai)
                        <div
                            class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold shrink-0">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $pengajuan->pegawai->nama }}</h4>
                                    <p class="text-[11px] text-slate-500 font-medium">Jurusan: <span
                                            class="text-slate-700 font-semibold">{{ $pengajuan->pegawai->jurusan ?? 'Tidak Tercatat' }}</span>
                                    </p>
                                </div>
                            </div>
                            <span
                                class="px-2.5 py-1 rounded-md text-[9px] font-extrabold bg-emerald-800 text-white uppercase tracking-wider shadow-2xs">Ketua</span>
                        </div>
                    @endif

                    @if (isset($pengajuan->anggotas) && count($pengajuan->anggotas) > 0)
                        @foreach ($pengajuan->anggotas as $anggota)
                            <div
                                class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/60">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-slate-900">
                                            {{ $anggota->nama ?? ($anggota->pegawai->nama ?? '-') }}</h4>
                                        <p class="text-[11px] text-slate-500 font-medium">Jurusan: <span
                                                class="text-slate-700 font-semibold">{{ $anggota->jurusan ?? ($anggota->pegawai->jurusan ?? '-') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <span
                                    class="px-2.5 py-1 rounded-md text-[9px] font-extrabold bg-slate-200 text-slate-700 uppercase tracking-wider">Anggota</span>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- 3. DOKUMEN PROPOSAL UTAMA -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-file-pdf text-emerald-600"></i> Dokumen Proposal Utama
                </h3>

                <div
                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-slate-200/60 bg-slate-50/50">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="p-3 bg-red-50 text-red-600 rounded-xl shrink-0">
                            <i class="fa-solid fa-file-pdf text-xl"></i>
                        </div>
                        <div class="truncate">
                            <p class="font-bold text-slate-700 text-xs truncate">
                                Proposal-{{ $pengajuan->proposal_nama_asli ?: basename($pengajuan->proposal_path ?? 'Dokumen_Proposal.pdf') }}
                            </p>
                            <p class="text-[11px] text-slate-400">Format PDF siap diverifikasi</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
                        <a href="{{ asset('storage/' . $pengajuan->proposal_path) }}" target="_blank"
                            class="px-3.5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs">
                            <i class="fa-solid fa-eye text-slate-500"></i> Pratinjau
                        </a>
                        <a href="{{ asset('storage/' . $pengajuan->proposal_path) }}" download="Proposal-{{ $pengajuan->proposal_nama_asli ?: basename($pengajuan->proposal_path ?? 'Dokumen_Proposal.pdf') }}"
                            class="px-3.5 py-2 text-xs font-bold text-white bg-emerald-800 rounded-xl hover:bg-emerald-900 transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-download"></i> Unduh
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3B. DOKUMEN PENDUKUNG (Kontrak, RAB, Kwitansi, Bukti Pajak, Berita Acara/Hibah) -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-xs uppercase tracking-wider">
                    <i class="fa-solid fa-folder-open text-emerald-600"></i> Dokumen Pendukung
                </h3>

                <div class="space-y-3">
                    @php
                        $dokumenPendukung = [
                            [
                                'label' => 'Kontrak',
                                'path' => $pengajuan->kontrak_path,
                                'nama' => $pengajuan->kontrak_nama_asli,
                                'wajib' => false,
                            ],
                            [
                                'label' => 'RAB (Rencana Anggaran Biaya)',
                                'path' => $pengajuan->rab_path,
                                'nama' => $pengajuan->rab_nama_asli,
                                'wajib' => false,
                            ],
                        ];
                    @endphp

                    @foreach ($dokumenPendukung as $dok)
                        <div
                            class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-xl border border-slate-200/60 bg-slate-50/50">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div
                                    class="p-3 rounded-xl shrink-0 {{ $dok['path'] ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-400' }}">
                                    <i class="fa-solid fa-file-pdf text-xl"></i>
                                </div>
                                <div class="truncate">
                                    <p class="font-bold text-slate-700 text-xs truncate flex items-center gap-2">
                                        {{ $dok['label'] }}
                                        <span
                                            class="text-[9px] font-extrabold px-2 py-0.5 rounded-full uppercase tracking-wider {{ $dok['wajib'] ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-600' }}">
                                            {{ $dok['wajib'] ? 'Wajib' : 'Opsional' }}
                                        </span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 truncate">
                                        {{ $dok['path'] ? $dok['nama'] : 'Belum diunggah' }}
                                    </p>
                                </div>
                            </div>

                            @if ($dok['path'])
                                <div class="flex items-center gap-2 w-full sm:w-auto justify-end shrink-0">
                                    <a href="{{ asset('storage/' . $dok['path']) }}" target="_blank"
                                        class="px-3.5 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition flex items-center gap-1.5 shadow-2xs">
                                        <i class="fa-solid fa-eye text-slate-500"></i> Pratinjau
                                    </a>
                                    <a href="{{ asset('storage/' . $dok['path']) }}" download="{{ $dok['nama'] ?: basename($dok['path']) }}"
                                        class="px-3.5 py-2 text-xs font-bold text-white bg-emerald-800 rounded-xl hover:bg-emerald-900 transition flex items-center gap-1.5 shadow-sm">
                                        <i class="fa-solid fa-download"></i> Unduh
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 4. LUARAN YANG DIRENCANAKAN -->
            <div class="bg-white rounded-2xl shadow-xs border border-slate-200 p-6">
                <div class="flex items-center gap-2.5 mb-5 pb-3 border-b border-slate-100">
                    <div
                        class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-800 flex items-center justify-center text-xs font-bold">
                        <i class="fa-solid fa-bullseye"></i>
                    </div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-800">
                        Luaran yang Direncanakan
                    </h3>
                </div>

                <div class="space-y-3">
                    @php
                        $luaranList = $pengajuan->luaran ?? collect();
                    @endphp

                    @foreach ($luaranList as $luaran)
                        @if ($luaran->is_wajib)
                            <div
                                class="p-4 rounded-xl border border-emerald-200/80 bg-emerald-50/20 flex items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-900">
                                        {{ $luaran->luaranMaster->nama ?? 'Luaran Wajib' }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Opsi/Indikator: <span
                                            class="font-medium text-slate-700">{{ $luaran->opsi_dipilih ?? '-' }}</span>
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-extrabold bg-emerald-800 text-white uppercase tracking-wider shrink-0 shadow-2xs">Wajib</span>
                            </div>
                        @endif
                    @endforeach

                    @foreach ($luaranList as $luaran)
                        @if (!$luaran->is_wajib)
                            <div
                                class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/50 flex items-center justify-between gap-4">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-900">
                                        {{ $luaran->luaranMaster->nama ?? 'Luaran Tambahan' }}</h4>
                                    <p class="text-[11px] text-slate-500 mt-0.5">Opsi/Indikator: <span
                                            class="font-medium text-slate-700">{{ $luaran->opsi_dipilih ?? '-' }}</span>
                                    </p>
                                </div>
                                <span
                                    class="px-3 py-1 rounded-lg text-[10px] font-extrabold bg-slate-200 text-slate-700 uppercase tracking-wider shrink-0">Tambahan</span>
                            </div>
                        @endif
                    @endforeach

                    @if ($luaranList->isEmpty())
                        <p class="text-xs text-slate-400 italic py-2 text-center">Tidak ada data luaran tercatat.</p>
                    @endif
                </div>
            </div>

        </div><!-- End of Kolom Kiri -->

        <!-- KOLOM KANAN: Form Keputusan Validasi Proposal (Sticky) - 5 Kolom -->
        <div class="lg:col-span-5 space-y-5 sticky top-5">
            <form action="{{ route('admin.validasi.proposal.update', $pengajuan->id) }}" method="POST"
                class="space-y-5" id="formValidasiProposal">
                @csrf

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 space-y-4 text-xs">
                    <h3
                        class="font-extrabold text-slate-900 text-sm uppercase tracking-wide border-b border-slate-100 pb-3 flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i> Keputusan Validasi
                    </h3>

                    <div class="space-y-3">
                        <!-- Opsi Setuju -->
                        <label
                            class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-emerald-500 cursor-pointer transition">
                            <input type="radio" name="keputusan" value="setuju" id="keputusanSetuju"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                required>
                            <div>
                                <span class="font-bold text-slate-900 block text-xs">Setuju Proposal</span>
                                <span class="text-[11px] text-slate-500 leading-snug block mt-0.5">Proposal dinyatakan
                                    valid dan dapat dilanjutkan ke tahap berikutnya.</span>
                            </div>
                        </label>

                        <!-- Opsi Revisi -->
                        <label
                            class="flex items-start gap-3 p-3.5 rounded-xl border border-slate-200 bg-white hover:border-slate-500 cursor-pointer transition">
                            <input type="radio" name="keputusan" value="revisi" id="keputusanRevisi"
                                class="mt-0.5 w-4 h-4 text-emerald-700 border-slate-300 focus:ring-emerald-600 accent-emerald-700"
                                required>
                            <div>
                                <span class="font-bold text-slate-900 block text-xs">Perlu Revisi</span>
                                <span class="text-[11px] text-slate-500 leading-snug block mt-0.5">Proposal dikembalikan
                                    kepada dosen pengusul untuk diperbaiki.</span>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-1.5 pt-2">
                        <label class="block font-bold text-slate-700 text-xs">Catatan / Catatan Revisi <span
                                class="text-rose-500 font-normal">(Jika revisi wajib diisi)</span></label>
                        <textarea name="catatan" id="catatanValidasi" rows="3"
                            placeholder="Tuliskan catatan atau instruksi perbaikan..."
                            class="w-full border border-slate-200 rounded-xl p-3 text-xs focus:outline-none focus:border-emerald-700 bg-slate-50/50 resize-none transition">{{ $pengajuan->catatan_validator ?? '' }}</textarea>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                            class="flex-1 bg-emerald-700 text-white font-bold py-3 px-4 rounded-xl hover:bg-emerald-800 transition shadow-sm text-xs flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Keputusan
                        </button>
                        <a href="{{ route('admin.validasi.proposal') }}"
                            class="px-4 py-3 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition text-center">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

                           {{-- ===================== RIWAYAT VALIDASI (TRACKING, TIDAK PERNAH DITIMPA) ===================== --}}
                @include('partials.timeline-validasi', [
                    'sumber' => [['objek' => $pengajuan, 'tahap' => 'proposal']],
                    'judul'  => 'Riwayat Validasi Proposal',
                ])
        </div><!-- End of Kolom Kanan -->

    </div>

    <script>
        (function() {
            const radioSetuju = document.getElementById('keputusanSetuju');
            const radioRevisi = document.getElementById('keputusanRevisi');
            const catatan = document.getElementById('catatanValidasi');
            const form = document.getElementById('formValidasiProposal');

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

            // Inisialisasi saat halaman dimuat (misal ada nilai lama dari validasi gagal sebelumnya)
            syncCatatanRequired();
        })();
    </script>
@endsection
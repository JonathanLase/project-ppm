{{--
    PARTIAL: Sidebar Petugas PPM untuk layouts/admin.blade.php
    Pakai Tailwind classes yang sama dengan sidebar admin
    sehingga tampilannya konsisten dengan template admin yang ada.
--}}
<aside class="w-72 bg-[#022c22] text-white flex flex-col h-full shrink-0 select-none shadow-2xl">

    {{-- Brand --}}
    <div class="brand">
        <div class="brand-mark">
            <img src="{{ asset('img/logo-icon.png') }}" alt="Logo Poltekkes Kemenkes Medan"
                style="width:100%; height:100%; object-fit:contain; padding:4px;">
        </div>
        <div class="brand-text">
            <b>Poltekkes Kemenkes</b>
            <span>Medan · SIPPM</span>
            <span style="display:inline-block;margin-top:4px;font-size:9px;font-weight:700;
                letter-spacing:.08em;text-transform:uppercase;
                background:rgba(251,191,36,.18);color:#fbbf24;
                border:1px solid rgba(251,191,36,.3);border-radius:999px;padding:2px 8px;">
                Petugas PPM
            </span>
        </div>
    </div>

    <nav class="flex-grow px-4 py-4 space-y-1 text-xs overflow-y-auto sidebar-scroll font-medium">

        {{-- Dashboard --}}
        <a href="{{ route('petugas.dashboard') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-house w-4 text-center"></i> Dashboard
        </a>

        {{-- ── Divider ── --}}
        <div class="border-t border-emerald-800/50 my-1 mx-1"></div>

        {{-- Pengajuan (sisi pengaju) --}}
        <a href="{{ route('petugas.pengajuan.daftar') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.pengajuan.*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-file-pen w-4 text-center"></i> Pengajuan Proposal
        </a>
        <a href="{{ route('petugas.riwayat') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.riwayat') || request()->routeIs('petugas.pengajuan.detail') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Riwayat Pengajuan
        </a>
        <a href="{{ route('petugas.laporan.kemajuan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.laporan.kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-spinner w-4 text-center"></i> Laporan Kemajuan
        </a>
        <a href="{{ route('petugas.laporan.index', 'hasil') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.laporan.*') && !request()->routeIs('petugas.laporan.kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-file-circle-check w-4 text-center"></i> Laporan Hasil
        </a>

        {{-- ── Divider ── --}}
        <div class="border-t border-emerald-800/50 my-1 mx-1"></div>

        {{-- Validasi & Admin --}}
        <a href="{{ route('admin.semua-pengajuan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.semua-pengajuan') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-list-check w-4 text-center"></i> Semua Pengajuan
        </a>
        <a href="{{ route('admin.validasi.proposal') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.proposal*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-file-circle-check w-4 text-center"></i> Validasi Proposal
        </a>
        <a href="{{ route('admin.validasi.laporan-kemajuan') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.laporan-kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-spinner w-4 text-center"></i> Validasi Lap. Kemajuan
        </a>
        <a href="{{ route('admin.validasi.laporan_hasil') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.laporan_hasil*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-square-poll-vertical w-4 text-center"></i> Validasi Lap. Hasil
        </a>

        {{-- ── Divider ── --}}
        <div class="border-t border-emerald-800/50 my-1 mx-1 pt-2"></div>

        {{-- Profil & Logout --}}
        <a href="{{ route('petugas.profil') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.profil*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100/90 hover:bg-emerald-900/60 hover:text-white' }}">
            <i class="fa-solid fa-circle-user w-4 text-center"></i> Profil
        </a>
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-rose-950/60 text-rose-300 hover:text-rose-100 transition text-left font-semibold cursor-pointer">
                <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
            </button>
        </form>

    </nav>

    <div class="p-4 text-[10px] text-emerald-400/80 border-t border-emerald-950/80 leading-tight shrink-0 bg-[#011a14]">
        Sistem Informasi Pengelolaan Penelitian &amp; Pengabdian Masyarakat<br>&copy; {{ date('Y') }}
    </div>

</aside>

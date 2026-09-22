{{--
    PARTIAL: Sidebar Petugas PPM untuk layouts/app.blade.php
    Pakai CSS classes yang sama dengan sidebar dosen (.sidebar, .nav, .brand, dll)
    sehingga tampilannya konsisten dengan template app.css yang ada.
--}}
<aside class="sidebar">
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

    <div class="nav">

        {{-- Dashboard --}}
        <a class="{{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}"
            href="{{ route('petugas.dashboard') }}">
            <span class="ic">▦</span>Dashboard
        </a>

        {{-- ── Divider ── --}}
        <div style="border-top:1px solid rgba(255,255,255,0.1);margin:6px 12px;"></div>

        {{-- Pengajuan (sisi pengaju) --}}
        <a class="{{ request()->routeIs('petugas.pengajuan.*') ? 'active' : '' }}"
            href="{{ route('petugas.pengajuan.daftar') }}">
            <span class="ic">✎</span>Pengajuan Proposal
        </a>
        <a class="{{ request()->routeIs('petugas.riwayat') || request()->routeIs('petugas.pengajuan.detail') ? 'active' : '' }}"
            href="{{ route('petugas.riwayat') }}">
            <span class="ic">◷</span>Riwayat Pengajuan
        </a>
        <a class="{{ request()->routeIs('petugas.laporan.kemajuan*') ? 'active' : '' }}"
            href="{{ route('petugas.laporan.kemajuan') }}">
            <span class="ic">▤</span>Laporan Kemajuan
        </a>
        <a class="{{ request()->routeIs('petugas.laporan.*') && !request()->routeIs('petugas.laporan.kemajuan*') ? 'active' : '' }}"
            href="{{ route('petugas.laporan.index', 'hasil') }}">
            <span class="ic">▤</span>Laporan Hasil
        </a>

        {{-- ── Divider ── --}}
        <div style="border-top:1px solid rgba(255,255,255,0.1);margin:6px 12px;"></div>

        {{-- Validasi & Admin --}}
        <a class="{{ request()->routeIs('admin.semua-pengajuan') ? 'active' : '' }}"
            href="{{ route('admin.semua-pengajuan') }}">
            <span class="ic">☰</span>Semua Pengajuan
        </a>
        <a class="{{ request()->routeIs('admin.validasi.proposal*') ? 'active' : '' }}"
            href="{{ route('admin.validasi.proposal') }}">
            <span class="ic">✔</span>Validasi Proposal
        </a>
        <a class="{{ request()->routeIs('admin.validasi.laporan-kemajuan*') ? 'active' : '' }}"
            href="{{ route('admin.validasi.laporan-kemajuan') }}">
            <span class="ic">⏳</span>Validasi Lap. Kemajuan
        </a>
        <a class="{{ request()->routeIs('admin.validasi.laporan_hasil*') ? 'active' : '' }}"
            href="{{ route('admin.validasi.laporan_hasil') }}">
            <span class="ic">📊</span>Validasi Lap. Hasil
        </a>

        {{-- ── Divider ── --}}
        <div style="border-top:1px solid rgba(255,255,255,0.1);margin:6px 12px;"></div>

        {{-- Profil & Logout --}}
        <a class="{{ request()->routeIs('petugas.profil*') ? 'active' : '' }}"
            href="{{ route('petugas.profil') }}">
            <span class="ic">◈</span>Profil
        </a>
        <a href="#" class="logout-link"
            onclick="event.preventDefault(); document.getElementById('petugas-logout-form').submit();">
            <span class="ic">⏻</span>Logout
        </a>
        <form id="petugas-logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
            @csrf
        </form>

    </div>

    <div class="sidebar-foot">
        Sistem Informasi Pengelolaan<br>Penelitian &amp; Pengabdian Masyarakat<br>&copy; {{ date('Y') }}
    </div>
</aside>

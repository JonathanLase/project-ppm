@if(auth()->check() && auth()->user()->role === 'petugas_ppm')
    @include('layouts.petugas')
@else
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIPPM Poltekkes Kemenkes Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ====== OVERRIDE STYLE: sidebar hijau, logout merah ======
         Idealnya potongan CSS ini dipindahkan ke dalam app.css, tapi ditaruh di sini
         dulu supaya bisa langsung jalan tanpa perlu edit file lain. ====== --}}
    <style>
        :root {
            --sidebar-bg: #0b3d2e;
            /* hijau tua, samakan dengan sidebar admin */
            --sidebar-bg-active: #00875A;
            --sidebar-text: #d8ece3;
            --sidebar-text-muted: #9fc6b6;
            --logout-red: #e5484d;
            --logout-red-bg: rgba(229, 72, 77, 0.12);
        }

        .sidebar {
            background: var(--sidebar-bg) !important;
        }

        .sidebar .brand-text b,
        .sidebar .brand-text span,
        .sidebar .sidebar-foot {
            color: var(--sidebar-text) !important;
        }

        .sidebar .nav a {
            color: var(--sidebar-text) !important;
        }

        .sidebar .nav a:hover {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        .sidebar .nav a.active {
            background: var(--sidebar-bg-active) !important;
            color: #fff !important;
        }

        /* Tombol Logout dibuat merah, dipisah dari menu lain */
        .sidebar .nav a.logout-link {
            color: var(--logout-red) !important;
            margin-top: 8px;
        }

        .sidebar .nav a.logout-link:hover {
            background: var(--logout-red-bg) !important;
        }

        /* Avatar topbar: pastikan foto profil mengisi penuh & tetap bulat/rounded
           mengikuti gaya .avatar bawaan app.css, apapun ukurannya. */
        .topbar .avatar {
            overflow: hidden;
        }

        .topbar .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
    </style>
</head>

<body>
    <div class="app">
        @if(auth()->user()->role === 'petugas_ppm')
            @include('partials.sidebar-petugas-app')
        @else
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark"><img src="{{ asset('img/logo-icon.png') }}" alt="Logo Poltekkes Kemenkes Medan"
                        style="width:100%; height:100%; object-fit:contain; padding:4px;"></div>
                <div class="brand-text"><b>Poltekkes Kemenkes</b><span>Medan · SIPPM</span></div>
            </div>
            <div class="nav">
                <a class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="ic">▦</span>Dashboard</a>
                <a class="{{ request()->routeIs('pengajuan.*') ? 'active' : '' }}" href="{{ route('pengajuan.daftar') }}"><span class="ic">✎</span>Pengajuan Proposal</a>
                <a class="{{ request()->routeIs('riwayat') || request()->routeIs('pengajuan.detail') ? 'active' : '' }}" href="{{ route('riwayat') }}"><span class="ic">◷</span>Riwayat Pengajuan</a>
                <a class="{{ request()->routeIs('laporan.kemajuan*') ? 'active' : '' }}" href="{{ route('laporan.kemajuan') }}"><span class="ic">▤</span>Laporan Kemajuan</a>
                <a class="{{ request()->routeIs('laporan.*') && !request()->routeIs('laporan.kemajuan*') ? 'active' : '' }}" href="{{ route('laporan.index', 'hasil') }}"><span class="ic">▤</span>Laporan Hasil</a>
                <a class="{{ request()->routeIs('notifikasi*') ? 'active' : '' }}" href="{{ route('notifikasi') }}"><span class="ic">🔔</span>Notifikasi</a>
                <a class="{{ request()->routeIs('profil*') ? 'active' : '' }}" href="{{ route('profil') }}"><span class="ic">◈</span>Profil</a>
                <a href="{{ route('ubah-password') }}" class="{{ request()->routeIs('ubah-password') ? 'active' : '' }}"><span class="ic">⚿</span>Ubah Password</a>
                <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><span class="ic">⏻</span>Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
            </div>
            <div class="sidebar-foot">Sistem Informasi Pengelolaan<br>Penelitian &amp; Pengabdian Masyarakat<br>&copy; {{ date('Y') }}</div>
        </aside>
        @endif

        <div class="main">
            <div class="topbar">
                <div>
                    <h1>@yield('title', 'Dashboard')</h1>
                    <div class="crumbs">@yield('crumbs', 'Dashboard')</div>
                </div>
                <div class="who">
                    <div class="avatar">
                        @if (auth()->user()->foto)
                            <img src="{{ Storage::url(auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}">
                        @else
                            {{ auth()->user()->initials() }}
                        @endif
                    </div>
                    <div class="meta">
                        <b>{{ auth()->user()->nama }}</b>
                        <span>Dosen</span>
                    </div>
                </div>
            </div>

            <div class="content">
                @if (session('success'))
                    <div class="alert-box alert-info" style="margin-bottom:16px;">&#9989; {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>

</html>

@endif

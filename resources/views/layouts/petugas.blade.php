<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — SIPPM Poltekkes Kemenkes Medan</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Icons & Styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        .sidebar-scroll::-webkit-scrollbar { width: 6px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background-color: rgba(16, 185, 129, 0.2); border-radius: 20px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background-color: rgba(16, 185, 129, 0.4); }
        .sidebar-scroll { scrollbar-width: thin; scrollbar-color: rgba(16, 185, 129, 0.2) transparent; }
        .brand { padding: 1.5rem 1rem; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid rgba(2, 44, 34, 0.8); background: #011a14; }
        .brand-mark { width: 42px; height: 42px; background: white; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .brand-text { display: flex; flex-direction: column; }
        .brand-text b { font-size: 0.95rem; font-weight: 800; color: #fff; line-height: 1.2; letter-spacing: -0.01em; }
        .brand-text span { font-size: 0.75rem; color: #6ee7b7; font-weight: 500; letter-spacing: 0.05em; }
    </style>
</head>

<body class="bg-[#f1f5f9] text-slate-900 flex h-screen overflow-hidden">

    <!-- ============================= SIDEBAR ============================= -->
    <aside class="w-72 bg-[#022c22] text-white flex flex-col h-full shrink-0 select-none shadow-2xl">
        <div class="brand">
            <div class="brand-mark">
                <img src="{{ asset('img/logo-icon.png') }}" alt="Logo Poltekkes Kemenkes Medan"
                    style="width:100%; height:100%; object-fit:contain; padding:4px;">
            </div>
            <div class="brand-text">
                <b>Poltekkes Kemenkes</b>
                <span>Medan · SIPPM</span>
                <span style="font-size:9px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;background:rgba(251,191,36,.18);color:#fbbf24;border:1px solid rgba(251,191,36,.3);border-radius:999px;padding:2px 8px;display:inline-block;margin-top:4px;">Petugas PPM</span>
            </div>
        </div>

        <nav class="flex-grow px-4 py-5 space-y-6 text-xs overflow-y-auto sidebar-scroll font-medium">
            <a href="{{ route('petugas.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('petugas.dashboard') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100 hover:bg-emerald-900/60 hover:text-white' }}">
                <i class="fa-solid fa-house w-4 text-center"></i>
                Dashboard
            </a>

            <div>
                <div class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase px-4 mb-2">Pengajuan</div>
                <div class="space-y-1 text-emerald-100/90 font-medium">
                    <a href="{{ route('admin.semua-pengajuan') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.semua-pengajuan') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-list-check w-4 text-center"></i> Semua Pengajuan
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase px-4 mb-2">Validasi</div>
                <div class="space-y-1 text-emerald-100/90 font-medium">
                    <a href="{{ route('admin.validasi.proposal') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.proposal*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-file-circle-check w-4 text-center"></i> Proposal
                    </a>
                    <a href="{{ route('admin.validasi.laporan-kemajuan') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.laporan-kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-spinner w-4 text-center"></i> Laporan Kemajuan
                    </a>
                    <a href="{{ route('admin.validasi.laporan_hasil') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.validasi.laporan_hasil*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-square-poll-vertical w-4 text-center"></i> Laporan Hasil
                    </a>
                </div>
            </div>

            <div>
                <div class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase px-4 mb-2">Master Data</div>
                <div class="space-y-1 text-emerald-100/90 font-medium">
                    <a href="{{ route('admin.master.skema') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.master.skema*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center"></i> Skema
                    </a>
                    <a href="{{ route('admin.master.pegawai') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.master.pegawai*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-id-badge w-4 text-center"></i> Manajemen User
                    </a>
                    <a href="{{ route('admin.master.rumpun') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.master.rumpun*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-book-bookmark w-4 text-center"></i> Rumpun Ilmu
                    </a>
                    <a href="{{ route('admin.master.luaran') }}"
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.master.luaran*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                        <i class="fa-solid fa-arrow-up-right-from-square w-4 text-center"></i> Luaran
                    </a>
                </div>
            </div>

            <div class="border-t border-emerald-800/50 my-2 mx-4"></div>

            <div class="space-y-1 text-emerald-100/90 font-medium">
                <a href="{{ route('pengajuan.daftar') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('pengajuan.*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-file-pen w-4 text-center"></i> Pengajuan Proposal
                </a>
                <a href="{{ route('riwayat') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('riwayat') || request()->routeIs('pengajuan.detail') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 text-center"></i> Riwayat Pengajuan
                </a>
                <a href="{{ route('laporan.kemajuan') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('laporan.kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-spinner w-4 text-center"></i> Laporan Kemajuan
                </a>
                <a href="{{ route('laporan.index', 'hasil') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('laporan.*') && !request()->routeIs('laporan.kemajuan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-file-circle-check w-4 text-center"></i> Laporan Hasil
                </a>
            </div>

            <div class="border-t border-emerald-800/50 my-2 mx-4"></div>

            <div class="space-y-1 text-emerald-100/90 font-medium">
                <a href="{{ route('admin.laporan') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.laporan*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center text-emerald-400"></i> Laporan
                </a>
                <a href="{{ route('admin.activity_log') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.activity_log') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 text-center text-emerald-400"></i> Activity Log
                </a>
                <a href="{{ route('admin.notifikasi') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('admin.notifikasi') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-bell w-4 text-center text-amber-400"></i> Notifikasi
                </a>
                <a href="{{ route('petugas.profil') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.profil*') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-circle-user w-4 text-center text-emerald-400"></i> Profil
                </a>
                <a href="{{ route('petugas.ubah-password') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition {{ request()->routeIs('petugas.ubah-password') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'hover:bg-emerald-900/60 hover:text-white' }}">
                    <i class="fa-solid fa-key w-4 text-center text-emerald-400"></i> Ubah Password
                </a>

                <form action="{{ route('admin.logout') }}" method="POST" class="pt-2">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-rose-950/60 text-rose-300 hover:text-rose-100 transition text-left font-semibold cursor-pointer">
                        <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        <div class="p-4 text-[10px] text-emerald-400/80 border-t border-emerald-950/80 leading-tight shrink-0 bg-[#011a14]">
            Sistem Informasi Pengelolaan Penelitian &amp; Pengabdian Masyarakat<br>&copy; {{ date('Y') }}
        </div>
    </aside>

    <!-- ============================= MAIN CONTENT AREA ============================= -->
    <div class="flex-grow flex flex-col h-full overflow-hidden">
        
        <!-- HEADER -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-8 shrink-0 z-10 shadow-sm">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">@yield('title', 'Dashboard')</h1>
                <div class="text-xs font-medium text-slate-500 mt-1 flex items-center gap-2">
                    <i class="fa-solid fa-house-chimney text-[10px]"></i>
                    @yield('crumbs', 'Dashboard')
                </div>
            </div>

            <div class="flex items-center gap-5">
                <a href="{{ route('admin.notifikasi') }}" class="relative text-slate-400 hover:text-emerald-600 transition">
                    <i class="fa-regular fa-bell text-xl"></i>
                    @php
                        $unreadCount = \App\Models\Notification::whereNull('read_at')->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-rose-500 rounded-full border-2 border-white text-[9px] font-bold text-white flex items-center justify-center">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-sm font-bold text-slate-800">{{ auth()->user()->nama }}</div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-emerald-600">Petugas PPM</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-emerald-100 border-2 border-emerald-200 flex items-center justify-center text-emerald-700 font-bold overflow-hidden shadow-inner">
                        @if (auth()->user()->foto)
                            <img src="{{ Storage::url(auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}" class="w-full h-full object-cover">
                        @else
                            {{ auth()->user()->initials() }}
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- CONTENT SCROLL AREA -->
        <main class="flex-grow overflow-y-auto p-8 bg-[#f8fafc] relative content space-y-6">
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i>
                    <div class="text-sm text-emerald-800 font-medium">{{ session('success') }}</div>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-start gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5"></i>
                    <div>
                        <div class="text-sm text-rose-800 font-bold mb-1">Terdapat kesalahan:</div>
                        <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>

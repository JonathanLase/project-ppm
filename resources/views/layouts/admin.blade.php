@if(auth()->check() && auth()->user()->role === 'petugas_ppm')
    @include('layouts.petugas')
@else
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPPM Poltekkes Kemenkes Medan')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Alpine.js untuk Dropdown Interaktif -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icon.png') }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.25);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-mark {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: 13px;
            line-height: 1.2;
            color: #ffffff;
        }

        .brand-text b {
            display: block;
            font-weight: 700;
            font-size: 14px;
        }

        .brand-text span {
            display: block;
            font-size: 11px;
            color: #34d399;
            font-weight: 500;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] text-slate-900 flex h-screen overflow-hidden">

    <!-- ============================= SIDEBAR ============================= -->
    @if(auth()->user()->role === 'petugas_ppm')
        @include('partials.sidebar-petugas-admin')
    @else
    <aside class="w-72 bg-[#022c22] text-white flex flex-col h-full shrink-0 select-none shadow-2xl">
        <div class="brand">
            <div class="brand-mark">
                <img src="{{ asset('img/logo-icon.png') }}" alt="Logo Poltekkes Kemenkes Medan"
                    style="width:100%; height:100%; object-fit:contain; padding:4px;">
            </div>
            <div class="brand-text">
                <b>Poltekkes Kemenkes</b>
                <span>Medan · SIPPM</span>
            </div>
        </div>

        <nav class="flex-grow px-4 py-5 space-y-6 text-xs overflow-y-auto sidebar-scroll font-medium">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700 text-white font-bold shadow-lg shadow-emerald-950/40' : 'text-emerald-100 hover:bg-emerald-900/60 hover:text-white' }}">
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

            <div class="pt-3 border-t border-emerald-900/80 space-y-1 text-emerald-100/90 font-medium">
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
    @endif

    <!-- ============================= MAIN CONTENT AREA ============================= -->
    <div class="flex-grow flex flex-col h-full overflow-hidden">
        <header
            class="bg-white px-8 py-4 flex justify-between items-center border-b border-slate-200 shrink-0 shadow-sm">
            <div>
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">@yield('header_title', 'Dashboard')</h1>
                <p class="text-xs text-slate-500 font-semibold">@yield('header_breadcrumb', 'Menu Admin / Dashboard')</p>
            </div>
            <div class="flex items-center gap-4">

                <!-- Ikon Lonceng Notifikasi Dinamis dengan Alpine.js -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="relative w-10 h-10 rounded-full bg-slate-100 border border-slate-200 text-slate-700 flex items-center justify-center hover:bg-slate-200 transition">
                        @if (isset($unreadCount) && $unreadCount > 0)
                            <span
                                class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-600 rounded-full ring-2 ring-white animate-pulse"></span>
                        @endif
                        <i class="fa-solid fa-bell text-sm"></i>
                    </button>

                    <!-- Dropdown Daftar Notifikasi Ringkas -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-80 bg-white border border-slate-200 rounded-2xl shadow-xl py-3 z-50 text-xs"
                        style="display: none;">
                        <div class="px-4 pb-2 border-b border-slate-100 flex justify-between items-center">
                            <span class="font-bold text-slate-900">Notifikasi Terbaru</span>
                            <a href="{{ route('admin.notifikasi') }}"
                                class="text-[10px] text-emerald-700 font-bold hover:underline">Lihat Semua</a>
                        </div>

                        <div class="divide-y divide-slate-100 max-h-64 overflow-y-auto">
                            @forelse($unreadNotifications ?? [] as $notif)
                                <div class="p-3 hover:bg-slate-50 transition">
                                    <p class="font-bold text-slate-900 line-clamp-1">{{ $notif->title }}</p>
                                    <p class="text-slate-500 text-[11px] line-clamp-2 mt-0.5">{{ $notif->message }}</p>
                                    <span
                                        class="text-[9px] text-slate-400 mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="py-6 text-center text-slate-400">
                                    <p>Tidak ada notifikasi baru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-l border-slate-200 pl-4">
                    <div
                        class="w-10 h-10 rounded-full bg-emerald-800 text-white flex items-center justify-center font-extrabold text-sm shadow-md">
                        AS</div>
                    <div class="text-left">
                        <span class="block text-sm font-extrabold text-slate-900 leading-tight">Administrator
                            SIPPM</span>
                        <span class="block text-xs text-slate-500 font-semibold">Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-grow p-8 space-y-6 overflow-y-auto">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>

</html>
@endif

@extends('layouts.admin')

@section('title', 'Arsip SK - SIPPM Poltekkes Kemenkes Medan')
@section('header_title', 'Arsip Surat Keputusan (SK)')
@section('header_breadcrumb', 'Menu Admin / Arsip SK')

@section('content')
    <style>
        .btn-gradient-animate {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            transition: all 0.3s ease;
        }

        .btn-gradient-animate:hover {
            background: linear-gradient(135deg, #047857 0%, #064e3b 100%);
            box-shadow: 0 6px 20px rgba(4, 120, 87, 0.25);
            transform: translateY(-1px);
        }

        .custom-card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        /* Styling Modern untuk Pagination */
        .pagination-modern nav {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        .pagination-modern nav svg {
            width: 1.1rem;
            height: 1.1rem;
        }

        .pagination-modern nav span[aria-current="page"] span,
        .pagination-modern nav a,
        .pagination-modern nav span[aria-disabled="true"] span {
            font-size: 0.8rem !important;
            font-weight: 500;
            padding: 0.45rem 0.85rem !important;
            border-radius: 0.75rem !important;
            border: 1px solid #e5e7eb !important;
            background-color: #ffffff;
            color: #4b5563;
            transition: all 0.25s ease;
        }

        .pagination-modern nav span[aria-current="page"] span {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(4, 120, 87, 0.25);
        }

        .pagination-modern nav a:hover {
            background-color: #ecfdf5 !important;
            color: #047857 !important;
            border-color: #a7f3d0 !important;
            transform: translateY(-1px);
        }
    </style>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid px-6 py-8">
        <!-- Header Page with Accent -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-file-shield"></i> Dokumen &amp; Legalitas
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Arsip SK</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Unggah dan kelola Surat Keputusan (SK) penelitian &amp; pengabdian masyarakat untuk seluruh sivitas akademika.
                </p>
            </div>

            <div class="flex items-center gap-3 self-start md:self-auto">
                <button type="button" onclick="openUploadModal()"
                    class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2 cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload SK Baru
                </button>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden mb-6">
            {{-- Toolbar Filter & Pencarian --}}
            <div class="p-5 border-b border-gray-100 bg-gray-50/40">
                <form method="GET" action="{{ route('admin.arsip-sk.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    {{-- Filter Tahun --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun</label>
                        <select name="tahun" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-white text-gray-700 font-medium focus:outline-none focus:border-emerald-600">
                            <option value="">Semua Tahun</option>
                            @foreach ($years as $y)
                                <option value="{{ $y }}" {{ (string)$filterTahun === (string)$y ? 'selected' : '' }}>
                                    Tahun {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Jenis Kegiatan --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Jenis Kegiatan</label>
                        <select name="jenis" id="filter_jenis_admin" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-white text-gray-700 font-medium focus:outline-none focus:border-emerald-600">
                            <option value="">Semua Jenis</option>
                            <option value="penelitian" {{ $filterJenis === 'penelitian' ? 'selected' : '' }}>Penelitian</option>
                            <option value="pengabdian" {{ $filterJenis === 'pengabdian' ? 'selected' : '' }}>Pengabdian Masyarakat</option>
                        </select>
                    </div>

                    {{-- Filter Jalur Pengajuan --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Jalur Pengajuan</label>
                        <select name="jalur" id="filter_jalur_admin" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-white text-gray-700 font-medium focus:outline-none focus:border-emerald-600">
                            <option value="">Semua Jalur</option>
                            <option value="simlitabkes" {{ $filterJalur === 'simlitabkes' ? 'selected' : '' }}>Simlitabkes</option>
                            <option value="mandiri" {{ $filterJalur === 'mandiri' ? 'selected' : '' }}>Mandiri</option>
                        </select>
                    </div>

                    {{-- Filter Skema Dinamis --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Skema</label>
                        <select name="skema_id" id="filter_skema_admin" class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs bg-white text-gray-700 font-medium focus:outline-none focus:border-emerald-600">
                            <option value="">Semua Skema</option>
                        </select>
                    </div>

                    {{-- Cari No SK --}}
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Cari No SK</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 pointer-events-none">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </span>
                            <input type="text" name="q" value="{{ $q }}" placeholder="Nomor SK..."
                                class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl bg-white text-gray-800 placeholder-gray-400 focus:outline-none focus:border-emerald-600">
                        </div>
                    </div>

                    {{-- Tombol Submit & Reset --}}
                    <div class="flex items-end gap-2">
                        <button type="submit"
                            class="flex-1 py-2 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <i class="fa-solid fa-filter text-xs"></i> Filter
                        </button>
                        @if ($filterTahun || $filterJenis || $filterJalur || $filterSkema || $q)
                            <a href="{{ route('admin.arsip-sk.index') }}"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-semibold rounded-xl transition flex items-center justify-center gap-1"
                                title="Reset Filter">
                                <i class="fa-solid fa-rotate-left text-xs"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if ($errors->any())
                <div class="m-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Tabel Data Arsip SK --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 w-14 text-center">No</th>
                            <th class="py-4 px-6">Nomor SK</th>
                            <th class="py-4 px-6 w-24">Tahun</th>
                            <th class="py-4 px-6">Jenis Kegiatan</th>
                            <th class="py-4 px-6">Jalur</th>
                            <th class="py-4 px-6">Skema</th>
                            <th class="py-4 px-6">File Dokumen</th>
                            <th class="py-4 px-6 w-36 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                        @forelse ($arsipSks as $index => $item)
                            <tr class="hover:bg-emerald-50/30 transition duration-150">
                                <td class="py-4 px-6 font-medium text-gray-500 text-center">
                                    {{ $arsipSks->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-900">
                                    <span class="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg text-xs font-mono">
                                        {{ $item->no_sk }}
                                    </span>
                                    @if ($item->keterangan)
                                        <span class="block text-[11px] text-gray-400 font-normal mt-1 max-w-xs truncate" title="{{ $item->keterangan }}">
                                            <i class="fa-regular fa-note-sticky mr-1"></i> {{ $item->keterangan }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-extrabold text-gray-800">
                                    {{ $item->tahun }}
                                </td>
                                <td class="py-4 px-6">
                                    @if ($item->jenis === 'penelitian')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-200">
                                            Penelitian
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200">
                                            Pengabdian
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if ($item->jalur === 'simlitabkes')
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Simlitabkes
                                        </span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-purple-50 text-purple-700 border border-purple-200">
                                            Mandiri
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-medium text-gray-800">
                                    {{ $item->skema->nama ?? '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-file-pdf text-rose-500 text-lg"></i>
                                        <div class="text-xs">
                                            <span class="font-semibold text-gray-900 block truncate max-w-[150px]" title="{{ $item->file_nama_asli }}">
                                                {{ $item->file_nama_asli ?: 'SK Document' }}
                                            </span>
                                            <span class="text-[10.5px] text-gray-400">
                                                {{ $item->fileSizeFormatted() }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Preview PDF --}}
                                        <a href="{{ route('admin.arsip-sk.preview', $item->id) }}" target="_blank"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition"
                                            title="Preview Dokumen PDF">
                                            <i class="fa-solid fa-eye text-xs"></i>
                                        </a>

                                        {{-- Download PDF --}}
                                        <a href="{{ route('admin.arsip-sk.download', $item->id) }}"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition"
                                            title="Download File PDF">
                                            <i class="fa-solid fa-download text-xs"></i>
                                        </a>

                                        {{-- Edit SK --}}
                                        <button type="button" onclick='openEditModal(@json($item))'
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition cursor-pointer"
                                            title="Edit Data SK">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>

                                        {{-- Delete SK --}}
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('admin.arsip-sk.destroy', $item->id) }}', @js($item->no_sk))"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition cursor-pointer"
                                            title="Hapus Arsip SK">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-gray-400">
                                    <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-folder-open text-lg"></i>
                                    </div>
                                    <p class="font-bold text-gray-700 text-sm">Belum ada arsip SK</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Silakan unggah dokumen Surat Keputusan (SK) baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="flex flex-col md:flex-row justify-between items-center px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-xs text-gray-500 gap-4">
                <div>
                    Menampilkan <span class="font-semibold text-gray-700">{{ $arsipSks->firstItem() ?? 0 }}</span> sampai
                    <span class="font-semibold text-gray-700">{{ $arsipSks->lastItem() ?? 0 }}</span> dari <span
                        class="font-semibold text-gray-700">{{ $arsipSks->total() }}</span> arsip
                </div>
                <div class="pagination-modern">
                    {{ $arsipSks->onEachSide(1)->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form Tambah / Edit Arsip SK -->
    <div id="modalFormSk"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col max-h-[90vh]">
            {{-- Modal Header --}}
            <div class="bg-gradient-to-r from-emerald-800 to-emerald-900 px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 id="modalFormTitle" class="text-sm font-bold flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload Arsip SK Baru
                </h3>
                <button type="button" onclick="closeFormModal()" class="text-emerald-200 hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Modal Body Form --}}
            <form id="formSk" action="{{ route('admin.arsip-sk.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto">
                @csrf
                <div id="methodField"></div>

                {{-- Nomor SK & Tahun --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Nomor SK <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="no_sk" id="form_no_sk" required placeholder="Contoh: SK/001/PPM/2026"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Tahun <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="tahun" id="form_tahun" required min="2000" max="{{ date('Y') + 2 }}" value="{{ date('Y') }}"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10">
                    </div>
                </div>

                {{-- Jenis Kegiatan & Jalur Pengajuan --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Jenis Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <select name="jenis" id="form_jenis" required
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 bg-white cursor-pointer">
                            <option value="penelitian">Penelitian</option>
                            <option value="pengabdian">Pengabdian Masyarakat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                            Jalur Pengajuan <span class="text-rose-500">*</span>
                        </label>
                        <select name="jalur" id="form_jalur" required
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 bg-white cursor-pointer">
                            <option value="simlitabkes">Simlitabkes</option>
                            <option value="mandiri">Mandiri</option>
                        </select>
                    </div>
                </div>

                {{-- Skema Kegiatan (Dinamis Sesuai Jalur & Jenis) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Skema Kegiatan <span class="text-gray-400 font-normal">(Dinamis sesuai jalur)</span>
                    </label>
                    <select name="skema_id" id="form_skema_id"
                        class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10 bg-white cursor-pointer">
                        <option value="">-- Pilih Skema Kegiatan --</option>
                    </select>
                </div>

                {{-- Upload File SK (PDF Max 5MB) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        File Dokumen SK (PDF, Maks. 5 MB) <span id="fileRequiredBadge" class="text-rose-500">*</span>
                    </label>
                    <input type="file" name="file" id="form_file" accept=".pdf,application/pdf"
                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 border border-gray-200 rounded-xl p-1.5 focus:outline-none">
                    <p id="fileHelpText" class="text-[11px] text-gray-400 mt-1">Format file harus berupa dokumen PDF dengan ukuran maksimal 5 MB.</p>
                </div>

                {{-- Keterangan / Catatan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Keterangan / Catatan <span class="text-gray-400 font-normal">(Opsional)</span>
                    </label>
                    <textarea name="keterangan" id="form_keterangan" rows="3" placeholder="Tambahkan catatan singkat mengenai SK ini..."
                        class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/10"></textarea>
                </div>

                {{-- Modal Actions --}}
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <button type="button" onclick="closeFormModal()"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl text-xs font-bold transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md transition cursor-pointer">
                        Simpan Arsip SK
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Form Hapus Tersembunyi (dipicu lewat SweetAlert2) --}}
    <form id="formDelete" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const allSkemas = @json($skemas);

        // --- DYNAMIC SKEMA UNTUK TOOLBAR FILTER ---
        const filterJenis = document.getElementById('filter_jenis_admin');
        const filterJalur = document.getElementById('filter_jalur_admin');
        const filterSkema = document.getElementById('filter_skema_admin');
        const currentFilterSkemaId = "{{ $filterSkema }}";

        function updateFilterSkemaDropdown() {
            const jalurVal = (filterJalur.value || '').trim().toLowerCase();
            const jenisVal = (filterJenis.value || '').trim().toLowerCase();

            filterSkema.innerHTML = '<option value="">Semua Skema</option>';

            let filtered = allSkemas;
            if (jalurVal) filtered = filtered.filter(item => (item.jalur || '').trim().toLowerCase() === jalurVal);
            if (jenisVal) filtered = filtered.filter(item => (item.jenis || '').trim().toLowerCase() === jenisVal);

            filtered.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                const jalurLabel = item.jalur ? ' (' + item.jalur.toUpperCase() + ')' : '';
                opt.textContent = item.nama + jalurLabel;
                if (String(item.id) === String(currentFilterSkemaId)) {
                    opt.selected = true;
                }
                filterSkema.appendChild(opt);
            });
        }

        if (filterJenis && filterJalur && filterSkema) {
            updateFilterSkemaDropdown();
            filterJenis.addEventListener('change', updateFilterSkemaDropdown);
            filterJalur.addEventListener('change', updateFilterSkemaDropdown);
        }

        // --- DYNAMIC SKEMA UNTUK MODAL FORM ---
        const formJenis = document.getElementById('form_jenis');
        const formJalur = document.getElementById('form_jalur');
        const formSkema = document.getElementById('form_skema_id');
        let formTargetSkemaId = null;

        function updateFormSkemaDropdown() {
            const jalurVal = (formJalur.value || '').trim().toLowerCase();
            const jenisVal = (formJenis.value || '').trim().toLowerCase();

            formSkema.innerHTML = '<option value="">-- Pilih Skema Kegiatan --</option>';

            let filtered = allSkemas;
            if (jalurVal) filtered = filtered.filter(item => (item.jalur || '').trim().toLowerCase() === jalurVal);
            if (jenisVal) filtered = filtered.filter(item => (item.jenis || '').trim().toLowerCase() === jenisVal);

            filtered.forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                const jalurLabel = item.jalur ? ' (' + item.jalur.toUpperCase() + ')' : '';
                opt.textContent = item.nama + jalurLabel;
                if (String(item.id) === String(formTargetSkemaId)) {
                    opt.selected = true;
                }
                formSkema.appendChild(opt);
            });
        }

        if (formJenis && formJalur && formSkema) {
            formJenis.addEventListener('change', updateFormSkemaDropdown);
            formJalur.addEventListener('change', updateFormSkemaDropdown);
        }

        // --- MODAL CONTROLS ---
        const modalForm = document.getElementById('modalFormSk');
        const formSk = document.getElementById('formSk');
        const modalTitle = document.getElementById('modalFormTitle');
        const methodField = document.getElementById('methodField');
        const formFile = document.getElementById('form_file');
        const fileReqBadge = document.getElementById('fileRequiredBadge');
        const fileHelpText = document.getElementById('fileHelpText');

        function openUploadModal() {
            formSk.reset();
            formSk.setAttribute('action', "{{ route('admin.arsip-sk.store') }}");
            methodField.innerHTML = '';
            modalTitle.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Upload Arsip SK Baru';
            formFile.setAttribute('required', 'required');
            fileReqBadge.style.display = 'inline';
            fileHelpText.textContent = 'Format file harus berupa dokumen PDF dengan ukuran maksimal 5 MB.';
            formTargetSkemaId = null;
            updateFormSkemaDropdown();
            modalForm.classList.remove('hidden');
        }

        function openEditModal(item) {
            formSk.reset();
            const updateUrl = "{{ route('admin.arsip-sk.update', ':id') }}".replace(':id', item.id);
            formSk.setAttribute('action', updateUrl);
            methodField.innerHTML = '@method("PUT")';
            modalTitle.innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Arsip SK';

            document.getElementById('form_no_sk').value = item.no_sk ?? '';
            document.getElementById('form_tahun').value = item.tahun ?? '{{ date("Y") }}';
            document.getElementById('form_jenis').value = item.jenis ?? 'penelitian';
            document.getElementById('form_jalur').value = item.jalur ?? 'simlitabkes';
            document.getElementById('form_keterangan').value = item.keterangan ?? '';

            formFile.removeAttribute('required');
            fileReqBadge.style.display = 'none';
            fileHelpText.innerHTML = '<span class="text-emerald-700 font-semibold">File saat ini: ' + (item.file_nama_asli ?? 'SK Document') + '</span>. Kosongkan jika tidak ingin mengganti file.';

            formTargetSkemaId = item.skema_id;
            updateFormSkemaDropdown();

            modalForm.classList.remove('hidden');
        }

        function closeFormModal() {
            modalForm.classList.add('hidden');
        }

        // Konfirmasi Hapus dengan SweetAlert2
        function openDeleteModal(deleteUrl, noSk) {
            Swal.fire({
                title: 'Hapus Arsip SK?',
                html: `Anda akan menghapus dokumen SK <b>${noSk}</b>.<br>File PDF terkait juga akan dihapus dari server. Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-trash mr-1"></i> Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-gray-100 p-4',
                    title: 'text-lg font-bold text-gray-900',
                    htmlContainer: 'text-xs text-gray-500',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md',
                    cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus Data...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    const formDelete = document.getElementById('formDelete');
                    formDelete.setAttribute('action', deleteUrl);
                    formDelete.submit();
                }
            });
        }

        // Toast Notifikasi Sukses
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3500,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#ffffff',
                color: '#1f2937',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-emerald-100 p-4 mt-4 mr-4',
                }
            });
        @endif

        // Toast Notifikasi Gagal
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 4500,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#ffffff',
                color: '#1f2937',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-rose-100 p-4 mt-4 mr-4',
                }
            });
        @endif
    </script>
@endsection

@extends('Admin.dashboard')

@section('title', 'Master Luaran - SIPPM')
@section('header_title', 'Master Luaran')
@section('header_breadcrumb', 'Menu Admin / Master Data / Luaran')

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

        /* Styling Modern & Profesional untuk Pagination */
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
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
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

        .pagination-modern nav span[aria-disabled="true"] span {
            background-color: #f9fafb !important;
            color: #9ca3af !important;
            border-color: #f3f4f6 !important;
            box-shadow: none !important;
        }
    </style>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid px-6 py-8">
        <!-- Header Page with Accent (Disamakan dengan Master Data Skema) -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-layer-group"></i> Master Data Sistem
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Master Luaran</h1>
                <p class="text-sm text-gray-500 mt-1">Mengacu pada Lampiran 13 (Penelitian) & Lampiran 14 (Pengabdian).</p>
            </div>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
                class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2 self-start md:self-auto">
                <i class="fa-solid fa-circle-plus text-sm"></i> Tambah Luaran
            </button>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden">

            <!-- Tab Navigasi Penelitian / Pengabdian -->
            <div class="px-6 pt-6 pb-4 border-b border-gray-100 flex items-center gap-3">
                <a href="{{ route('admin.master.luaran', ['jenis' => 'penelitian']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $jenis == 'penelitian' ? 'bg-emerald-800 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Penelitian
                </a>
                <a href="{{ route('admin.master.luaran', ['jenis' => 'pengabdian']) }}"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition {{ $jenis == 'pengabdian' ? 'bg-emerald-800 text-white shadow-md' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                    Pengabdian
                </a>
            </div>

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 w-16">No</th>
                            <th class="py-4 px-6">Jenis Luaran</th>
                            <th class="py-4 px-6">Opsi / Kategori</th>
                            <th class="py-4 px-6 w-28">Wajib</th>
                            <th class="py-4 px-6 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                        @forelse($luarans as $index => $item)
                            @php
                                // opsi di-cast 'array' di model, tapi bisa saja berisi string lama.
                                // Normalisasi di sini supaya blade selalu punya string yang aman dicetak.
                                $opsiText = is_array($item->opsi) ? implode(', ', $item->opsi) : $item->opsi ?? '';
                            @endphp
                            <tr class="hover:bg-emerald-50/30 transition duration-150 group">
                                <td class="py-4 px-6 font-medium text-gray-500">
                                    {{ $luarans->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    {{ $item->nama }}
                                </td>
                                <td class="py-4 px-6 text-gray-600">
                                    {{ $opsiText !== '' ? $opsiText : '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if ($item->wajib)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-100">
                                            Wajib
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">
                                            Tambahan
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalEdit('{{ $item->id }}', '{{ addslashes($item->nama) }}', '{{ addslashes($opsiText) }}', '{{ $item->wajib ? 1 : 0 }}', '{{ $item->jenis }}')"
                                            class="p-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm"
                                            title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <form id="delete-form-{{ $item->id }}"
                                            action="{{ route('admin.master.luaran.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="konfirmasiHapus('delete-form-{{ $item->id }}')"
                                                class="p-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition shadow-sm"
                                                title="Hapus Data">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                        <p class="text-sm font-medium">Belum ada data luaran untuk kategori ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div
                class="flex flex-col md:flex-row justify-between items-center px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-xs text-gray-500 gap-4">
                <div>
                    Menampilkan <span class="font-semibold text-gray-700">{{ $luarans->firstItem() ?? 0 }}</span> sampai
                    <span class="font-semibold text-gray-700">{{ $luarans->lastItem() ?? 0 }}</span> dari <span
                        class="font-semibold text-gray-700">{{ $luarans->total() }}</span> data
                </div>
                <div class="pagination-modern">
                    {{ $luarans->appends(['jenis' => $jenis])->onEachSide(1)->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-sm font-bold flex items-center gap-2"><i class="fa-solid fa-plus-circle"></i> Tambah Luaran
                    Baru</h3>
                <button onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                    class="text-emerald-200 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form action="{{ route('admin.master.luaran.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori Utama</label>
                    <select name="jenis"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="penelitian">Penelitian</option>
                        <option value="pengabdian">Pengabdian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Jenis Luaran</label>
                    <input type="text" name="nama" placeholder="Contoh: Artikel ilmiah dimuat di jurnal"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Opsi / Kategori (Opsional)</label>
                    <input type="text" name="opsi" placeholder="Contoh: Internasional, Nasional Terakreditasi"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[11px] text-gray-400 mt-1">Pisahkan dengan koma jika lebih dari satu opsi.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Wajib / Tambahan</label>
                    <select name="wajib"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="1">Wajib</option>
                        <option value="0">Tambahan</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modal-edit"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-sm font-bold flex items-center gap-2"><i class="fa-solid fa-pen-to-square"></i> Edit
                    Luaran</h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-emerald-200 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>
            <form id="form-edit" method="POST" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kategori Utama</label>
                    <select id="edit-jenis" name="jenis"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="penelitian">Penelitian</option>
                        <option value="pengabdian">Pengabdian</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Jenis Luaran</label>
                    <input type="text" id="edit-nama" name="nama"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Opsi / Kategori (Opsional)</label>
                    <input type="text" id="edit-opsi" name="opsi"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-[11px] text-gray-400 mt-1">Pisahkan dengan koma jika lebih dari satu opsi.</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Wajib / Tambahan</label>
                    <select id="edit-wajib" name="wajib"
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs bg-white outline-none focus:ring-2 focus:ring-emerald-500"
                        required>
                        <option value="1">Wajib</option>
                        <option value="0">Tambahan</option>
                    </select>
                </div>
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Konfirmasi Hapus dengan SweetAlert2 yang Lebih Estetik
        function konfirmasiHapus(formId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data luaran yang dihapus tidak dapat dikembalikan ke sistem!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#047857',
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
                    document.getElementById(formId).submit();
                }
            });
        }

        // Fungsi Buka Modal Edit
        function bukaModalEdit(id, nama, opsi, wajib, jenis) {
            document.getElementById('form-edit').action = `/admin/master/luaran/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-opsi').value = opsi === '-' ? '' : opsi;
            document.getElementById('edit-wajib').value = wajib;
            document.getElementById('edit-jenis').value = jenis;
            document.getElementById('modal-edit').classList.remove('hidden');
        }

        // Notifikasi Sukses Toast Modern
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#ffffff',
                color: '#1f2937',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-emerald-100 p-4 mt-4 mr-4',
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection

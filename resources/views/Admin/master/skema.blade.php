@extends('Admin.dashboard')

@section('title', 'Master Data Skema - SIPPM')
@section('header_title', 'Master Data Skema')
@section('header_breadcrumb', 'Menu Admin / Master Data / Skema')

@section('content')
    <style>
        /* Efek Gradasi & Transisi Halus */
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

        /* Styling Modern untuk Pagination Tailwind Laravel */
        .pagination-modern nav {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pagination-modern nav svg {
            width: 1rem;
            height: 1rem;
        }

        .pagination-modern nav span[aria-current="page"] span,
        .pagination-modern nav a,
        .pagination-modern nav span[aria-disabled="true"] span {
            font-size: 0.75rem !important;
            padding: 0.35rem 0.75rem !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e5e7eb !important;
            background-color: #ffffff;
            color: #4b5563;
            transition: all 0.2s ease;
        }

        .pagination-modern nav span[aria-current="page"] span {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%) !important;
            border-color: transparent !important;
            color: #ffffff !important;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(4, 120, 87, 0.2);
        }

        .pagination-modern nav a:hover {
            background-color: #ecfdf5 !important;
            color: #047857 !important;
            border-color: #a7f3d0 !important;
        }

        .pagination-modern nav span[aria-disabled="true"] span {
            background-color: #f9fafb !important;
            color: #9ca3af !important;
        }
    </style>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-fluid px-6 py-8">
        <!-- Header Page with Accent -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-layer-group"></i> Master Data Sistem
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Master Data Skema</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data skema kegiatan penelitian dan pengabdian kepada masyarakat.
                </p>
            </div>
            <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')"
                class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2 self-start md:self-auto">
                <i class="fa-solid fa-circle-plus text-sm"></i> Tambah Skema Baru
            </button>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden">

            <!-- Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6">No</th>
                            <th class="py-4 px-6">Kode</th>
                            <th class="py-4 px-6">Nama Skema</th>
                            <th class="py-4 px-6">Jenis Kegiatan</th>
                            <th class="py-4 px-6">Jalur</th>
                            <th class="py-4 px-6">Status</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-50">
                        @forelse($skemas as $index => $skema)
                            <tr class="hover:bg-emerald-50/30 transition duration-150 group">
                                <td class="py-4 px-6 font-medium text-gray-500">
                                    {{ $skemas->firstItem() + $index }}
                                </td>
                                <td class="py-4 px-6 font-mono text-emerald-800">
                                    <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-xs">
                                        {{ $skema->kode ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-900">
                                    {{ $skema->nama }}
                                </td>
                                <td class="py-4 px-6">
                                    @if ($skema->jenis == 'penelitian')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100 capitalize">
                                            <i class="fa-solid fa-flask mr-1.5 text-[10px]"></i> {{ $skema->jenis }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100 capitalize">
                                            <i class="fa-solid fa-hands-holding-child mr-1.5 text-[10px]"></i>
                                            {{ $skema->jenis }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 capitalize text-gray-700">
                                    {{ $skema->jalur ?? '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if (isset($skema->aktif) && $skema->aktif == 1)
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full"></span> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="w-1.5 h-1.5 mr-1.5 bg-rose-500 rounded-full"></span> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            onclick="bukaModalEdit('{{ $skema->id }}', '{{ addslashes($skema->nama) }}', '{{ $skema->jenis }}', '{{ $skema->jalur }}', '{{ $skema->kode }}', '{{ $skema->aktif }}')"
                                            class="p-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm"
                                            title="Edit Data">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <form id="delete-form-{{ $skema->id }}"
                                            action="{{ route('admin.master.skema.destroy', $skema->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="konfirmasiHapus('delete-form-{{ $skema->id }}')"
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
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                        <p class="text-sm font-medium">Belum ada data skema yang tersedia di database.</p>
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
                    Menampilkan <span class="font-semibold text-gray-700">{{ $skemas->firstItem() ?? 0 }}</span> sampai
                    <span class="font-semibold text-gray-700">{{ $skemas->lastItem() ?? 0 }}</span> dari <span
                        class="font-semibold text-gray-700">{{ $skemas->total() }}</span> data
                </div>
                <div class="pagination-modern">
                    {{ $skemas->onEachSide(1)->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div id="modal-tambah"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-sm font-bold flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i> Tambah Data Skema Baru
                </h3>
                <button onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                    class="text-emerald-200 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.master.skema.store') }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Skema</label>
                        <input type="text" name="kode" placeholder="Contoh: SKM-01"
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Skema</label>
                        <input type="text" name="nama" placeholder="Masukkan nama skema..."
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                            required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Kegiatan</label>
                            <select name="jenis"
                                class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                                required>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jalur</label>
                            <select name="jalur"
                                class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                                required>
                                <option value="" disabled selected>Pilih Jalur</option>
                                <option value="simlitabkes">Simlitabkes</option>
                                <option value="mandiri">Mandiri</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Keaktifan</label>
                        <select name="aktif"
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                            required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md transition">Simpan
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div id="modal-edit"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-sm font-bold flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Data Skema
                </h3>
                <button onclick="document.getElementById('modal-edit').classList.add('hidden')"
                    class="text-emerald-200 hover:text-white transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form id="form-edit" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Skema</label>
                        <input type="text" id="edit-kode" name="kode"
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Skema</label>
                        <input type="text" id="edit-nama" name="nama"
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition"
                            required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Kegiatan</label>
                            <select id="edit-jenis" name="jenis"
                                class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                                required>
                                <option value="penelitian">Penelitian</option>
                                <option value="pengabdian">Pengabdian</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jalur</label>
                            <select id="edit-jalur" name="jalur"
                                class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                                required>
                                <option value="" disabled>Pilih Jalur</option>
                                <option value="simlitabkes">Simlitabkes</option>
                                <option value="mandiri">Mandiri</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Status Keaktifan</label>
                        <select id="edit-aktif" name="aktif"
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white"
                            required>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-bold transition">Batal</button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md transition">Perbarui
                        Data</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Konfirmasi Hapus dengan SweetAlert2 yang Lebih Modern & Elegan
        function konfirmasiHapus(formId) {
            Swal.fire({
                title: 'Hapus Data Ini?',
                text: "Data skema yang dihapus akan hilang permanen dan tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#047857', // Emerald 700
                cancelButtonColor: '#ef4444', // Red 500 (Lebih modern)
                confirmButtonText: '<i class="fa-solid fa-trash mr-1.5"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fa-solid fa-xmark mr-1.5"></i> Batal',
                reverseButtons: true, // Tombol batal di kiri, konfirmasi di kanan (standar UI modern)
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-500',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md hover:opacity-90 transition',
                    cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md hover:opacity-90 transition'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading state saat menghapus agar terasa profesional
                    Swal.fire({
                        title: 'Sedang Menghapus...',
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

        // Buka Modal Edit
        function bukaModalEdit(id, nama, jenis, jalur, kode, aktif) {
            document.getElementById('form-edit').action = `/admin/master/skema/${id}`;
            document.getElementById('edit-nama').value = nama;
            document.getElementById('edit-jenis').value = jenis;
            document.getElementById('edit-jalur').value = (jalur === 'null' || !jalur) ? '' : jalur;
            document.getElementById('edit-kode').value = (kode === 'null' || !kode) ? '' : kode;
            document.getElementById('edit-aktif').value = aktif;
            document.getElementById('modal-edit').classList.remove('hidden');
        }

        // Notifikasi Sesi Berhasil (Tambah & Edit)
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true, // Ada garis waktu mundur di bawah pop-up
                showConfirmButton: false,
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-600'
                }
            });
        @endif

        // Notifikasi Sesi Gagal / Error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                text: '{{ session('error') }}',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#047857',
                padding: '1.5rem',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-800',
                    htmlContainer: 'text-sm text-gray-600',
                    confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md'
                }
            });
        @endif
    </script>
@endsection

@extends('Admin.dashboard')

@section('title', 'Master Rumpun Ilmu - SIPPM')
@section('header_title', 'Master Rumpun Ilmu')
@section('header_breadcrumb', 'Menu Admin / Master Data / Rumpun Ilmu')

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
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Master Rumpun Ilmu</h1>
            <p class="text-sm text-gray-500 mt-1">Mengacu pada Lampiran 2 daftar Rumpun, Bidang, dan Sub-bidang Ilmu resmi.</p>
        </div>
        <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" 
                class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2 self-start md:self-auto">
            <i class="fa-solid fa-circle-plus text-sm"></i> Tambah Rumpun Baru
        </button>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden">
        
        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 w-32">Kode</th>
                        <th class="py-4 px-6">Rumpun / Bidang Ilmu</th>
                        <th class="py-4 px-6 w-48">Level</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <!-- Ukuran font diseragamkan sepenuhnya menggunakan text-xs agar rapi dan seragam -->
                <tbody class="text-xs text-gray-700 divide-y divide-gray-50">
                    @forelse($rumpuns as $item)
                    <tr class="hover:bg-emerald-50/30 transition duration-150 group">
                        <td class="py-4 px-6 font-mono font-medium text-emerald-800 align-middle">
                            <span class="inline-block px-2.5 py-1 bg-emerald-50 border border-emerald-100 rounded-lg text-xs">
                                {{ $item->kode ?? '-' }}
                            </span>
                        </td>
                        <td class="py-4 px-6 align-middle">
                            <!-- Ukuran font disamakan semua (text-xs), indentasi diatur menggunakan pl-* untuk membedakan hirarki secara bersih -->
                            <div class="text-xs text-gray-700 {{ $item->level == 1 ? '' : ($item->level == 2 ? 'pl-4 font-medium' : 'pl-8') }}">
                                {{ $item->nama }}
                            </div>
                        </td>
                        <td class="py-4 px-6 align-middle">
                            @if($item->level == 1)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    Level 1 (Utama)
                                </span>
                            @elseif($item->level == 2)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    Level 2 (Bidang)
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Level 3 (Sub-bidang)
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="bukaModalEdit('{{ $item->id }}', '{{ $item->kode }}', '{{ addslashes($item->nama) }}', '{{ $item->level }}')" 
                                        class="p-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-2xs" title="Edit Data">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.master.rumpun.destroy', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="konfirmasiHapus('delete-form-{{ $item->id }}')" 
                                            class="p-2 bg-gray-50 border border-gray-200 rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition shadow-2xs" title="Hapus Data">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-12 text-gray-400">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                                <p class="text-xs font-medium">Belum ada data rumpun ilmu yang tersedia.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer -->
        <div class="flex flex-col md:flex-row justify-between items-center px-6 py-4 bg-gray-50/50 border-t border-gray-100 text-xs text-gray-500 gap-4">
            <div>
                Menampilkan <span class="font-semibold text-gray-700">{{ $rumpuns->firstItem() ?? 0 }}</span> sampai <span class="font-semibold text-gray-700">{{ $rumpuns->lastItem() ?? 0 }}</span> dari <span class="font-semibold text-gray-700">{{ $rumpuns->total() }}</span> data
            </div>
            <div class="pagination-modern">
                {{ $rumpuns->onEachSide(1)->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div id="modal-tambah" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all">
        <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-plus-circle"></i> Tambah Rumpun Ilmu Baru
            </h3>
            <button onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="text-emerald-200 hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form action="{{ route('admin.master.rumpun.store') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Rumpun</label>
                    <input type="text" name="kode" placeholder="Contoh: 340" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Rumpun / Bidang Ilmu</label>
                    <input type="text" name="nama" placeholder="Contoh: ILMU KESEHATAN" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Level Hirarki</label>
                    <select name="level" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white" required>
                        <option value="1">Level 1 (Rumpun Utama)</option>
                        <option value="2">Level 2 (Bidang Ilmu)</option>
                        <option value="3">Level 3 (Sub-bidang Ilmu)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="px-4 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-bold transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 transform transition-all">
        <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
            <h3 class="text-sm font-bold flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Rumpun Ilmu
            </h3>
            <button onclick="document.getElementById('modal-edit').classList.add('hidden')" class="text-emerald-200 hover:text-white transition">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-edit" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Kode Rumpun</label>
                    <input type="text" id="edit-kode" name="kode" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Rumpun / Bidang Ilmu</label>
                    <input type="text" id="edit-nama" name="nama" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Level Hirarki</label>
                    <select id="edit-level" name="level" class="w-full border border-gray-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none transition bg-white" required>
                        <option value="1">Level 1 (Rumpun Utama)</option>
                        <option value="2">Level 2 (Bidang Ilmu)</option>
                        <option value="3">Level 3 (Sub-bidang Ilmu)</option>
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('modal-edit').classList.add('hidden')" class="px-4 py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-bold transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md transition">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    /* Konfirmasi Hapus yang Sangat Profesional */
    function konfirmasiHapus(formId) {
        Swal.fire({
            title: 'Hapus Data Ini?',
            text: "Data rumpun ilmu yang dihapus akan hilang permanen dari sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#047857', 
            cancelButtonColor: '#6b7280',  
            confirmButtonText: '<i class="fa-solid fa-trash mr-1.5"></i> Ya, Hapus Permanen',
            cancelButtonText: '<i class="fa-solid fa-xmark mr-1.5"></i> Batal',
            reverseButtons: true, 
            padding: '1.75rem',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-gray-100',
                title: 'text-lg font-bold text-gray-800 tracking-tight',
                htmlContainer: 'text-xs text-gray-500 mt-2',
                confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md transition hover:opacity-90',
                cancelButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md transition hover:bg-gray-700'
            }
        }).then((result) => { 
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Penghapusan',
                    text: 'Sedang menghapus data dari database...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    padding: '1.75rem',
                    customClass: {
                        popup: 'rounded-3xl shadow-2xl border border-gray-100',
                        title: 'text-base font-bold text-gray-800',
                        htmlContainer: 'text-xs text-gray-500'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                document.getElementById(formId).submit(); 
            }
        });
    }

    /* Buka Modal Edit Rumpun Ilmu */
    function bukaModalEdit(id, kode, nama, level) {
        let url = "{{ route('admin.master.rumpun.update', ':id') }}";
        url = url.replace(':id', id);

        document.getElementById('form-edit').action = url;
        document.getElementById('edit-kode').value = (kode === 'null' || !kode) ? '' : kode;
        document.getElementById('edit-nama').value = nama;
        document.getElementById('edit-level').value = level;
        document.getElementById('modal-edit').classList.remove('hidden');
    }

    /* Notifikasi Sukses Profesional (Tambah, Edit, Hapus) */
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 3500,
            timerProgressBar: true, 
            showConfirmButton: false,
            padding: '1.75rem',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-emerald-100',
                title: 'text-lg font-bold text-gray-800',
                htmlContainer: 'text-xs text-gray-600'
            }
        });
    @endif

    /* Notifikasi Gagal / Error Profesional */
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: '{{ session('error') }}',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#047857',
            padding: '1.75rem',
            customClass: {
                popup: 'rounded-3xl shadow-2xl border border-red-100',
                title: 'text-lg font-bold text-gray-800',
                htmlContainer: 'text-xs text-gray-600',
                confirmButton: 'px-5 py-2.5 rounded-xl text-xs font-semibold shadow-md'
            }
        });
    @endif
</script>
@endsection
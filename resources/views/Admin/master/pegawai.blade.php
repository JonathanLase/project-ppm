@extends('Admin.dashboard')

@section('title', 'Manajemen User - SIPPM')
@section('header_title', 'Manajemen User')
@section('header_breadcrumb', 'Menu Admin / Master Data / Manajemen User')

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
        <!-- Header Page with Accent -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-100 gap-4">
            <div>
                <div class="flex items-center gap-2 text-emerald-600 font-semibold text-xs tracking-wider uppercase mb-1">
                    <i class="fa-solid fa-users-gear"></i> Master Data Sistem
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen User</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola akun admin & dosen — tambah, ubah, hapus, atau import massal via
                    Excel.</p>
            </div>

            <div class="flex items-center gap-3 self-start md:self-auto">
                <button onclick="document.getElementById('modalImport').classList.remove('hidden')"
                    class="px-4 py-2.5 bg-white border border-gray-200 text-gray-700 text-xs font-semibold rounded-xl shadow-sm hover:bg-gray-50 transition flex items-center gap-2">
                    <i class="fa-solid fa-file-excel text-emerald-600"></i> Import Excel
                </button>
                <button onclick="openTambahModal()"
                    class="px-5 py-2.5 btn-gradient-animate text-white text-xs font-semibold rounded-xl shadow-md flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah User
                </button>
            </div>
        </div>

        <!-- Main Card Container -->
        <div class="bg-white rounded-2xl custom-card-shadow border border-gray-100 overflow-hidden">

            @if ($errors->any())
                <div class="m-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-bold space-y-1">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Tabel Data Pegawai -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-gray-50/75 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 w-16">No</th>
                            <th class="py-4 px-6">NIP / NIDN</th>
                            <th class="py-4 px-6">Nama Lengkap</th>
                            <th class="py-4 px-6">Jabatan / Pangkat</th>
                            <th class="py-4 px-6">Jurusan / Prodi</th>
                            <th class="py-4 px-6">Kontak</th>
                            <th class="py-4 px-6 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                        @forelse($pegawais as $index => $pegawai)
                            <tr class="hover:bg-emerald-50/30 transition duration-150 group">
                                <td class="py-4 px-6 font-medium text-gray-500">{{ $pegawais->firstItem() + $index }}</td>
                                <td class="py-4 px-6 font-bold text-gray-900">
                                    {{ $pegawai->nip }}
                                    @if ($pegawai->nidn)
                                        <span class="block text-[11px] text-gray-400 font-normal">NIDN:
                                            {{ $pegawai->nidn }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-bold text-gray-900">
                                    {{ $pegawai->nama }}
                                    <span class="block text-[11px] text-emerald-700 font-semibold uppercase">Role:
                                        {{ $pegawai->role }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    {{ $pegawai->jabatan ?? '-' }}
                                    <span class="block text-[11px] text-gray-400">{{ $pegawai->pangkat ?? '' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    {{ $pegawai->jurusan ?? '-' }}
                                    <span class="block text-[11px] text-gray-400">Prodi:
                                        {{ $pegawai->prodi ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    {{ $pegawai->email ?? '-' }}
                                    <span class="block text-[11px] text-gray-400">HP: {{ $pegawai->hp ?? '-' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick='openEditModal(@json($pegawai))'
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-sky-50 text-sky-600 hover:bg-sky-100 transition"
                                            title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </button>
                                        <button type="button"
                                            onclick="openDeleteModal('{{ route('admin.master.pegawai.destroy', $pegawai->id) }}', @js($pegawai->nama))"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 transition"
                                            title="Hapus">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">
                                    <div
                                        class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center mx-auto mb-3">
                                        <i class="fa-solid fa-users-slash text-lg"></i>
                                    </div>
                                    <p class="font-bold text-gray-700 text-sm">Belum ada data pegawai</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Silakan tambah manual atau import data pegawai
                                        menggunakan file Excel.</p>
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
                    Menampilkan <span class="font-semibold text-gray-700">{{ $pegawais->firstItem() ?? 0 }}</span> sampai
                    <span class="font-semibold text-gray-700">{{ $pegawais->lastItem() ?? 0 }}</span> dari <span
                        class="font-semibold text-gray-700">{{ $pegawais->total() }}</span> data
                </div>
                <div class="pagination-modern">
                    {{ $pegawais->onEachSide(1)->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah / Edit User -->
    <div id="modalForm"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-100 max-h-[90vh] flex flex-col">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white shrink-0">
                <h3 id="modalFormTitle" class="text-sm font-bold flex items-center gap-2">
                    <i class="fa-solid fa-user-plus"></i> Tambah User
                </h3>
                <button type="button" onclick="closeFormModal()" class="text-emerald-200 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="formPegawai" action="{{ route('admin.master.pegawai.store') }}" method="POST"
                class="p-6 space-y-4 overflow-y-auto">
                @csrf
                <div id="methodField"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIP <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="nip" id="f_nip" required
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">NIDN</label>
                        <input type="text" name="nidn" id="f_nidn"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Nama Lengkap <span
                            class="text-rose-500">*</span></label>
                    <input type="text" name="nama" id="f_nama" required
                        class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Password <span id="f_password_required" class="text-rose-500">*</span>
                        </label>
                        <input type="password" name="password" id="f_password" placeholder="Kosongkan jika tidak diubah"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Role <span
                                class="text-rose-500">*</span></label>
                        <select name="role" id="f_role" required
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600 bg-white">
                            <option value="dosen">Dosen</option>
                            <option value="admin">Admin</option>
                            <option value="petugas_ppm">Petugas PPM</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jabatan</label>
                        <input type="text" name="jabatan" id="f_jabatan"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pangkat</label>
                        <input type="text" name="pangkat" id="f_pangkat"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jurusan</label>
                        <input type="text" name="jurusan" id="f_jurusan"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Prodi</label>
                        <input type="text" name="prodi" id="f_prodi"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Email</label>
                        <input type="email" name="email" id="f_email"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">No. HP</label>
                        <input type="text" name="hp" id="f_hp"
                            class="w-full border border-gray-200 rounded-xl p-2.5 text-xs focus:outline-none focus:border-emerald-600">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeFormModal()"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Hapus Tersembunyi (submit dipicu lewat SweetAlert2) -->
    <form id="formDelete" action="" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Modal Pop-up Import Excel -->
    <div id="modalImport"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100">
            <div class="bg-emerald-900 px-6 py-4 flex justify-between items-center text-white">
                <h3 class="text-sm font-bold flex items-center gap-2"><i class="fa-solid fa-file-excel"></i> Import Data
                    Pegawai</h3>
                <button onclick="document.getElementById('modalImport').classList.add('hidden')"
                    class="text-emerald-200 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form action="{{ route('admin.master.pegawai.import') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Pilih File Excel (.xlsx / .csv)</label>
                    <input type="file" name="file" accept=".xlsx, .xls, .csv" required
                        class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-800 hover:file:bg-emerald-100 border border-gray-200 rounded-xl p-1.5">
                    <p class="text-[11px] text-gray-400 mt-1.5">Kolom excel yang didukung: <b>nip, nama, password, role,
                            jabatan, pangkat, jurusan, prodi, email, hp, nidn</b>.</p>
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="document.getElementById('modalImport').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl text-xs font-bold">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 btn-gradient-animate text-white rounded-xl text-xs font-bold shadow-md">
                        Upload & Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const routeStore = "{{ route('admin.master.pegawai.store') }}";
        // Template URL update, __ID__ akan diganti dinamis
        const routeUpdateTemplate = "{{ route('admin.master.pegawai.update', ['id' => '__ID__']) }}";

        function openTambahModal() {
            document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-user-plus"></i> Tambah User';
            document.getElementById('formPegawai').setAttribute('action', routeStore);
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('formPegawai').reset();
            document.getElementById('f_password').setAttribute('required', 'required');
            document.getElementById('f_password').setAttribute('placeholder', '');
            document.getElementById('f_password_required').style.display = 'inline';
            document.getElementById('modalForm').classList.remove('hidden');
        }

        function openEditModal(pegawai) {
            document.getElementById('modalFormTitle').innerHTML = '<i class="fa-solid fa-user-pen"></i> Edit User';
            const updateUrl = routeUpdateTemplate.replace('__ID__', pegawai.id);
            document.getElementById('formPegawai').setAttribute('action', updateUrl);
            document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';

            document.getElementById('f_nip').value = pegawai.nip ?? '';
            document.getElementById('f_nidn').value = pegawai.nidn ?? '';
            document.getElementById('f_nama').value = pegawai.nama ?? '';
            document.getElementById('f_password').value = '';
            document.getElementById('f_password').removeAttribute('required');
            document.getElementById('f_password').setAttribute('placeholder', 'Kosongkan jika tidak diubah');
            document.getElementById('f_password_required').style.display = 'none';
            document.getElementById('f_role').value = pegawai.role ?? 'dosen';
            document.getElementById('f_jabatan').value = pegawai.jabatan ?? '';
            document.getElementById('f_pangkat').value = pegawai.pangkat ?? '';
            document.getElementById('f_jurusan').value = pegawai.jurusan ?? '';
            document.getElementById('f_prodi').value = pegawai.prodi ?? '';
            document.getElementById('f_email').value = pegawai.email ?? '';
            document.getElementById('f_hp').value = pegawai.hp ?? '';

            document.getElementById('modalForm').classList.remove('hidden');
        }

        function closeFormModal() {
            document.getElementById('modalForm').classList.add('hidden');
        }

        // Konfirmasi Hapus dengan SweetAlert2
        function openDeleteModal(deleteUrl, namaPegawai) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Anda akan menghapus data <b>${namaPegawai}</b>.<br>Tindakan ini tidak dapat dibatalkan.`,
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

        // Kalau ada error validasi (redirect back), otomatis buka modal tambah
        @if ($errors->any() && !session('success'))
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('modalForm').classList.remove('hidden');
            });
        @endif

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

        // Notifikasi Gagal Toast Modern
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                background: '#ffffff',
                color: '#1f2937',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-rose-100 p-4 mt-4 mr-4',
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        @endif
    </script>
@endsection

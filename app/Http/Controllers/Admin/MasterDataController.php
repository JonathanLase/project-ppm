<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skema;
use App\Models\RumpunIlmu;
use App\Models\LuaranMaster;
use App\Models\Pegawai;
use App\Imports\PegawaiImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MasterDataController extends Controller
{
    // ==================== MASTER SKEMA ====================

    public function skemaIndex(Request $request)
    {
        $skemas = Skema::paginate(10);
        return view('Admin.master.skema', compact('skemas'));
    }

    public function skemaStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:penelitian,pengabdian',
            'aktif' => 'required|boolean',
        ]);

        Skema::create($request->all());

        return redirect()->route('admin.master.skema')->with('success', 'Data skema berhasil ditambahkan.');
    }

    public function skemaUpdate(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jenis' => 'required|in:penelitian,pengabdian',
            'aktif' => 'required|boolean',
        ]);

        $skema = Skema::findOrFail($id);
        $skema->update($request->all());

        return redirect()->route('admin.master.skema')->with('success', 'Data skema berhasil diperbarui.');
    }

    public function skemaDestroy($id)
    {
        $skema = Skema::findOrFail($id);
        $skema->delete();

        return redirect()->route('admin.master.skema')->with('success', 'Data skema berhasil dihapus.');
    }


    // ==================== MASTER RUMPUN ILMU ====================

    public function rumpunIlmuIndex(Request $request)
    {
        $rumpuns = RumpunIlmu::orderBy('kode', 'asc')->paginate(15);
        return view('Admin.master.rumpun', compact('rumpuns'));
    }

    public function rumpunStore(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:rumpun_ilmu,kode',
            'nama' => 'required|string|max:255',
            'level' => 'required|in:1,2,3',
        ]);

        RumpunIlmu::create($request->all());

        return redirect()->back()->with('success', 'Rumpun ilmu berhasil ditambahkan.');
    }

    public function rumpunUpdate(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:rumpun_ilmu,kode,' . $id,
            'nama' => 'required|string|max:255',
            'level' => 'required|in:1,2,3',
        ]);

        $rumpun = RumpunIlmu::findOrFail($id);
        $rumpun->update($request->all());

        return redirect()->back()->with('success', 'Rumpun ilmu berhasil diperbarui.');
    }

    public function rumpunDestroy($id)
    {
        $rumpun = RumpunIlmu::findOrFail($id);
        $rumpun->delete();

        return redirect()->back()->with('success', 'Rumpun ilmu berhasil dihapus.');
    }


    // ==================== MASTER LUARAN ====================

    public function luaranIndex(Request $request)
    {
        $jenis = $request->get('jenis', 'penelitian');
        $luarans = LuaranMaster::where('jenis', $jenis)->paginate(10);

        return view('Admin.master.luaran', compact('luarans', 'jenis'));
    }

    public function luaranStore(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:penelitian,pengabdian',
            'nama' => 'required|string|max:255',
            'opsi' => 'nullable|string',
            'wajib' => 'required|boolean',
        ]);

        LuaranMaster::create($request->all());

        return redirect()->back()->with('success', 'Data luaran berhasil ditambahkan.');
    }

    public function luaranUpdate(Request $request, $id)
    {
        $request->validate([
            'jenis' => 'required|in:penelitian,pengabdian',
            'nama' => 'required|string|max:255',
            'opsi' => 'nullable|string',
            'wajib' => 'required|boolean',
        ]);

        $luaran = LuaranMaster::findOrFail($id);
        $luaran->update($request->all());

        return redirect()->back()->with('success', 'Data luaran berhasil diperbarui.');
    }

    public function luaranDestroy($id)
    {
        $luaran = LuaranMaster::findOrFail($id);
        $luaran->delete();

        return redirect()->back()->with('success', 'Data luaran berhasil dihapus.');
    }


    // ==================== MASTER PEGAWAI (MANAJEMEN USER) & IMPORT ====================

    // Menampilkan halaman manajemen user / data pegawai
    public function pegawaiIndex(Request $request)
    {
        $pegawais = Pegawai::latest()->paginate(10);
        return view('Admin.master.pegawai', compact('pegawais'));
    }

    // Tambah pegawai baru (CRUD - Create)
    public function pegawaiStore(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:pegawais,nip',
            'nama' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,dosen,petugas_ppm',
            'jabatan' => 'nullable|string|max:100',
            'pangkat' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:150',
            'prodi' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150|unique:pegawais,email',
            'hp' => 'nullable|string|max:20',
            'nidn' => 'nullable|string|max:50',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'email.unique' => 'Email sudah digunakan pegawai lain.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['must_change_password'] = true;

        Pegawai::create($validated);

        return redirect()->route('admin.master.pegawai')->with('success', 'Data pegawai berhasil ditambahkan.');
    }

    // Ubah data pegawai (CRUD - Update)
    public function pegawaiUpdate(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        $validated = $request->validate([
            'nip' => 'required|string|max:50|unique:pegawais,nip,' . $pegawai->id,
            'nama' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,dosen,petugas_ppm',
            'jabatan' => 'nullable|string|max:100',
            'pangkat' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:150',
            'prodi' => 'nullable|string|max:150',
            'email' => 'nullable|email|max:150|unique:pegawais,email,' . $pegawai->id,
            'hp' => 'nullable|string|max:20',
            'nidn' => 'nullable|string|max:50',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nama.required' => 'Nama wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'email.unique' => 'Email sudah digunakan pegawai lain.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $pegawai->update($validated);

        return redirect()->route('admin.master.pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    // Hapus pegawai (CRUD - Delete)
    public function pegawaiDestroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        if ($pegawai->id === auth()->id()) {
            return redirect()->route('admin.master.pegawai')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $pegawai->delete();

        return redirect()->route('admin.master.pegawai')->with('success', 'Data pegawai berhasil dihapus.');
    }

    // Proses import data pegawai dari file Excel/CSV
    public function pegawaiImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'Silakan pilih file terlebih dahulu.',
            'file.mimes' => 'Format file harus berupa Excel (.xlsx, .xls) atau CSV.',
            'file.max' => 'Ukuran file maksimal adalah 2MB.',
        ]);

        try {
            Excel::import(new PegawaiImport, $request->file('file'));
            return redirect()->route('admin.master.pegawai')->with('success', 'Data pegawai berhasil di-import!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat meng-import data: ' . $e->getMessage());
        }
    }

    // Download template excel pegawai kosong
    public function pegawaiDownloadTemplate(): BinaryFileResponse
    {
        $path = public_path('templates/template_pegawai.xlsx');

        // Pastikan Anda sudah meletakkan file template di public/templates/template_pegawai.xlsx
        return response()->download($path, 'Template_Import_Pegawai.xlsx');
    }
}

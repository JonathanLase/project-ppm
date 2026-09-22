<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Halaman login user/dosen (beranda "/").
     */
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'dosen') {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required|string',
            'password' => 'required|string',
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $pegawai = Pegawai::where('nip', $request->nip)
            ->where('role', 'dosen')
            ->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return back()
                ->withErrors(['login' => 'NIP atau password salah. Silakan periksa kembali.'])
                ->withInput($request->only('nip'));
        }

        Auth::login($pegawai, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($pegawai->must_change_password) {
            return redirect()->route('ubah-password');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Halaman login admin terpisah, diakses lewat "/login".
     */
    public function showAdminLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $pegawai = Pegawai::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$pegawai || !Hash::check($request->password, $pegawai->password)) {
            return back()
                ->withErrors(['login' => 'Alamat email atau kata sandi salah. Silakan periksa kembali.'])
                ->withInput($request->only('email'));
        }

        Auth::login($pegawai, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect('/admin/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function showUbahPassword()
    {
        return view('auth.ubah-password');
    }

    public function ubahPassword(Request $request)
    {
        $request->validate([
            'password_baru' => 'required|string|min:6|confirmed',
        ], [
            'password_baru.min' => 'Password baru minimal 6 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password_baru),
            'must_change_password' => false,
        ]);

        if ($user->role === 'petugas_ppm') {
            return redirect()->route('petugas.dashboard')->with('success', 'Password berhasil diperbarui.');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Password berhasil diperbarui.');
        }

        return redirect()->route('dashboard')->with('success', 'Password berhasil diperbarui.');
    }
}

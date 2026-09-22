<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal Admin — SIPPM Poltekkes Kemenkes Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* Kustomisasi warna tombol hijau agar lebih lembut dan tidak terlalu pekat */
        .btn-custom-green {
            width: 100%;
            justify-content: center;
            background-color: #2e7d32;
            /* Hijau yang lebih ramah di mata (forest green medium) */
            border-color: #2e7d32;
            color: #fff;
            padding: 12px;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-custom-green:hover {
            background-color: #256428;
            /* Sedikit lebih gelap saat kursor diarahkan */
        }
    </style>
</head>

<body>
    <div class="admin-login-bg">
        <div class="admin-login-card">
            <div class="admin-login-brand">
                <img src="{{ asset('img/logo-full.png') }}" alt="Logo Poltekkes Kemenkes Medan">
            </div>
            <h1>Login Portal Admin</h1>
            <div class="sub">Masukkan kredensial Anda untuk mengakses<br>dashboard sistem admin.</div>
            {{-- Menampilkan pesan error jika NIP/password salah --}}
            @if ($errors->any())
                <div class="login-alert"
                    style="display:flex; background-color: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-size: 14px;">
                    {{ $errors->first() }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="field">
                    <label>NIP</label>
                    <div class="field-icon">
                        <input type="text" name="nip" placeholder="Masukkan NIP Admin"
                            value="{{ old('nip') }}" autocomplete="username" required>
                    </div>
                </div>
                <div class="field">
                    <div class="field-row-label">
                        <label style="margin-bottom:0;">KATA SANDI</label>
                        <a href="#"
                            onclick="alert('Silakan hubungi Superadmin untuk reset kata sandi.'); return false;">Lupa
                            sandi?</a>
                    </div>
                    <div class="field-icon">
                        <input type="password" name="password" placeholder="Masukkan kata sandi"
                            autocomplete="current-password" required>
                    </div>
                </div>
                <label class="admin-remember">
                    <input type="checkbox" name="remember" checked> Ingat perangkat ini
                </label>
                <button class="btn btn-primary btn-custom-green" type="submit">Masuk Sekarang</button>
            </form>
            <a href="{{ route('login') }}" class="admin-login-back">&larr; Kembali ke Halaman Login Dosen</a>
        </div>
    </div>
</body>

</html>

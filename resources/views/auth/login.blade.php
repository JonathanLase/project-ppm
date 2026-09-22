<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIPPM Poltekkes Kemenkes Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/logo-icon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ====== OVERRIDE STYLE: mempercantik halaman login (khusus halaman ini) ======
         Tidak menyentuh app.css supaya halaman lain tidak terpengaruh. ====== --}}
    <style>
        #loginScreen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f7f6;
            padding: 24px;
        }

        #loginScreen .login-wrap {
            width: 100%;
            max-width: 1120px;
            min-height: 620px;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 24px 60px -20px rgba(11, 61, 46, 0.35), 0 0 0 1px rgba(11, 61, 46, 0.04);
            animation: loginFadeIn 0.5s ease both;
        }

        @keyframes loginFadeIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---------- Sisi Kiri (Brand / Hero) ---------- */
        #loginScreen .login-side {
            position: relative;
            background: linear-gradient(155deg, #0e4a37 0%, #0b3d2e 55%, #093a2b 100%);
            color: #d8ece3;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        #loginScreen .login-side::before,
        #loginScreen .login-side::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
            pointer-events: none;
        }

        #loginScreen .login-side::before {
            width: 340px;
            height: 340px;
            top: -120px;
            right: -100px;
        }

        #loginScreen .login-side::after {
            width: 260px;
            height: 260px;
            bottom: -100px;
            left: -80px;
            background: rgba(255, 255, 255, 0.045);
        }

        #loginScreen .login-side-deco {
            position: absolute;
            inset: 0;
            pointer-events: none;
            opacity: 0.5;
        }

        #loginScreen .login-brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        #loginScreen .login-brand .brand-mark {
            width: 42px;
            height: 42px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        }

        #loginScreen .login-brand b {
            display: block;
            font-size: 16px;
            color: #fff;
            letter-spacing: 0.2px;
        }

        #loginScreen .login-brand span {
            display: block;
            font-size: 12px;
            color: #9fc6b6;
        }

        #loginScreen .login-hero {
            position: relative;
            z-index: 1;
            margin-top: 8px;
        }

        #loginScreen .login-hero h2 {
            font-size: 29px;
            line-height: 1.28;
            font-weight: 800;
            color: #fff;
            margin: 0 0 14px;
            letter-spacing: -0.3px;
        }

        #loginScreen .login-hero p {
            font-size: 14px;
            line-height: 1.65;
            color: #bcdccf;
            margin: 0 0 30px;
            max-width: 420px;
        }

        #loginScreen .login-feat {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        #loginScreen .login-feat li {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        #loginScreen .login-feat .ic {
            flex: none;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.14);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        #loginScreen .login-feat li:hover .ic {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        #loginScreen .login-feat .ic svg {
            width: 18px;
            height: 18px;
            stroke: #fff;
        }

        #loginScreen .login-feat b {
            display: block;
            color: #fff;
            font-size: 13.5px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        #loginScreen .login-feat div>div {
            font-size: 12.5px;
            color: #a8cfbf;
            line-height: 1.5;
        }

        #loginScreen .login-foot {
            position: relative;
            z-index: 1;
            font-size: 11.5px;
            color: #7fae9a;
        }

        /* ---------- Sisi Kanan (Form) ---------- */
        #loginScreen .login-form-side {
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        #loginScreen .login-box {
            width: 100%;
            max-width: 360px;
        }

        #loginScreen .login-box h1 {
            font-size: 24px;
            font-weight: 800;
            color: #10241c;
            margin: 0 0 6px;
            letter-spacing: -0.3px;
        }

        #loginScreen .login-box .sub {
            font-size: 13px;
            color: #6b7c76;
            margin-bottom: 28px;
        }

        #loginScreen .login-alert {
            display: none;
            align-items: center;
            gap: 8px;
            background: #fdecec;
            border: 1px solid #f5c2c2;
            color: #c22b2b;
            font-size: 12.5px;
            font-weight: 600;
            padding: 11px 14px;
            border-radius: 10px;
            margin-bottom: 18px;
        }

        #loginScreen .field {
            margin-bottom: 18px;
        }

        #loginScreen .field label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #24382f;
            margin-bottom: 7px;
        }

        #loginScreen .field input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px 14px;
            border: 1.5px solid #e2e8e5;
            border-radius: 11px;
            font-size: 13.5px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #fafcfb;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        #loginScreen .field input::placeholder {
            color: #a3b0ab;
        }

        #loginScreen .field input:focus {
            outline: none;
            border-color: #0b3d2e;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(11, 61, 46, 0.1);
        }

        #loginScreen .pw-wrap {
            position: relative;
        }

        #loginScreen .pw-wrap input {
            padding-right: 44px;
        }

        #loginScreen .pw-wrap button {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #6b7c76;
            transition: background 0.15s ease, color 0.15s ease;
        }

        #loginScreen .pw-wrap button:hover {
            background: #eef3f1;
            color: #0b3d2e;
        }

        #loginScreen .pw-wrap svg {
            width: 18px;
            height: 18px;
        }

        #loginScreen .login-remember {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: #4c5c56;
            margin-bottom: 24px;
        }

        #loginScreen .login-remember label {
            display: flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
        }

        #loginScreen .login-remember a {
            color: #0b3d2e;
            font-weight: 700;
            text-decoration: none;
        }

        #loginScreen .login-remember a:hover {
            text-decoration: underline;
        }

        #loginScreen .btn.btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0e4a37, #0b3d2e);
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            padding: 13px 18px;
            border: none;
            border-radius: 11px;
            cursor: pointer;
            box-shadow: 0 8px 20px -6px rgba(11, 61, 46, 0.5);
            transition: transform 0.15s ease, box-shadow 0.15s ease, filter 0.15s ease;
        }

        #loginScreen .btn.btn-primary:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
            box-shadow: 0 10px 24px -6px rgba(11, 61, 46, 0.55);
        }

        #loginScreen .btn.btn-primary:active {
            transform: translateY(0);
        }

        @media (max-width: 880px) {
            #loginScreen .login-wrap {
                grid-template-columns: 1fr;
                min-height: 0;
            }

            #loginScreen .login-side {
                padding: 36px 28px;
            }

            #loginScreen .login-hero h2 {
                font-size: 23px;
            }

            #loginScreen .login-form-side {
                padding: 36px 28px;
            }
        }
    </style>
</head>

<body>
    <div id="loginScreen">
        <div class="login-wrap">
            <div class="login-side">
                <div class="login-brand">
                    <div class="brand-mark"><img src="{{ asset('img/logo-icon.png') }}" alt="Logo"
                            style="width:100%; height:100%; object-fit:contain; padding:5px;"></div>
                    <div><b>SIPPM</b><span>Poltekkes Kemenkes Medan</span></div>
                </div>
                <div class="login-hero">
                    <h2>Kelola Penelitian &amp; Pengabdian Masyarakat dalam Satu Sistem</h2>
                    <p>Ajukan proposal, pantau progres validasi, unggah laporan, hingga catat luaran penelitian dan
                        pengabdian Anda secara terintegrasi.</p>
                    <ul class="login-feat">
                        <li>
                            <div class="ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                    <path d="M15 5l4 4" />
                                </svg>
                            </div>
                            <div><b>Pengajuan Digital</b>Ajukan proposal penelitian & pengabdian tanpa kertas.</div>
                        </li>
                        <li>
                            <div class="ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="9" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                            </div>
                            <div><b>Pantau Real-time</b>Lihat status validasi setiap tahap pengajuan Anda.</div>
                        </li>
                        <li>
                            <div class="ic">
                                <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M12 2l2.9 6.3 6.9.6-5.2 4.6 1.6 6.8L12 16.9 5.8 20.3l1.6-6.8L2.2 8.9l6.9-.6Z" />
                                </svg>
                            </div>
                            <div><b>Catat Luaran</b>Rekam seluruh capaian & luaran kegiatan Anda.</div>
                        </li>
                    </ul>
                </div>
                <div class="login-foot">&copy; {{ date('Y') }} Poltekkes Kemenkes Medan — SIPPM</div>
            </div>

            <div class="login-form-side">
                <div class="login-box">
                    <h1>Masuk ke Akun Anda</h1>
                    <div class="sub">Gunakan NIP dan password yang telah terdaftar di sistem.</div>

                    <div class="login-alert" style="{{ $errors->has('login') ? 'display:flex;' : '' }}">
                        {{ $errors->first('login') }}
                    </div>

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf
                        <div class="field">
                            <label>NIP</label>
                            <input type="text" name="nip" placeholder="Masukkan NIP Anda"
                                value="{{ old('nip') }}" autocomplete="username" required>
                        </div>
                        <div class="field">
                            <label>Password</label>
                            <div class="pw-wrap">
                                <input id="loginPass" type="password" name="password" placeholder="Masukkan password"
                                    autocomplete="current-password" required>
                                <button type="button" onclick="togglePw()" id="pwToggleBtn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="login-remember">
                            <label><input type="checkbox" name="remember"> Ingat saya</label>
                            <a href="#"
                                onclick="alert('Silakan hubungi Admin SIPPM untuk reset password.'); return false;">Lupa
                                password?</a>
                        </div>
                        <button class="btn btn-primary" style="width:100%;" type="submit">Masuk</button>
                    </form>

                    <div style="text-align:center; margin-top:18px; font-size:11.5px; color:var(--ink-500);">
                        Login sebagai Admin? <a href="{{ route('admin.login') }}"
                            style="color:#0b3d2e; font-weight:700; text-decoration:none;">Masuk lewat Portal
                            Admin &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function togglePw() {
            const i = document.getElementById('loginPass');
            i.type = i.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>

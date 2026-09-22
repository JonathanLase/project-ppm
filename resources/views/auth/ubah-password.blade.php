@extends('layouts.app')

@section('title', 'Ubah Password')
@section('crumbs', 'Ubah Password')

@section('content')
    <style>
        /* ==== Ubah Password — scoped styles ==== */
        .up-wrap {
            max-width: 620px;
            margin: 24px auto 0;
        }

        .up-card {
            background: #fff;
            border: 1px solid #eef2f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(16, 24, 40, .04);
            overflow: hidden;
        }

        .up-head {
            background: linear-gradient(120deg, #0b3d2e 0%, #0f5c44 45%, #00875A 100%);
            padding: 32px 34px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .up-head .icon {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            flex: none;
            background: rgba(255, 255, 255, .18);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .up-head h3 {
            color: #fff;
            margin: 0 0 4px;
            font-size: 19px;
        }

        .up-head .sub {
            color: rgba(255, 255, 255, .85);
            font-size: 13.5px;
            margin: 0;
        }

        .up-first-login {
            margin: 18px 20px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12.5px;
            font-weight: 600;
            line-height: 1.5;
        }

        .up-first-login .ic {
            font-size: 15px;
            flex: none;
        }

        .up-error {
            margin: 18px 20px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .up-body {
            padding: 28px 34px 32px;
        }

        .up-field {
            margin-bottom: 20px;
        }

        .up-field label {
            display: block;
            font-size: 13px;
            font-weight: 800;
            color: #374151;
            margin-bottom: 8px;
        }

        .up-input-wrap {
            position: relative;
        }

        .up-input-wrap .lock-ic {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
            pointer-events: none;
        }

        .up-input-wrap input {
            width: 100%;
            box-sizing: border-box;
            border: 1.5px solid #e2ece7;
            border-radius: 10px;
            padding: 13px 44px 13px 40px;
            font-size: 14px;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .up-input-wrap input:focus {
            outline: none;
            border-color: #00875A;
            box-shadow: 0 0 0 4px rgba(0, 135, 90, .12);
        }

        .up-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 15px;
            color: #9ca3af;
            padding: 4px;
            transition: color .15s ease;
        }

        .up-toggle:hover {
            color: #00875A;
        }

        .up-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 6px;
        }

        /* --- Password strength meter --- */
        .up-strength {
            margin-top: 8px;
            display: none;
        }

        .up-strength.show {
            display: block;
        }

        .up-strength-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 4px;
        }

        .up-strength-bar span {
            height: 4px;
            flex: 1;
            border-radius: 4px;
            background: #e5e7eb;
            transition: background .2s ease;
        }

        .up-strength-label {
            font-size: 10.5px;
            font-weight: 700;
        }

        .up-match {
            font-size: 11px;
            margin-top: 6px;
            font-weight: 700;
            display: none;
        }

        .up-match.show {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .up-match.ok {
            color: #047857;
        }

        .up-match.no {
            color: #b91c1c;
        }

        .up-btn-primary {
            width: 100%;
            background: linear-gradient(120deg, #0b3d2e, #00875A);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px 18px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 6px 14px -4px rgba(11, 61, 46, .45);
            transition: transform .15s ease;
        }

        .up-btn-primary:hover {
            transform: translateY(-2px);
        }
    </style>

    <div class="up-wrap">
        <div class="up-card">
            <div class="up-head">
                <div class="icon">🔒</div>
                <div>
                    <h3>Ganti Password</h3>
                    <p class="sub">Gunakan password yang kuat dan mudah Anda ingat.</p>
                </div>
            </div>

            @if (auth()->user()->must_change_password)
                <div class="up-first-login">
                    <span class="ic">⚠️</span>
                    <span>Ini adalah login pertama Anda. Silakan buat password baru sebelum melanjutkan.</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="up-error">
                    <span>⛔</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="up-body">
                <form method="POST" action="{{ auth()->user()->role === 'petugas_ppm' ? route('petugas.ubah-password.submit') : route('ubah-password.submit') }}" id="up-form">
                    @csrf
                    <div class="up-field">
                        <label for="password_baru">Password Baru</label>
                        <div class="up-input-wrap">
                            <span class="lock-ic">🔑</span>
                            <input type="password" name="password_baru" id="password_baru" placeholder="Minimal 6 karakter"
                                required oninput="upCheckStrength(this.value); upCheckMatch();">
                            <button type="button" class="up-toggle" onclick="upToggle('password_baru', this)">👁</button>
                        </div>
                        <div class="up-strength" id="up-strength">
                            <div class="up-strength-bar">
                                <span id="up-bar-1"></span>
                                <span id="up-bar-2"></span>
                                <span id="up-bar-3"></span>
                            </div>
                            <div class="up-strength-label" id="up-strength-label"></div>
                        </div>
                    </div>

                    <div class="up-field">
                        <label for="password_baru_confirmation">Konfirmasi Password Baru</label>
                        <div class="up-input-wrap">
                            <span class="lock-ic">🔑</span>
                            <input type="password" name="password_baru_confirmation" id="password_baru_confirmation"
                                placeholder="Ulangi password baru" required oninput="upCheckMatch();">
                            <button type="button" class="up-toggle"
                                onclick="upToggle('password_baru_confirmation', this)">👁</button>
                        </div>
                        <div class="up-match" id="up-match"></div>
                    </div>

                    <button class="up-btn-primary" type="submit">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function upToggle(id, btn) {
            const input = document.getElementById(id);
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.textContent = showing ? '👁' : '🙈';
        }

        function upCheckStrength(value) {
            const wrap = document.getElementById('up-strength');
            const bars = [document.getElementById('up-bar-1'), document.getElementById('up-bar-2'), document.getElementById(
                'up-bar-3')];
            const label = document.getElementById('up-strength-label');

            if (!value) {
                wrap.classList.remove('show');
                return;
            }
            wrap.classList.add('show');

            let score = 0;
            if (value.length >= 6) score++;
            if (value.length >= 10 && /[0-9]/.test(value) && /[a-zA-Z]/.test(value)) score++;
            if (value.length >= 10 && /[^a-zA-Z0-9]/.test(value) && /[A-Z]/.test(value)) score++;

            const colors = ['#ef4444', '#f59e0b', '#10b981'];
            const labels = ['Lemah', 'Sedang', 'Kuat'];

            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[Math.max(score - 1, 0)] : '#e5e7eb';
            });
            label.textContent = score > 0 ? labels[score - 1] : 'Terlalu pendek';
            label.style.color = score > 0 ? colors[score - 1] : '#9ca3af';
        }

        function upCheckMatch() {
            const pass = document.getElementById('password_baru').value;
            const conf = document.getElementById('password_baru_confirmation').value;
            const el = document.getElementById('up-match');

            if (!conf) {
                el.classList.remove('show');
                return;
            }

            el.classList.add('show');
            if (pass === conf) {
                el.classList.remove('no');
                el.classList.add('ok');
                el.textContent = '✓ Password cocok';
            } else {
                el.classList.remove('ok');
                el.classList.add('no');
                el.textContent = '✗ Password belum sama';
            }
        }
    </script>
@endsection

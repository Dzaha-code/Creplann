<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ config('app.name', 'CrePlann') }} — Daftar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root{
            --paper:#FBF7F0;--paper-soft:#F4EEE3;--ink:#241F1A;--ink-soft:#6b6156;
            --coral:#E15B3F;--coral-ink:#B7431F;--sage:#7E9083;--line:#E4D9C8;--danger:#C4432E;--radius:20px;
            color-scheme:light;
        }
        *{box-sizing:border-box;}
        html,body{background:var(--paper) !important;}
        body{
            margin:0;min-height:100vh;color:var(--ink);font-family:'Plus Jakarta Sans',sans-serif;
            -webkit-font-smoothing:antialiased;
            background-image:radial-gradient(var(--line) 1px, transparent 1px);
            background-size:22px 22px;background-position:-11px -11px;
            display:flex;flex-direction:column;align-items:center;padding:48px 20px;
        }
        a{color:inherit;text-decoration:none;}
        .brand{display:flex;align-items:center;gap:10px;margin-bottom:34px;}
        .brand svg{width:32px;height:32px;flex-shrink:0;}
        .brand span{font-family:'Fraunces', serif;font-weight:700;font-size:1.3rem;letter-spacing:-0.02em;color:var(--ink);}
        .auth-card{
            width:100%;max-width:420px;background:#fff !important;border:1px solid var(--line);
            border-radius:var(--radius);box-shadow:0 24px 50px -30px rgba(36,31,26,0.28);
            padding:38px 34px 32px;color:var(--ink);
        }
        .auth-card h1{font-family:'Fraunces', serif;font-weight:600;font-size:1.7rem;letter-spacing:-0.01em;margin:0 0 6px;color:var(--ink);}
        .auth-card p.lede{color:var(--ink-soft);font-size:0.92rem;margin:0 0 26px;}
        .back-home{margin-top:26px;font-size:0.88rem;font-weight:600;color:var(--ink-soft);display:inline-flex;align-items:center;gap:6px;transition:color .2s ease;}
        .back-home:hover{color:var(--coral-ink);}
        .status-banner{background:rgba(126,144,131,0.14);border:1px solid rgba(126,144,131,0.35);color:#4d5c50;font-size:0.88rem;font-weight:600;padding:11px 16px;border-radius:12px;margin-bottom:22px;}
        .field{margin-bottom:20px;}
        .field label{display:block;font-size:0.85rem;font-weight:600;color:var(--ink);margin-bottom:7px;}
        .field input[type="text"],.field input[type="email"],.field input[type="password"]{
            width:100%;padding:11px 14px;border-radius:12px;border:1.5px solid var(--line);
            background:var(--paper-soft);color:var(--ink);font-family:inherit;font-size:0.96rem;
            transition:border-color .2s ease, background .2s ease;
        }
        .field input:focus{outline:none;border-color:var(--coral);background:#fff;}
        .field.has-error input{border-color:var(--danger);}
        .field-error{margin-top:6px;font-size:0.82rem;color:var(--danger);font-weight:500;}
        .remember-row{display:flex;align-items:center;gap:9px;margin:2px 0 26px;}
        .remember-row input[type="checkbox"]{width:17px;height:17px;accent-color:var(--coral);border-radius:4px;}
        .remember-row label{font-size:0.88rem;color:var(--ink-soft);font-weight:500;}
        .form-footer{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-top:6px;}
        .link-muted{font-size:0.85rem;font-weight:600;color:var(--ink-soft);text-decoration:underline;text-underline-offset:3px;transition:color .2s ease;}
        .link-muted:hover{color:var(--coral-ink);}
        .btn{display:inline-flex;align-items:center;justify-content:center;padding:12px 26px;border-radius:999px;font-weight:700;font-size:0.95rem;border:none;cursor:pointer;transition:transform .15s ease, background .2s ease;font-family:inherit;}
        .btn:hover{transform:translateY(-1px);}
        .btn-solid{background:var(--coral);color:#fff;box-shadow:0 10px 22px -10px rgba(225,91,63,0.6);}
        .btn-solid:hover{background:var(--coral-ink);}
        .switch-line{text-align:center;margin-top:26px;font-size:0.88rem;color:var(--ink-soft);}
    </style>
</head>
<body>

    <a href="{{ url('/') }}" class="brand">
        <svg viewBox="0 0 40 40" fill="none">
            <path d="M8 12c0-2.2 1.8-4 4-4h16c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4V12z" stroke="#241F1A" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M13 5.5v6M27 5.5v6" stroke="#E15B3F" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M13 21l4 4 9-9" stroke="#E15B3F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>{{ config('app.name', 'CrePlann') }}</span>
    </a>

    <div class="auth-card">
        <h1>Buat akun baru</h1>
        <p class="lede">Mulai rancang pekanmu bersama {{ config('app.name', 'CrePlann') }}, gratis.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="field @error('name') has-error @enderror">
                <label for="name">Nama</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}"
                       required autofocus autocomplete="name">
                @error('name')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field @error('email') has-error @enderror">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       required autocomplete="username">
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field @error('password') has-error @enderror">
                <label for="password">Kata Sandi</label>
                <input id="password" type="password" name="password"
                       required autocomplete="new-password">
                @error('password')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field @error('password_confirmation') has-error @enderror">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation"
                       required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-footer">
                <a class="link-muted" href="{{ route('login') }}">Sudah punya akun?</a>
                <button type="submit" class="btn btn-solid">Daftar</button>
            </div>
        </form>
    </div>

    <a href="{{ url('/') }}" class="back-home">← Kembali ke beranda</a>

</body>
</html>
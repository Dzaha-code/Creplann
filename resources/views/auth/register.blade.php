<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <title>{{ config('app.name', 'CrePlann') }} — Daftar</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="https://fonts.gstatic.com/s/bigshouldersdisplay/v24/fC1_PZJEZG-e9gHhdI4-NBbfd2ys3SjJCx1czNDuDJAM2w.woff2">
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css'])
    @endif
</head>
<body class="auth-page">

    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    <a href="{{ url('/') }}" class="brand">
        <svg viewBox="0 0 40 40" fill="none">
            <path d="M8 12c0-2.2 1.8-4 4-4h16c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4V12z" stroke="#241F1A" stroke-width="1.6" stroke-linejoin="round"/>
            <path d="M13 5.5v6M27 5.5v6" stroke="#E15B3F" stroke-width="1.6" stroke-linecap="round"/>
            <path d="M13 21l4 4 9-9" stroke="#E15B3F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>{{ config('app.name', 'CrePlann') }}</span>
    </a>

    <div class="auth-card" id="main-content" tabindex="-1">
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

        <x-google-auth-button />
    </div>

    <a href="{{ url('/') }}" class="back-home">← Kembali ke beranda</a>

</body>
</html>
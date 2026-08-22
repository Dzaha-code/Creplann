<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#21251f">
    <meta name="description" content="{{ $metaDescription ?? (config('app.name', 'CrePlann').' — Rencanakan mingguanmu, pantau progres, dan tutup pekan dengan tenang.') }}">
    <meta property="og:title" content="{{ $metaTitle ?? config('app.name', 'CrePlann') }}">
    <meta property="og:description" content="Planner mingguan: catatan, jadwal, dan to-do dalam satu ruang yang tenang.">
    <meta property="og:type" content="website">
    <title>{{ $metaTitle ?? config('app.name', 'CrePlann') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="https://fonts.gstatic.com/s/bigshouldersdisplay/v24/fC1_PZJEZG-e9gHhdI4-NBbfd2ys3SjJCx1czNDuDJAM2w.woff2">
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css"></noscript>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/welcome.css'])
    @endif

    @stack('head')
</head>
<body class="pub-body">

    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    {{-- ── Navbar publik (mirip landing page, bukan app capsule) ── --}}
    <header class="lp-nav pub-nav">
        <div class="wrap lp-nav-inner">
            <a href="{{ url('/') }}" class="lp-brand">
                {{ config('app.name', 'CrePlann') }}
            </a>

            <ul class="lp-nav-links" role="list">
                <li>
                    <a href="{{ url('/') }}#features"
                       class="{{ request()->is('/') ? '' : '' }}">Fitur</a>
                </li>
                <li>
                    <a href="{{ route('blog.index') }}"
                       class="{{ request()->routeIs('blog*') ? 'pub-nav-active' : '' }}">Blog</a>
                </li>
                <li>
                    <a href="{{ route('help.index') }}"
                       class="{{ request()->routeIs('help*') ? 'pub-nav-active' : '' }}">Bantuan</a>
                </li>
                <li>
                    <a href="{{ route('contact.index') }}"
                       class="{{ request()->routeIs('contact*') ? 'pub-nav-active' : '' }}">Kontak</a>
                </li>
            </ul>

            <div class="lp-nav-cta">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-coral">
                        <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-ghost-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-coral">
                        Coba gratis
                        <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- Page header slot (judul halaman) --}}
    @isset($header)
        <div class="pub-page-header wrap">
            @if (is_string($header))
                <h1>{{ $header }}</h1>
            @else
                {{ $header }}
            @endif
        </div>
    @endisset

    <main id="main-content" class="pub-main" tabindex="-1">
        {{ $slot }}
    </main>

    {{-- ── Footer publik ── --}}
    <footer class="lp-footer pub-footer">
        <div class="wrap">
            <div class="footer-inner">
                <div class="footer-brand">
                    <span class="footer-brand-dot" aria-hidden="true"></span>
                    {{ config('app.name', 'CrePlann') }}
                </div>
                <ul class="footer-links" role="list">
                    <li><a href="{{ url('/') }}#features">Fitur</a></li>
                    <li><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a href="{{ route('help.index') }}">Bantuan</a></li>
                    <li><a href="{{ route('contact.index') }}">Kontak</a></li>
                </ul>
                <div class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name', 'CrePlann') }}. Hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>

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

    {{-- Fonts: preconnect + preload woff2 kritis (Big Shoulders Display, subset latin) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="https://fonts.gstatic.com/s/bigshouldersdisplay/v24/fC1_PZJEZG-e9gHhdI4-NBbfd2ys3SjJCx1czNDuDJAM2w.woff2">
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Third-party: preconnect + tabler icons dimuat non-blocking --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://lh3.googleusercontent.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css"></noscript>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    @stack('head')
</head>
<body>

    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    @include('layouts.navigation')

    @isset($header)
        <div class="wrap page-header">
            @if (is_string($header))
                <h1>{{ $header }}</h1>
            @else
                {{ $header }}
            @endif
        </div>
    @endisset

    <main id="main-content" class="page-body" tabindex="-1">
        {{ $slot }}
    </main>

    @include('layouts.footer')

    @stack('scripts')
</body>
</html>

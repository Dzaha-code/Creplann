<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CrePlann') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root{
            --paper:#FBF7F0;
            --paper-soft:#F4EEE3;
            --ink:#241F1A;
            --ink-soft:#6b6156;
            --coral:#E15B3F;
            --coral-ink:#B7431F;
            --sage:#7E9083;
            --line:#E4D9C8;
            --danger:#C4432E;
            --radius:20px;
        }
        *{box-sizing:border-box;}
        body{
            margin:0;min-height:100vh;
            background:var(--paper);
            color:var(--ink);
            font-family:'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing:antialiased;
            background-image:radial-gradient(var(--line) 1px, transparent 1px);
            background-size:22px 22px;
            background-position:-11px -11px;
        }
        a{color:inherit;text-decoration:none;}
        .wrap{max-width:1080px;margin:0 auto;padding:0 28px;}

        /* Capsule navbar */
        .navbar-wrap{
            position:sticky;top:16px;z-index:50;
            display:flex;justify-content:center;
            padding:16px 20px 0;
        }
        .navbar-capsule{
            display:flex;align-items:center;gap:4px;
            background:#fff;border:1px solid var(--line);border-radius:999px;
            padding:8px;box-shadow:0 18px 40px -22px rgba(36,31,26,0.28);
            max-width:100%;overflow-x:auto;
        }
        .navbar-capsule::-webkit-scrollbar{display:none;}
        .nav-brand{
            display:flex;align-items:center;padding:8px 14px 8px 10px;
            border-right:1px solid var(--line);margin-right:6px;flex-shrink:0;
        }
        .nav-brand svg{width:26px;height:26px;}
        .nav-item{
            display:flex;align-items:center;gap:8px;padding:10px 18px;
            border-radius:999px;font-weight:600;font-size:0.9rem;color:var(--ink-soft);
            white-space:nowrap;transition:background .2s ease, color .2s ease;flex-shrink:0;
        }
        .nav-item svg{width:19px;height:19px;flex-shrink:0;}
        .nav-item:hover{background:var(--paper-soft);color:var(--ink);}
        .nav-item.active{background:var(--coral);color:#fff;box-shadow:0 8px 18px -8px rgba(225,91,63,.6);}
        .nav-logout-form{flex-shrink:0;margin-left:4px;}
        .nav-logout{
            padding:10px;border-radius:999px;border:none;background:transparent;cursor:pointer;
            color:var(--ink-soft);display:flex;align-items:center;justify-content:center;transition:background .2s ease,color .2s ease;
        }
        .nav-logout svg{width:19px;height:19px;}
        .nav-logout:hover{background:rgba(196,67,46,.1);color:var(--danger);}

        @media (max-width:640px){
            .nav-item .label{display:none;}
            .nav-item{padding:11px;}
        }

        /* Page header */
        .page-header{padding:36px 0 4px;}
        .page-header h1{
            font-family:'Fraunces', serif;font-weight:600;font-size:1.8rem;
            letter-spacing:-0.01em;margin:0;
        }

        main.page-body{padding:24px 0 80px;}

        /* Shared card */
        .card{
            background:#fff;border:1px solid var(--line);border-radius:var(--radius);
            padding:26px 24px;
        }
    </style>

    {{ $head ?? '' }}
</head>
<body>

    @include('layouts.navigation')

    @isset($header)
        <div class="wrap page-header">
            <h1>{{ $header }}</h1>
        </div>
    @endisset

    <main class="page-body">
        {{ $slot }}
    </main>

</body>
</html>
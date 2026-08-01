<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CrePlann') }} — Rencanakan Pekanmu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="antialiased">

    <header class="site">
        <div class="wrap row">
            <div class="brand">
                <svg viewBox="0 0 40 40" fill="none">
                    <path d="M8 12c0-2.2 1.8-4 4-4h16c2.2 0 4 1.8 4 4v18c0 2.2-1.8 4-4 4H12c-2.2 0-4-1.8-4-4V12z" stroke="#241F1A" stroke-width="1.6" stroke-linejoin="round"/>
                    <path d="M13 5.5v6M27 5.5v6" stroke="#E15B3F" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M13 21l4 4 9-9" stroke="#E15B3F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>{{ config('app.name', 'CrePlann') }}</span>
            </div>

            @if (Route::has('login'))
            <nav class="auth">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-solid">Ke Dashboard →</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-solid">Coba Gratis</a>
                    @endif
                @endauth
            </nav>
            @endif
        </div>
    </header>

    <main class="hero">
        <div class="wrap">
            <span class="eyebrow">✦ Perencanaan mingguan yang tenang</span>

            <h1 class="headline">
                Atur pekanmu,<br>
                rancang <span class="accent">masa depan
                    <svg viewBox="0 0 200 20" preserveAspectRatio="none">
                        <path d="M2 14C40 4, 90 4, 130 12S190 18, 198 8" stroke="var(--coral)" stroke-width="4" stroke-linecap="round" fill="none"/>
                    </svg>
                </span> yang jernih.
            </h1>

            <p class="lede">
                Tinggalkan catatan yang berserakan. CrePlann membantumu menyusun prioritas,
                memantau progres, dan menutup pekan dengan tenang — bukan dengan stres.
            </p>

            <div class="cta-row">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-solid">Buka Planner Saya</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-solid">Mulai Sekarang — Gratis</a>
                    <a href="{{ route('login') }}" class="btn btn-ghost">Masuk Akun</a>
                @endauth
            </div>

            <div class="week-strip">
                @php
                    $days = ['S','S','R','K','J','S','M'];
                @endphp
                @foreach ($days as $i => $d)
                    <div class="day {{ $i < 3 ? 'done' : '' }} {{ $i === 3 ? 'today' : '' }}">
                        <div class="dot">
                            {{ $d }}
                            @if ($i === 3)
                                <svg class="scribble" viewBox="0 0 52 52" fill="none">
                                    <path d="M26 4C13 4 5 13 5 26s8 22 21 22 21-9 21-22c0-6-2-11-5-15" stroke="var(--coral)" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    <section class="features">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Cara kerjanya</div>
                <h2 class="section-title">Tiga hal yang benar-benar kamu butuhkan.</h2>
            </div>

            <div class="cards">
                <div class="card">
                    <svg class="icon" viewBox="0 0 40 40" fill="none">
                        <rect x="5" y="8" width="30" height="26" rx="4" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M5 16h30M13 4v6M27 4v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                        <path d="M11 22h4M11 27h9M20 22h9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    </svg>
                    <h3>Weekly Grid</h3>
                    <p>Lihat seluruh agenda dari Senin sampai Minggu dalam satu tampilan yang jernih, tanpa perlu berpindah layar.</p>
                </div>

                <div class="card">
                    <svg class="icon" viewBox="0 0 40 40" fill="none">
                        <circle cx="20" cy="20" r="15" stroke="currentColor" stroke-width="1.8"/>
                        <circle cx="20" cy="20" r="8.5" stroke="currentColor" stroke-width="1.6"/>
                        <circle cx="20" cy="20" r="2.4" fill="currentColor"/>
                    </svg>
                    <h3>Skala Prioritas</h3>
                    <p>Fokus pada tugas yang paling penting lebih dulu, tanpa kewalahan oleh daftar to-do yang menumpuk.</p>
                </div>

                <div class="card">
                    <svg class="icon" viewBox="0 0 40 40" fill="none">
                        <path d="M8 32h24M13 32V20M20 32V12M27 32V22" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        <path d="M11 15l6-6 5 4 8-8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3>Pantau Progress</h3>
                    <p>Evaluasi pencapaian mingguanmu dan lihat sendiri efisiensimu bertumbuh dari waktu ke waktu.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="site">
        <div class="wrap footer-row">
            <div class="quick-links">
                <a href="#">Blog ↗</a>
                <a href="#">Fitur ↗</a>
                <a href="#">Bantuan ↗</a>
                <a href="#">Kontak ↗</a>
            </div>
            <div class="copyright">&copy; {{ date('Y') }} {{ config('app.name', 'CrePlann') }}. Seluruh hak cipta dilindungi.</div>
        </div>
    </footer>

    <script>
        const cards = document.querySelectorAll('.card');
        const io = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('in'), i * 90);
                    io.unobserve(e.target);
                }
            });
        }, { threshold: 0.2 });
        cards.forEach(c => io.observe(c));
    </script>

</body>
</html>
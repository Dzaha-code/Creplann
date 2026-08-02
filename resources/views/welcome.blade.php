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

    <style>
        .site { padding: 24px 0 0; }
        .row { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .brand { display: flex; align-items: center; gap: 14px; font-weight: 700; font-size: 1rem; letter-spacing: -0.01em; }
        .brand svg { width: 36px; height: 36px; }
        .auth { display: flex; gap: 12px; align-items: center; }
        .site header {
            padding: 24px 0 14px;
            position: sticky;
            top: 0;
            z-index: 30;
            background: rgba(251, 247, 240, 0.98);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(228, 217, 200, 0.55);
        }

        .hero { padding: 64px 0 56px; }
        .hero-grid { display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 48px; align-items: center; }
        .copy { max-width: 620px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 10px; padding: 10px 16px; border-radius: 999px; background: rgba(225, 91, 63, 0.1); color: var(--coral); font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase; font-size: 0.9rem; margin-bottom: 24px; }
        .headline { margin: 0; font-family: 'Fraunces', serif; font-weight: 700; font-size: clamp(2.9rem, 5vw, 4.8rem); line-height: 0.98; max-width: 14ch; }
        .headline .accent { position: relative; color: var(--coral); display: inline-flex; align-items: flex-end; }
        .headline .accent svg { position: absolute; left: 0; right: 0; bottom: 0.05em; width: 110%; height: 0.9em; transform: translateY(14%); }
        .lede { margin: 26px 0 32px; max-width: 46rem; color: var(--ink-soft); font-size: 1rem; line-height: 1.8; }
        .cta-row { display: flex; flex-wrap: wrap; gap: 14px; margin-bottom: 46px; }
        .hero-panel { background: #fff; border: 1px solid var(--line); border-radius: 30px; padding: 36px; box-shadow: 0 34px 80px -40px rgba(36, 31, 26, 0.18); }
        .hero-panel h3 { margin: 0 0 18px; font-size: 1.15rem; letter-spacing: -0.02em; }
        .hero-panel p { margin: 0 0 22px; color: var(--ink-soft); line-height: 1.85; }
        .preview-row { display: grid; gap: 14px; }
        .preview-card { padding: 18px 20px; background: var(--paper); border: 1px solid var(--line); border-radius: 20px; }
        .preview-card strong { display: block; margin-bottom: 10px; font-size: 0.98rem; }
        .preview-card p { margin: 0; font-size: 0.92rem; color: var(--ink-soft); line-height: 1.7; }
        .week-strip { display: flex; align-items: center; gap: 10px; overflow-x: auto; padding: 18px 18px 16px; border-radius: 999px; border: 1px solid var(--line); background: #fff; box-shadow: 0 26px 50px -30px rgba(36, 31, 26, 0.16); }
        .day { min-width: 42px; min-height: 42px; border-radius: 999px; display: grid; place-items: center; font-weight: 700; color: var(--ink); background: var(--paper); position: relative; }
        .day.done { background: var(--sage); color: #fff; }
        .day.today { border: 2px solid var(--coral); background: #fff; }
        .day.today .scribble { position: absolute; inset: 0; width: 100%; height: 100%; transform: translate(-8%, -8%); }
        .features { padding: 0 0 72px; }
        .section-head { display: flex; justify-content: space-between; align-items: flex-end; gap: 24px; margin-bottom: 34px; flex-wrap: wrap; }
        .kicker { font-size: 0.82rem; letter-spacing: 0.22em; text-transform: uppercase; color: var(--coral); font-weight: 700; }
        .section-title { margin: 0; font-size: clamp(2rem, 3vw, 2.55rem); line-height: 1.08; max-width: 54rem; }
        .cards { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 22px; }
        .card { padding: 28px 26px; min-height: 260px; display: flex; flex-direction: column; gap: 18px; }
        .card h3 { margin: 0; font-size: 1.12rem; }
        .card p { margin: 0; color: var(--ink-soft); line-height: 1.75; font-size: 0.98rem; }
        .icon { width: 44px; height: 44px; color: var(--coral); }
        .footer-row { display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap; padding: 28px 0 12px; color: var(--ink-soft); font-size: 0.95rem; }
        .quick-links { display: flex; flex-wrap: wrap; gap: 18px; }
        .quick-links a { transition: color 0.2s ease; }
        .quick-links a:hover { color: var(--coral); }

        @media (max-width: 960px) {
            .hero-grid { grid-template-columns: 1fr; }
            .cards { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 720px) {
            .site header { padding: 18px 0 12px; }
            .row { flex-direction: column; align-items: flex-start; }
            .auth { width: 100%; justify-content: flex-start; }
            .headline { font-size: clamp(2.3rem, 7vw, 3.4rem); max-width: 100%; }
            .lede { margin-top: 20px; }
            .cards { grid-template-columns: 1fr; }
            .cta-row { flex-direction: column; width: 100%; }
            .btn { width: 100%; justify-content: center; }
            .footer-row { flex-direction: column; align-items: flex-start; }
        }

        @media (max-width: 520px) {
            .wrap { padding: 0 18px; }
            .hero { padding-top: 48px; padding-bottom: 42px; }
            .hero-panel { padding: 24px; }
            .brand { font-size: 0.98rem; }
            .day { min-width: 38px; min-height: 38px; }
        }
    </style>
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
        <div class="wrap hero-grid">
            <div class="copy">
                <span class="eyebrow">✦ Perencanaan mingguan yang tenang</span>

                <h1 class="headline">
                    Atur pekanmu,
                    rancang <span class="accent">masa depan</span> yang jernih.
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
                            {{ $d }}
                            @if ($i === 3)
                                <svg class="scribble" viewBox="0 0 52 52" fill="none">
                                    <path d="M26 4C13 4 5 13 5 26s8 22 21 22 21-9 21-22c0-6-2-11-5-15" stroke="var(--coral)" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="hero-panel">
                <h3>Rapi dan siap digunakan di Android & tablet</h3>
                <p>Dashboard dibuat untuk tampilan layar lebar dan ponsel, jadi kamu bisa cek jadwal, todo, dan notes tanpa ribet.</p>

                <div class="preview-row">
                    <div class="preview-card">
                        <strong>Jadwal Mingguan</strong>
                        <p>Kalender dengan highlight aktivitas hari ini dan status selesai di tampilan yang mudah dibaca.</p>
                    </div>
                    <div class="preview-card">
                        <strong>Todo Cepat</strong>
                        <p>Tambahkan tugas baru dengan cepat dan tandai selesai tanpa berpindah halaman.</p>
                    </div>
                </div>
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
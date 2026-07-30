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
        :root{
            --paper:#FBF7F0;
            --paper-soft:#F4EEE3;
            --ink:#241F1A;
            --ink-soft:#6b6156;
            --coral:#E15B3F;
            --coral-ink:#B7431F;
            --gold:#E3A93B;
            --sage:#7E9083;
            --line:#E4D9C8;
            --radius:20px;
        }
        *{box-sizing:border-box;}
        html{scroll-behavior:smooth;}
        body{
            margin:0;
            background:var(--paper);
            color:var(--ink);
            font-family:'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing:antialiased;
            background-image:
                radial-gradient(var(--line) 1px, transparent 1px);
            background-size: 22px 22px;
            background-position: -11px -11px;
        }
        .wrap{max-width:1080px;margin:0 auto;padding:0 28px;}
        a{color:inherit;text-decoration:none;}

        /* Header */
        header.site{padding:28px 0;}
        header.site .row{display:flex;align-items:center;justify-content:space-between;gap:16px;}
        .brand{display:flex;align-items:center;gap:10px;}
        .brand svg{width:34px;height:34px;flex-shrink:0;}
        .brand span{font-family:'Fraunces', serif;font-weight:700;font-size:1.4rem;letter-spacing:-0.02em;}
        nav.auth{display:flex;align-items:center;gap:10px;}
        .btn{
            display:inline-flex;align-items:center;justify-content:center;
            padding:10px 20px;border-radius:999px;font-weight:600;font-size:0.92rem;
            border:1.5px solid transparent;transition:transform .15s ease, background .2s ease, border-color .2s ease;
            white-space:nowrap;
        }
        .btn:hover{transform:translateY(-1px);}
        .btn-ghost{color:var(--ink);border-color:var(--line);}
        .btn-ghost:hover{border-color:var(--ink);}
        .btn-solid{background:var(--coral);color:#fff;box-shadow:0 6px 16px -6px rgba(225,91,63,0.55);}
        .btn-solid:hover{background:var(--coral-ink);}

        /* Hero */
        main.hero{padding:40px 0 20px;}
        .eyebrow{
            display:inline-flex;align-items:center;gap:8px;
            font-size:0.85rem;font-weight:600;color:var(--coral-ink);
            background:rgba(225,91,63,0.09);border:1px solid rgba(225,91,63,0.22);
            padding:7px 16px;border-radius:999px;margin-bottom:26px;
        }
        h1.headline{
            font-family:'Fraunces', serif;
            font-weight:600;
            font-size:clamp(2.4rem, 6vw, 4.1rem);
            line-height:1.05;
            letter-spacing:-0.02em;
            margin:0 0 24px;
            max-width:14ch;
        }
        h1.headline .accent{
            font-style:italic;
            color:var(--coral-ink);
            position:relative;
            white-space:nowrap;
        }
        h1.headline .accent svg{
            position:absolute;left:-2%;bottom:-0.14em;width:104%;height:0.3em;
        }
        h1.headline .accent svg path{
            stroke-dasharray:340;stroke-dashoffset:340;
            animation:draw 1s 0.5s ease-out forwards;
        }
        @keyframes draw{to{stroke-dashoffset:0;}}

        p.lede{font-size:1.15rem;line-height:1.6;color:var(--ink-soft);max-width:46ch;margin:0 0 34px;}
        .cta-row{display:flex;flex-wrap:wrap;gap:14px;margin-bottom:56px;}
        .cta-row .btn{padding:14px 28px;font-size:1rem;}

        /* Week strip — signature element */
        .week-strip{
            display:flex;gap:10px;align-items:flex-end;
            padding:22px 24px 20px;
            background:#fff;border:1px solid var(--line);border-radius:var(--radius);
            box-shadow:0 18px 40px -24px rgba(36,31,26,0.25);
            width:fit-content;
        }
        .day{
            display:flex;flex-direction:column;align-items:center;gap:8px;
            font-size:0.78rem;color:var(--ink-soft);font-weight:600;
        }
        .day .dot{
            position:relative;width:38px;height:38px;border-radius:50%;
            border:1.5px solid var(--line);display:flex;align-items:center;justify-content:center;
            font-family:'Fraunces', serif;font-weight:600;font-size:0.95rem;color:var(--ink);
            background:var(--paper);
        }
        .day.done .dot{background:var(--sage);border-color:var(--sage);color:#fff;}
        .day.today .dot{border-color:transparent;}
        .day.today svg.scribble{position:absolute;inset:-7px;width:52px;height:52px;pointer-events:none;}
        .day.today svg.scribble path{
            stroke-dasharray:150;stroke-dashoffset:150;
            animation:draw 0.9s 1s ease-out forwards;
        }
        .day.today .dot{position:relative;}

        /* Section title */
        .section-head{max-width:640px;margin:0 0 44px;}
        .kicker{font-size:0.82rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--sage);margin-bottom:10px;}
        h2.section-title{font-family:'Fraunces', serif;font-weight:600;font-size:clamp(1.7rem,3.4vw,2.3rem);letter-spacing:-0.01em;margin:0;}

        /* Features */
        section.features{padding:70px 0;}
        .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
        .card{
            background:#fff;border:1px solid var(--line);border-radius:var(--radius);
            padding:30px 26px;opacity:0;transform:translateY(16px);
            transition:opacity .6s ease, transform .6s ease;
        }
        .card.in{opacity:1;transform:translateY(0);}
        .card svg.icon{width:40px;height:40px;color:var(--coral-ink);margin-bottom:18px;}
        .card h3{font-family:'Fraunces', serif;font-weight:600;font-size:1.2rem;margin:0 0 8px;}
        .card p{margin:0;font-size:0.95rem;line-height:1.6;color:var(--ink-soft);}

        /* Footer */
        footer.site{border-top:1px solid var(--line);padding:36px 0;margin-top:40px;}
        .footer-row{display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:20px;}
        .quick-links{display:flex;gap:22px;flex-wrap:wrap;}
        .quick-links a{font-size:0.9rem;font-weight:600;color:var(--ink-soft);display:inline-flex;align-items:center;gap:5px;transition:color .2s ease;}
        .quick-links a:hover{color:var(--coral-ink);}
        .copyright{font-size:0.85rem;color:var(--ink-soft);}

        @media (max-width:760px){
            .cards{grid-template-columns:1fr;}
            .week-strip{width:100%;overflow-x:auto;}
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
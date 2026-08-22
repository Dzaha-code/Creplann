<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#21251f">
    <meta property="og:title" content="{{ config('app.name', 'CrePlann') }} — Perencanaan yang benar-benar bekerja">
    <meta property="og:description" content="CrePlann menyatukan catatan, jadwal, dan to-do dalam satu ruang yang tenang. Gratis selamanya.">
    <meta property="og:type" content="website">
    <title>{{ config('app.name', 'CrePlann') }} — Perencanaan yang benar-benar bekerja</title>

    {{-- Fonts: preconnect + preload woff2 kritis (Big Shoulders Display, subset latin) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="font" type="font/woff2" crossorigin
          href="https://fonts.gstatic.com/s/bigshouldersdisplay/v24/fC1_PZJEZG-e9gHhdI4-NBbfd2ys3SjJCx1czNDuDJAM2w.woff2">
    <link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Display:wght@600;700;800&family=IBM+Plex+Mono:wght@500;600;700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tabler icons: preconnect + non-blocking --}}
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.11.0/dist/tabler-icons.min.css"></noscript>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/welcome.css', 'resources/js/welcome.js'])
    @endif

</head>
<body>

    <a href="#main-content" class="skip-link">Lewati ke konten utama</a>

    {{-- ── Nav ── --}}
    <header class="lp-nav">
        <div class="wrap lp-nav-inner">
            <a href="/" class="lp-brand">
                {{-- <div class="lp-brand-icon" aria-hidden="true">
                    <i class="ti ti-notebook"></i>
                </div> --}}
                {{ config('app.name', 'CrePlann') }}
            </a>

            <ul class="lp-nav-links" role="list">
                <li><a href="#features">Fitur</a></li>
                <li><a href="#compare">Perbandingan</a></li>
                <li><a href="#cara-kerja">Cara kerja</a></li>
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

    <main id="main-content" tabindex="-1">

        {{-- ── Hero ── --}}
        <section class="lp-hero" aria-labelledby="hero-heading">
            <div class="wrap anim-hero">
                <div class="hero-eyebrow">
                    <i class="ti ti-sparkles" aria-hidden="true"></i>
                    Perencanaan mingguan yang benar-benar bekerja
                </div>

                <h1 class="hero-h1" id="hero-heading">
                    Berhenti mengejar hari.<br>
                    Mulai <em>memimpin</em> mingguanmu.
                </h1>

                <p class="hero-lede">
                    CrePlann menyatukan catatan, jadwal, dan to-do dalam satu ruang yang tenang. Tidak ada notifikasi berisik. Tidak ada fitur yang kamu tidak butuhkan.
                </p>

                <div class="hero-btns">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-coral-lg">
                            <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                            Buka planner saya
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-coral-lg">
                            <i class="ti ti-rocket" aria-hidden="true"></i>
                            Mulai gratis sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn-outline-lg">
                            Masuk akun
                        </a>
                    @endauth
                </div>

                <div class="hero-trust">
                    <i class="ti ti-shield-check" aria-hidden="true"></i>
                    Gratis selamanya &nbsp;·&nbsp; Tanpa kartu kredit &nbsp;·&nbsp; Data tersimpan aman
                </div>
            </div>
        </section>

        {{-- ── Dashboard Preview ── --}}
        <section class="preview-wrap" aria-label="Pratinjau dashboard">
            <div class="wrap">
                <div class="preview-frame">
                    <div class="preview-bar">
                        <span class="preview-dot" aria-hidden="true"></span>
                        <span class="preview-dot" aria-hidden="true"></span>
                        <span class="preview-dot" aria-hidden="true"></span>
                        <div class="preview-url">creplann.app/dashboard</div>
                    </div>

                    <div class="preview-bento" aria-hidden="true">
                        {{-- Stat: Notes --}}
                        <div class="pv-tile">
                            <div class="pv-label">Catatan</div>
                            <div class="pv-num" id="pvNotes">0</div>
                            <div class="pv-bar-wrap">
                                <div class="pv-bar" style="width:72%;background:#c96442"></div>
                            </div>
                        </div>

                        {{-- Stat: Todo --}}
                        <div class="pv-tile">
                            <div class="pv-label">Todo</div>
                            <div class="pv-num" id="pvTodo">0</div>
                            <div class="pv-bar-wrap">
                                <div class="pv-bar" style="width:90%;background:#b87c2e"></div>
                            </div>
                        </div>

                        {{-- Progress ring --}}
                        <div class="pv-tile pv-tile-tall">
                            <div class="pv-label">Penyelesaian</div>
                            <div class="pv-ring-area">
                                <div class="pv-ring">
                                    <svg viewBox="0 0 80 80" width="80" height="80">
                                        <circle cx="40" cy="40" r="32" fill="none" stroke="#ede9e3" stroke-width="5"/>
                                        <circle id="pvRingFg" cx="40" cy="40" r="32" fill="none" stroke="#c96442" stroke-width="5"
                                            stroke-linecap="round"
                                            stroke-dasharray="201.06"
                                            stroke-dashoffset="201.06"/>
                                    </svg>
                                    <div class="pv-ring-ctr">
                                        <span class="pv-ring-n" id="pvPct">0%</span>
                                        <span class="pv-ring-s">selesai</span>
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex;gap:8px;flex-direction:column">
                                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#4f554b">
                                    <span style="width:7px;height:7px;border-radius:50%;background:#8f3f1e;flex-shrink:0"></span>
                                    <b id="pvDone" style="color:#20241f">0</b>&nbsp;selesai
                                </div>
                                <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#4f554b">
                                    <span style="width:7px;height:7px;border-radius:50%;background:#e7e5e4;flex-shrink:0"></span>
                                    <b id="pvPend" style="color:#20241f">0</b>&nbsp;pending
                                </div>
                            </div>
                        </div>

                        {{-- Schedule --}}
                        <div class="pv-tile pv-tile-wide">
                            <div class="pv-label">Jadwal hari ini</div>
                            <div class="pv-sch-item">
                                <span class="pv-sch-time">07:30</span>
                                <span class="pv-sch-title">Review laporan mingguan</span>
                                <span class="pv-sch-badge">high</span>
                            </div>
                            <div class="pv-sch-item" style="border-left-color:#5a7a5c">
                                <span class="pv-sch-time">10:00</span>
                                <span class="pv-sch-title">Meeting tim produk</span>
                                <span class="pv-sch-badge" style="background:rgba(90,122,92,.12);color:#3d5e3e">medium</span>
                            </div>

                            <div class="pv-typing">
                                <span id="pvTypingText"></span><span class="pv-cursor"></span>
                            </div>
                        </div>

                        {{-- Quick actions --}}
                        <div class="pv-tile">
                            <div class="pv-label" style="margin-bottom:10px">Aksi cepat</div>
                            <div class="pv-qa-grid">
                                <div class="pv-qa"><i class="ti ti-notebook" aria-hidden="true"></i>Catatan</div>
                                <div class="pv-qa"><i class="ti ti-calendar-plus" aria-hidden="true"></i>Jadwal</div>
                                <div class="pv-qa"><i class="ti ti-circle-plus" aria-hidden="true"></i>Todo</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Stats ── --}}
        <section class="lp-stats" aria-label="Statistik pengguna">
            <div class="wrap">
                <div class="lp-stats-inner">
                    <div class="stat-item">
                        <div class="stat-item-num">1<span class="accent">k</span></div>
                        <div class="stat-item-lbl">Pengguna aktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-num">98<span class="accent">%</span></div>
                        <div class="stat-item-lbl">Kepuasan pengguna</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-num">3<span class="accent">×</span></div>
                        <div class="stat-item-lbl">Lebih produktif</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-item-num">0<span class="accent">Rp</span></div>
                        <div class="stat-item-lbl">Biaya mulai</div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Features ── --}}
        <section class="lp-features" id="features" aria-labelledby="features-heading">
            <div class="wrap">
                <div class="section-eyebrow">
                    <i class="ti ti-layout-grid" aria-hidden="true"></i>
                    Apa yang kamu dapat
                </div>
                <h2 class="section-title" id="features-heading">
                    Satu tempat.<br>Semua yang kamu butuhkan.
                </h2>
                <p class="section-sub">
                    Tidak seperti aplikasi lain yang memaksamu beli add-on, semua fitur CrePlann tersedia dari hari pertama.
                </p>

                <div class="features-grid">
                    <div class="feat-card">
                        <div class="feat-icon coral">
                            <i class="ti ti-notebook" aria-hidden="true"></i>
                        </div>
                        <h3>Catatan terorganisir</h3>
                        <p>Tulis ide, materi pelajaran, atau apapun — lalu filter berdasarkan kategori. Tidak ada catatan yang hilang lagi.</p>
                        <span class="feat-tag">Notes</span>
                    </div>

                    <div class="feat-card">
                        <div class="feat-icon sage">
                            <i class="ti ti-calendar-event" aria-hidden="true"></i>
                        </div>
                        <h3>Jadwal yang jelas</h3>
                        <p>Lihat seluruh agendamu dalam satu grid mingguan. Atur prioritas, tambahkan warna, dan selalu tahu apa yang menanti.</p>
                        <span class="feat-tag">Schedule</span>
                    </div>

                    <div class="feat-card">
                        <div class="feat-icon amber">
                            <i class="ti ti-checkbox" aria-hidden="true"></i>
                        </div>
                        <h3>To-do yang selesai</h3>
                        <p>Buat daftar tugas dengan tenggat waktu, centang satu per satu, dan pantau tingkat penyelesaianmu setiap minggu.</p>
                        <span class="feat-tag">Todo</span>
                    </div>

                    <div class="feat-card">
                        <div class="feat-icon coral">
                            <i class="ti ti-chart-donut" aria-hidden="true"></i>
                        </div>
                        <h3>Dashboard progress</h3>
                        <p>Satu halaman yang menampilkan semua metrik penting — catatan, jadwal, todo — tanpa perlu berpindah-pindah layar.</p>
                        <span class="feat-tag">Dashboard</span>
                    </div>

                    <div class="feat-card">
                        <div class="feat-icon sage">
                            <i class="ti ti-tag" aria-hidden="true"></i>
                        </div>
                        <h3>Kategori kustom</h3>
                        <p>Buat kategori sendiri sesuai konteksmu — sekolah, pekerjaan, pribadi — dan filter konten dalam hitungan detik.</p>
                        <span class="feat-tag">Organisasi</span>
                    </div>

                    <div class="feat-card">
                        <div class="feat-icon amber">
                            <i class="ti ti-device-mobile" aria-hidden="true"></i>
                        </div>
                        <h3>Akses dari mana saja</h3>
                        <p>Tampilan yang menyesuaikan ukuran layar apapun — dari desktop kantor hingga ponsel saat dalam perjalanan.</p>
                        <span class="feat-tag">Responsif</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Comparison ── --}}
        <section class="lp-compare" id="compare" aria-labelledby="compare-heading">
            <div class="wrap">
                <div class="compare-head">
                    <div class="section-eyebrow">
                        <i class="ti ti-scale" aria-hidden="true"></i>
                        Perbandingan
                    </div>
                    <h2 class="section-title" id="compare-heading">
                        Mengapa CrePlann berbeda
                    </h2>
                    <p style="font-size:15px;color:#78716c;max-width:440px;margin:0 auto">
                        Banyak aplikasi perencanaan yang memberimu banyak fitur tapi sedikit kejelasan. CrePlann berbeda.
                    </p>
                </div>

                <div class="compare-table" role="table" aria-label="Perbandingan fitur">
                    <div class="compare-header" role="row">
                        <div class="compare-col-head" role="columnheader">Fitur</div>
                        <div class="compare-col-head featured" role="columnheader">
                            <span>CrePlann</span>
                            <span class="featured-badge">Pilihan kamu</span>
                        </div>
                        <div class="compare-col-head" role="columnheader">Aplikasi lain</div>
                    </div>

                    @php
                    $rows = [
                        ['Gratis tanpa batas waktu', 'yes', 'no'],
                        ['Catatan + Jadwal + Todo dalam satu app', 'yes', 'partial'],
                        ['Tampilan dashboard terpadu', 'yes', 'partial'],
                        ['Kategori kustom tanpa biaya tambahan', 'yes', 'no'],
                        ['Tanpa iklan', 'yes', 'no'],
                        ['Responsif di semua perangkat', 'yes', 'yes'],
                        ['Filter dan preview konten cepat', 'yes', 'partial'],
                        ['Notifikasi yang bisa dimatikan', 'yes', 'partial'],
                    ];
                    @endphp

                    @foreach ($rows as $row)
                    <div class="compare-row" role="row">
                        <div class="compare-cell" role="cell">{{ $row[0] }}</div>
                        <div class="compare-cell featured-col" role="cell">
                            @if ($row[1] === 'yes')
                                <i class="ti ti-circle-check ic-yes" aria-label="Ya"></i> Ya
                            @elseif ($row[1] === 'no')
                                <i class="ti ti-circle-x ic-no" aria-label="Tidak"></i> Tidak
                            @else
                                <i class="ti ti-circle-half ic-partial" aria-label="Sebagian"></i> Terbatas
                            @endif
                        </div>
                        <div class="compare-cell" role="cell">
                            @if ($row[2] === 'yes')
                                <i class="ti ti-circle-check ic-yes" aria-label="Ya"></i> Ya
                            @elseif ($row[2] === 'no')
                                <i class="ti ti-circle-x ic-no" aria-label="Tidak"></i> Tidak
                            @else
                                <i class="ti ti-circle-half ic-partial" aria-label="Sebagian"></i> Terbatas
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── How it works ── --}}
        <section class="lp-how" id="cara-kerja" aria-labelledby="how-heading">
            <div class="wrap">
                <div class="how-head">
                    <div class="section-eyebrow">
                        <i class="ti ti-route" aria-hidden="true"></i>
                        Cara kerja
                    </div>
                    <h2 class="section-title" id="how-heading">Mulai dalam 3 langkah</h2>
                    <p style="font-size:15px;color:#78716c;max-width:400px;margin:0 auto">
                        Tidak perlu konfigurasi panjang. Buka, isi, selesai.
                    </p>
                </div>

                <div class="how-steps">
                    <div class="how-step">
                        <div class="how-step-num" aria-hidden="true">1</div>
                        <h3>Daftar gratis</h3>
                        <p>Buat akun dalam 30 detik. Tidak perlu kartu kredit, tidak ada trial period yang berakhir mengejutkan.</p>
                    </div>
                    <div class="how-step">
                        <div class="how-step-num" aria-hidden="true">2</div>
                        <h3>Isi planner-mu</h3>
                        <p>Tambahkan catatan, jadwal minggu ini, dan to-do yang tertunda. Semuanya dalam satu layar yang sama.</p>
                    </div>
                    <div class="how-step">
                        <div class="how-step-num" aria-hidden="true">3</div>
                        <h3>Pantau dan evaluasi</h3>
                        <p>Dashboard menghitung progresmu otomatis. Tutup minggu dengan data nyata, bukan perasaan samar.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Testimonials ── --}}
        <section class="lp-testi" aria-labelledby="testi-heading">
            <div class="wrap">
                <div class="testi-head">
                    <div class="section-eyebrow">
                        <i class="ti ti-message-circle" aria-hidden="true"></i>
                        Kata mereka
                    </div>
                    <h2 class="section-title" id="testi-heading">
                        Pengguna yang pekan ini lebih tenang
                    </h2>
                </div>

                <div class="testi-grid">
                    <div class="testi-card">
                        <div class="testi-stars" aria-label="5 dari 5 bintang">
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                        </div>
                        <p class="testi-quote">Akhirnya ada aplikasi yang tidak memaksaku membayar untuk fitur dasar. Semua yang saya butuhkan ada di sini, gratis, dan tampilan-nya tidak bikin sakit mata.</p>
                        <div class="testi-author">
                            <div class="testi-avatar" aria-hidden="true">RW</div>
                            <div>
                                <div class="testi-name">Rara Widyasari</div>
                                <div class="testi-role">Mahasiswa Teknik, Bandung</div>
                            </div>
                        </div>
                    </div>

                    <div class="testi-card">
                        <div class="testi-stars" aria-label="5 dari 5 bintang">
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                        </div>
                        <p class="testi-quote">Saya sudah mencoba Notion, Todoist, dan TickTick. Semuanya terlalu kompleks. CrePlann langsung bisa dipakai tanpa tutorial panjang.</p>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:rgba(90,122,92,.15);color:#3d5e3e" aria-hidden="true">BP</div>
                            <div>
                                <div class="testi-name">Bagas Prasetyo</div>
                                <div class="testi-role">Freelance Designer, Jakarta</div>
                            </div>
                        </div>
                    </div>

                    <div class="testi-card">
                        <div class="testi-stars" aria-label="5 dari 5 bintang">
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                            <i class="ti ti-star-filled" aria-hidden="true"></i>
                        </div>
                        <p class="testi-quote">Dashboard progress-nya yang bikin saya tetap di sini. Melihat angka todo yang selesai setiap minggu itu motivasi tersendiri yang tidak saya sangka-sangka.</p>
                        <div class="testi-author">
                            <div class="testi-avatar" style="background:rgba(184,124,46,.12);color:#8a5e1e" aria-hidden="true">NK</div>
                            <div>
                                <div class="testi-name">Nisa Kusuma</div>
                                <div class="testi-role">Content Strategist, Yogyakarta</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── CTA Bottom ── --}}
        <section class="lp-cta" aria-labelledby="cta-heading">
            <div class="wrap">
                <div class="cta-box">
                    <h2 id="cta-heading">
                        Minggumu yang paling<br>
                        <em style="font-style:italic;color:#f0997b">produktif</em> dimulai hari ini.
                    </h2>
                    <p>Bergabung dengan ribuan pengguna yang sudah menggantikan kekacauan catatan dengan satu planner yang jelas.</p>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-coral-lg">
                            <i class="ti ti-layout-dashboard" aria-hidden="true"></i>
                            Buka dashboard saya
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-coral-lg">
                            <i class="ti ti-rocket" aria-hidden="true"></i>
                            Mulai gratis — tanpa kartu kredit
                        </a>
                    @endauth
                    <p class="cta-note">Gratis selamanya. Data kamu tidak dijual ke siapapun.</p>
                </div>
            </div>
        </section>

    </main>

    {{-- ── Footer ── --}}
    <footer class="lp-footer">
        <div class="wrap">
            <div class="footer-inner">
                <div class="footer-brand">
                    <span class="footer-brand-dot" aria-hidden="true"></span>
                    {{ config('app.name', 'CrePlann') }}
                </div>
                <ul class="footer-links" role="list">
                    <li><a href="#">Fitur</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Bantuan</a></li>
                    <li><a href="#">Kontak</a></li>
                </ul>
                <div class="footer-copy">&copy; {{ date('Y') }} {{ config('app.name', 'CrePlann') }}. Hak cipta dilindungi.</div>
            </div>
        </div>
    </footer>


</body>
</html>
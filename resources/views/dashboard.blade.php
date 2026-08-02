<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="color-scheme" content="light">
<title>CrePlann — Dashboard</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
</head>
<body>

@include('layouts.navigation')

<div class="wrap page-header"><h1>Dashboard</h1></div>

<main class="page-body">
<div class="wrap">

    <div class="dashboard-greeting">
        <p class="welcome">Halo, Dzaha <span>👋</span></p>
        <p>Jumat, 31 Juli 2026 — semoga pekanmu berjalan sesuai rencana.</p>
    </div>

    <div class="week-strip">
        <div class="day done"><div class="dot">S</div></div>
        <div class="day done"><div class="dot">S</div></div>
        <div class="day done"><div class="dot">R</div></div>
        <div class="day today"><div class="dot">K
            <svg class="scribble" viewBox="0 0 52 52" fill="none"><path d="M26 4C13 4 5 13 5 26s8 22 21 22 21-9 21-22c0-6-2-11-5-15" stroke="var(--coral)" stroke-width="2" stroke-linecap="round"/></svg>
        </div></div>
        <div class="day"><div class="dot">J</div></div>
        <div class="day"><div class="dot">S</div></div>
        <div class="day"><div class="dot">M</div></div>
    </div>

    <div class="stat-grid">

        <div class="card stat-card">
            <h3><svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="4.5" width="17" height="16" rx="2.5" stroke="currentColor" stroke-width="1.7"/><path d="M8 2.8v3.6M16 2.8v3.6M3.5 9.5h17" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg> Jadwal Hari Ini</h3>
            <p class="sub">Agenda yang menantimu sampai malam.</p>
            <div class="agenda-item"><div class="agenda-time">09:00</div><div class="agenda-title">Kelas RPL — Praktikum Laravel</div><div class="agenda-tag">Tinggi</div></div>
            <div class="agenda-item"><div class="agenda-time">13:00</div><div class="agenda-title">Mentoring LKS 3D Model</div><div class="agenda-tag">Sedang</div></div>
            <div class="agenda-item"><div class="agenda-time">19:30</div><div class="agenda-title">Render animasi kincir angin</div><div class="agenda-tag">Tinggi</div></div>
        </div>

        <div class="card stat-card">
            <h3><svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="3.5" width="17" height="17" rx="3" stroke="currentColor" stroke-width="1.7"/><path d="M7.5 12l2.6 2.6L16.5 9" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/></svg> Progress Pekan Ini</h3>
            <p class="sub">Todo yang sudah kamu selesaikan.</p>
            <div class="progress-track"><div class="progress-fill" style="width:64%;"></div></div>
            <div class="progress-label"><span><strong>64%</strong> selesai</span><span>7/11 tugas</span></div>
            <div class="todo-list" style="margin-top:18px;">
                <div class="todo-row checked"><span class="checkbox-btn"></span><span class="title">Export model 3D windmill</span></div>
                <div class="todo-row checked"><span class="checkbox-btn"></span><span class="title">Revisi UI dashboard</span></div>
                <div class="todo-row"><span class="checkbox-btn"></span><span class="title">Siapkan materi presentasi LKS</span></div>
            </div>
        </div>

        <div class="card stat-card">
            <h3><svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><path d="M15 3.5V7h3.5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg> Catatan Terbaru</h3>
            <p class="sub">Yang terakhir kamu tulis.</p>
            <div class="note-card"><span class="cat">Kuliah</span><h4>Ide fitur kalender bulanan</h4><p>Tambahkan tampilan bulan penuh selain grid mingguan yang sudah ada...</p></div>
            <div class="note-card"><span class="cat">Pribadi</span><h4>Referensi warna doodle</h4><p>Coral #E15B3F cocok dipasangkan dengan sage #7E9083 untuk aksen kedua...</p></div>
        </div>

    </div>
</div>
</main>

</body>
</html>
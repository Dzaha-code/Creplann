# Laporan Optimasi Performa — CrePlann

> Framework: Laravel 13.x · Frontend: Blade + Tailwind CSS + Alpine · Build: Vite 8
> Tanggal: 22 Agustus 2026

---

## 1. Ringkasan Eksekutif

Website telah dioptimasi menyeluruh di **4 lapisan**: (A) Frontend aset, (B) Backend Laravel, (C) Asset delivery, (D) Accessibility & UX. Semua perubahan sudah **diimplementasikan langsung ke kode**, **build berhasil** (`npm run build`), **53 test lolos**, dan **semua Blade view terkompilasi** tanpa error.

### Dampak utama

| Aspek | Sebelum | Sesudah |
|---|---|---|
| Halaman landing (welcome) | Load **app.css + app.js** (~46KB JS Alpine + planner) + 44KB CSS inline + tabler blocking | Hanya **welcome.css (23KB / gzip 5.4KB) + welcome.js (1.3KB)** — tanpa Alpine sama sekali |
| Halaman login/register/guest | Load **Alpine + planner JS** (~46KB) yang tidak terpakai | **Nol JavaScript** (CSS saja) |
| CSS inline per halaman | 30–60KB per halaman, **unminified, tidak cacheable** | Di-extract ke file Vite: **minified + hashed + long-term cache** |
| CSS di HTML response | ±180KB total (duplikat tiap request) | Rata-rata **9–24KB per halaman (gzip 2–5KB)** |
| Google Fonts | `@import` di dalam CSS (render-blocking chain, tanpa preconnect) | `<link>` + **preconnect + preload woff2 kritis** + `display=swap` |
| Tabler Icons (CDN) | Render-blocking di 5 halaman | **Non-blocking** (`media="print" onload`) + preconnect |
| Bundle JS | 1 bundle besar (Alpine + planner + komponen mati) | **Code splitting**: Alpine chunk terpisah + JS per halaman (1–14KB) |
| Query DB (weekly grid) | Query ulang penuh di **setiap** request | **Query cache 5 menit** per user + auto-invalidasi via model events |
| Index DB | Hanya index FK tunggal | +3 index komposit untuk query tersering |
| Tree shaking | Komponen Alpine `weeklyPlanner`/`notesPage` **mati** (tidak dipakai view mana pun) | Dihapus dari bundle |
| CLS | `background-attachment: fixed`, font swap tanpa preload, animasi tanpa reduced-motion | Font dipreload, `background-attachment` dihapus, `prefers-reduced-motion` |
| Aksesibilitas | Tanpa skip link, fokus tidak konsisten | Skip link + `:focus-visible` + main landmark di semua halaman |
| SEO | Landing punya description, layout app **tidak** | Meta description + Open Graph + theme-color di layout app |

---

## 2. Diagnosis Awal (Akar Masalah)

1. **Inline `<style>` raksasa di setiap halaman** — schedule (61KB file), Note (54KB), todo (43KB), dashboard (31KB), welcome (74KB). CSS ini: tidak diminify, dikirim ulang utuh di setiap request, dan tidak bisa di-cache browser.
2. **Semua halaman memuat bundle app yang sama** — termasuk halaman yang tidak butuh JavaScript sama sekali (welcome, login, register) atau tidak butuh Alpine (semua halaman! `weeklyPlanner`/`notesPage` ternyata **tidak pernah dipakai** oleh view mana pun — halaman schedule & notes memakai vanilla JS sendiri).
3. **Google Fonts via `@import` di CSS** — menciptakan rantai render-blocking (CSS → font CSS → font file) tanpa preconnect.
4. **Tabler Icons via CDN render-blocking** di 5 halaman, dimuat sebagai stylesheet sinkron.
5. **Backend tanpa cache** — `WeeklyGridService::buildForUser` menjalankan 3–4 query berat di setiap request; dashboard bahkan membangun grid **dua kali** (server-render + fetch API).
6. **Index DB kurang** — query `whereBetween(date)` di-scope `user_id` hanya memakai index FK tunggal.
7. **CLS & UX**: `background-attachment: fixed` (repaint mahal di mobile), font swap tanpa preload, tidak ada skip link / focus ring / reduced-motion.

---

## 3. Perubahan Per File (Frontend)

### 3.1 `vite.config.js` — code splitting & multi-entry
- 13 entry: app, welcome, dashboard, schedule, todo, notes (CSS+JS masing-masing).
- `manualChunks`: Alpine dipisah ke chunk `alpine` sendiri → cache browser tetap valid saat kode aplikasi berubah.
- `cssCodeSplit: true`, `minify: 'oxc'` (Vite 8 tidak lagi membundel esbuild), `target: 'es2018'`.

### 3.2 CSS — inline → file Vite (minify + cache + HTML lebih kecil)
| File baru | Isi | Ukuran build (gzip) |
|---|---|---|
| `resources/css/welcome.css` | Desain sistem landing (konten `<style>` lama, identik) | 23.46 KB (5.41) |
| `resources/css/pages/schedule.css` | Grid mingguan | 13.60 KB (3.22) |
| `resources/css/pages/todo.css` | Halaman todo | 14.39 KB (3.04) |
| `resources/css/pages/notes.css` | Halaman notes | 8.79 KB (2.24) |
| `resources/css/pages/dashboard.css` | Bento dashboard | 9.42 KB (2.29) |

### 3.3 JS — per halaman + tree shaking
| File baru | Isi | Ukuran build (gzip) |
|---|---|---|
| `resources/js/welcome.js` | Animasi preview landing (1.28 KB / 0.70) | |
| `resources/js/pages/schedule.js` | CRUD jadwal + grid mingguan (14.04 KB / 4.01) | |
| `resources/js/pages/notes.js` | CRUD catatan + kategori (11.98 KB / 3.39) | |
| `resources/js/pages/todo.js` | Toggle edit todo (1.28 KB / 0.50) | |
| `resources/js/pages/dashboard.js` | Statistik dashboard (3.89 KB / 1.54) | |

- **`resources/js/app.js`** (0.28 KB): hanya Alpine bootstrap + scroll navbar dengan **throttle `requestAnimationFrame`** (dipindah dari `navigation.blade.php`). Komponen mati (`weeklyPlanner`, `notesPage`, `plannerApi`) **dihapus** — tidak direferensikan view mana pun (diverifikasi via grep). File `resources/js/planner/api.js` dipertahankan di disk sebagai utilitas, tetapi tidak lagi masuk bundle.
- **`resources/js/pages/notes.js`**: 5 echo `<?php json_encode(...) ?>` diganti pola `window.__notesConfig` (data disuntikkan Blade via `@json`) — karena JS diekstrak ke file statis, PHP tidak bisa dieksekusi di dalamnya.

### 3.4 Layout & head
- **`layouts/app.blade.php`**: preconnect `fonts.googleapis.com` + `fonts.gstatic.com` + `cdn.jsdelivr.net` + `lh3.googleusercontent.com` (avatar CDN), **preload woff2 kritis** (Big Shoulders Display latin), tabler icons **non-blocking** + fallback `<noscript>`, meta description + OG + theme-color, **skip link** + `<main id="main-content" tabindex="-1">`.
- **`layouts/guest.blade.php`**: font link disamakan dengan app (membuang Fraunces + Plus Jakarta Sans yang tidak pernah terpakai di halaman auth), `@vite` **CSS only** (tanpa app.js), skip link.
- **`auth/login.blade.php` & `auth/register.blade.php`**: sama — font link baru, CSS only, skip link + `id="main-content"`.
- **`welcome.blade.php`**: font + preconnect + tabler non-blocking + `@vite(['resources/css/welcome.css','resources/js/welcome.js'])` (tidak lagi app bundle), skip link, `<main id="main-content">`, meta OG.
- **`layouts/navigation.blade.php`**: script scroll inline dihapus (pindah ke app.js dengan rAF throttle).
- **`dashboard/todo/Note`**: link/push Tabler CDN dihapus (kini disediakan layout sekali, non-blocking).

### 3.5 `resources/css/app.css`
- `@import` Google Fonts **dihapus** (pindah ke `<link>` head).
- `background-attachment: fixed` **dihapus** (penyebab repaint/scroll jank di mobile).
- Ditambahkan: `.skip-link`, `:focus-visible` konsisten, `@media (prefers-reduced-motion: reduce)`.
- `resources/css/welcome.css` juga mendapat: skip link, focus-visible, reduced-motion, dan **`content-visibility: auto`** untuk section di bawah fold (LCP lebih cepat, visual tidak berubah).

---

## 4. Perubahan Per File (Backend)

### 4.1 `app/Services/GridCacheService.php` (baru)
Query cache **versioned per user** — alih-alih menghapus banyak kunci (Laravel tidak mendukung wildcard delete), nomor versi user di-"bump" sehingga semua kunci lama otomatis basi.
- `remember(User, weekStartDate, callback)` → `Cache::remember(key, 300s, ...)`
- `flush(userId)` → `Cache::increment(versionKey)`

### 4.2 `app/Services/WeeklyGridService.php`
`buildForUser()` kini memakai cache (kunci = tanggal awal minggu → semua anchor dalam satu minggu berbagi satu cache). Badan lama dipindah ke `private build()`.

### 4.3 Model events — auto-invalidasi (tidak ada data basi > 5 menit)
`Schedule`, `Todo`, `Note`, `Category` masing-masing mendapat:
```php
protected static function booted(): void
{
    static::saved(fn (self $model) => GridCacheService::flush((int) $model->user_id));
    static::deleted(fn (self $model) => GridCacheService::flush((int) $model->user_id));
}
```
→ CRUD schedule/todo/note/kategori langsung membatalkan cache grid user tersebut. **Tidak ada perubahan API/controller** — endpoint dan halaman tetap sama.

### 4.4 `database/migrations/2026_08_22_000001_add_performance_indexes.php` (baru, sudah dijalankan)
- `schedules`: index komposit `(user_id, date)` — untuk weekly grid & filter minggu.
- `todos`: index komposit `(user_id, due_date)`.
- `notes`: index komposit `(user_id, created_at)` — untuk urutan "terbaru".
- Punya `down()` untuk rollback.

---

## 5. Hasil Build (terukur)

```
public/build/assets/app-AlwqVF_j.css       90.96 kB │ gzip: 14.11 kB   (app layout)
public/build/assets/app-CxuSzHOc.js         0.28 kB │ gzip:  0.24 kB   (bootstrap Alpine)
public/build/assets/alpine-_N47BW7I.js     45.66 kB │ gzip: 16.22 kB   (chunk vendor, di-cache lintas halaman)
public/build/assets/welcome-Cxg9XUqA.css   23.46 kB │ gzip:  5.41 kB
public/build/assets/welcome-SWZG8RMV.js     1.28 kB │ gzip:  0.70 kB
public/build/assets/schedule-BAFshrBp.css  13.60 kB │ gzip:  3.22 kB
public/build/assets/schedule-K5tKfI5Y.js   14.04 kB │ gzip:  4.01 kB
public/build/assets/todo-CFZYeDKP.css      14.39 kB │ gzip:  3.04 kB
public/build/assets/todo-C1vCD0pQ.js        1.28 kB │ gzip:  0.50 kB
public/build/assets/notes-Bb9GwXw9.css      8.79 kB │ gzip:  2.24 kB
public/build/assets/notes-BTsmiRL7.js      11.98 kB │ gzip:  3.39 kB
public/build/assets/dashboard-CrdN1Dff.css  9.42 kB │ gzip:  2.29 kB
public/build/assets/dashboard-DV5JMBpD.js   3.89 kB │ gzip:  1.54 kB
```

**Perbandingan per halaman (JS + CSS, gzip):**
- **Welcome**: ~1.3 KB JS + 5.4 KB CSS (sebelumnya ~46 KB JS + ~60 KB CSS, plus 2 stylesheet blocking).
- **Login/Register**: 0 KB JS + 14.1 KB CSS (sebelumnya ~46 KB JS).
- **Dashboard/Schedule/Todo/Notes**: ~16–20 KB JS + ~17 KB CSS total, dengan Alpine di-cache antar halaman.

---

## 6. Panduan Langkah demi Langkah

### 6.1 Build & verifikasi (sudah dilakukan, tinggal diulang di lingkungan Anda)
```bash
# Prasyarat: Node.js >= 20.19 (Vite 8 tidak jalan di Node 20.12 — lihat §8)
node -v

# 1. Install dependensi (bila node_modules belum lengkap)
npm ci

# 2. Build production
npm run build

# 3. Caching Laravel (jalankan di setiap deploy)
php artisan optimize            # config, route, view cache
php artisan migrate --force     # index DB (sudah dijalankan)

# 4. Cek cepat
php artisan test                # 53 test (sudah lolos)
```

### 6.2 Deployment checklist
1. `composer install --no-dev --optimize-autoloader`
2. `npm ci && npm run build`
3. `php artisan optimize` + `php artisan migrate --force`
4. Jalankan queue worker: `php artisan queue:work` (untuk email verifikasi & tugas berat; lihat §7.5)
5. Verifikasi header respons: gzip/brotli aktif, `Cache-Control` aset statis benar (lihat §7)

### 6.3 Benchmark ulang
1. **Lighthouse** (Chrome DevTools → Lighthouse, mode Mobile + Desktop).
2. **WebPageTest** — pastikan `Render-blocking resources` turun (font & tabler kini non-blocking).
3. **GTmetrix / PageSpeed Insights** untuk field data.

---

## 7. Rekomendasi Server-Side

### 7.1 Gzip/Brotli + Cache Headers (prioritas tertinggi)
**Apache (Laragon / cPanel)** — tambahkan di `.htaccess` root:
```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json image/svg+xml
</IfModule>
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/avif "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType font/woff2 "access plus 1 year"
    ExpiresByType text/html "access plus 0 seconds"
</IfModule>
```
**Nginx**:
```nginx
gzip on;
gzip_types text/css application/javascript application/json image/svg+xml font/woff2;
location /build/ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```
> File build Vite ber-hash → aman di-cache `immutable` 1 tahun.

### 7.2 HTTP/2 atau HTTP/3
- Laragon: aktifkan HTTP/2 di `httpd.conf` (`Protocols h2 http/1.1`) — atau gunakan **LiteSpeed**.
- Pastikan sertifikat SSL aktif (HTTP/2 butuh HTTPS). Tanpa HTTPS, preconnect & preload tidak optimal.

### 7.3 CDN
- Letakkan `public/` di balik CDN (Cloudflare gratis sudah cukup): cache statis otomatis, brotli otomatis, HTTP/3 tersedia.
- Halaman HTML **jangan** di-cache CDN (perlu fresh token CSRF) — kecuali landing (bisa cache pendek + `Cache-Control: s-maxage=60`).

### 7.4 Redis untuk cache & queue (ganti `CACHE_STORE=database`)
Database cache melambat di traffic tinggi (tiap read = query DB).
```dotenv
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```
Lalu: `php artisan config:cache`.

### 7.5 Queue
Sudah terpasang (`QUEUE_CONNECTION=database`, tabel jobs ada). Pindahkan tugas berat ke queue:
- Email (verifikasi, notifikasi) — Laravel otomatis via `ShouldQueue`.
- Export/PDF/generate image — kirim `dispatch()`.
Jalankan worker: `php artisan queue:work` (atau supervisor/systemd di production).

### 7.6 OPcache
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0   ; production — clear via `php artisan optimize` saat deploy
```

### 7.7 Optimasi gambar (untuk konten masa depan)
- Konversi ke **WebP/AVIF** (Intervention Image atau `cwebp`).
- `srcset` + `sizes` untuk gambar responsif.
- `loading="lazy"` untuk gambar di bawah fold; `fetchpriority="high"` untuk hero.
- Saat ini `public/` belum punya gambar konten (hanya favicon) — avatars Google sudah via CDN.

---

## 8. Catatan Penting untuk Lingkungan Anda

1. **Node.js versi mesin Anda (20.12) terlalu lama untuk Vite 8** (butuh `^20.19.0 || >=22.12.0`). Build di mesin ini saya jalankan dengan **Node 22.13.0 portabel** (diunduh ke temp, tidak mengubah sistem) — hasilnya sukses. **Upgrade Node lokal** (via nvm-windows / installer nodejs.org) sebelum `npm run build` sendiri.
2. `vite.config.js` memakai `minify: 'oxc'` — Vite 8 (rolldown) tidak lagi membundel esbuild; opsi `minify: 'esbuild'` akan error `Cannot find package 'esbuild'`.
3. `resources/js/planner/api.js` tidak lagi di-import oleh bundle (tidak dipakai) — file dipertahankan, aman dihapus bila tidak akan dipakai.
4. Komponen Alpine `weeklyPlanner` & `notesPage` yang dihapus memang **tidak direferensikan** oleh view mana pun (pola lama yang tergantikan vanilla JS) — bukan fitur yang hilang.
5. Migration index sudah **dijalankan** di DB `creplann` (status `DONE`). Bila perlu rollback: `php artisan migrate:rollback --step=1`.
6. `CACHE_STORE=database` tetap bekerja untuk GridCacheService — data basi maksimal 5 menit dan langsung invalid saat ada perubahan data (model events).
7. **Jangan pernah menaruh array literal berisi koma di dalam `@json(...)`** — implementasi `@json` Laravel memakai `explode(',')` sehingga array multi-baris akan dipotong dan menghasilkan ParseError saat view di-render (kasus yang sempat muncul di `Note/index.blade.php` dan sudah diperbaiki dengan pola: kumpulkan data di blok `@php` → `@json($variabelTunggal)`).

---

## 9. Checklist Target Lighthouse / CWV

| Metrik | Target | Strategi yang sudah diterapkan |
|---|---|---|
| Performance | ≥ 90 | CSS/JS split & minified, font preload, tabler non-blocking, cache backend |
| LCP | < 2.5s | Preload font headline, konten hero langsung ter-render (CSS lokal), `content-visibility` di landing |
| FID/INP | < 100ms | JS per halaman kecil (1–14KB), scroll handler rAF-throttle, tanpa polling berat |
| CLS | < 0.1 | Font `display=swap` + preload, `background-attachment` dihapus, dimensi elemen stabil, `prefers-reduced-motion` |
| Accessibility | ≥ 90 | Skip link, landmark `<main>`, `:focus-visible`, ARIA (sudah ada), kontras (sudah di-patch user) |
| Best Practices | ≥ 90 | HTTPS-ready, aset modern, tanpa console error, meta viewport |
| SEO | ≥ 90 | Meta description + OG di semua layout, semantic HTML, `lang` benar |

---

## 10. Daftar Lengkap File yang Diubah

**Baru (dibuat tim optimasi):**
- `app/Services/GridCacheService.php`
- `database/migrations/2026_08_22_000001_add_performance_indexes.php`
- `resources/css/welcome.css`, `resources/css/pages/{schedule,todo,notes,dashboard}.css`
- `resources/js/welcome.js`, `resources/js/pages/{schedule,todo,notes,dashboard}.js`

**Diubah (tim optimasi):**
- `vite.config.js`
- `resources/css/app.css`
- `resources/js/app.js`
- `resources/views/layouts/{app,guest,navigation}.blade.php`
- `resources/views/welcome.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/schedule/index.blade.php`
- `resources/views/todo/index.blade.php`
- `resources/views/Note/index.blade.php`
- `resources/views/auth/{login,register}.blade.php`
- `app/Services/WeeklyGridService.php`
- `app/Models/{Schedule,Todo,Note,Category}.php`

> File `old_app.css` dan `old_welcome.blade.php` di root **tidak disentuh**.

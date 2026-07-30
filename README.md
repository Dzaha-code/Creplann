<div align="center">

<img src="https://img.icons8.com/doodle/96/000000/todo-list.png" width="90" alt="CrePlann" />

# CrePlann

**Rencanakan pekanmu. Wujudkan targetmu.**

Weekly planner, todo, dan notes app — dibangun di atas Laravel, dirancang untuk terasa personal.

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Breeze](https://img.shields.io/badge/Auth-Laravel_Breeze-6366F1?style=for-the-badge)](https://laravel.com/docs/starter-kits)
[![License](https://img.shields.io/badge/License-MIT-8CA37B?style=for-the-badge)](#-lisensi)

</div>

---

## ✦ Kenapa CrePlann?

Kebanyakan planner app terasa seperti spreadsheet yang dipaksa jadi cantik. **CrePlann** dibangun dari arah sebaliknya: mulai dari bagaimana orang benar-benar merencanakan pekan mereka — jadwal, tugas, dan catatan yang saling terhubung, bukan tiga fitur terpisah yang kebetulan ada di satu aplikasi.

Setiap pengguna punya ruang privatnya sendiri: jadwal, todo, kategori, dan catatan yang terisolasi penuh per akun, siap dikembangkan dari proyek personal jadi produk multi-pengguna sungguhan.

<div align="center">
<img src="https://img.icons8.com/doodle/48/000000/idea.png" alt="idea" />
</div>

## 📖 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Teknologi](#-teknologi)
- [Arsitektur Data](#-arsitektur-data)
- [API Endpoint](#-api-endpoint)
- [Instalasi](#-instalasi)
- [Roadmap](#-roadmap)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

## 🎯 Fitur Utama

| | |
|---|---|
| 🗓️ **Jadwal Mingguan** | Susun `schedules` dengan prioritas dan kode warna sendiri, per hari, per jam. |
| ✅ **Todo Terhubung** | `todos` bisa berdiri sendiri atau terikat langsung ke sebuah jadwal. |
| 🗂️ **Catatan Terorganisir** | `notes` dikelompokkan lewat `categories` buatan pengguna sendiri. |
| 📊 **Ringkasan Aktivitas** | Dashboard menampilkan gambaran pekan secara sekilas, tanpa perlu buka satu-satu. |
| 🔒 **Privat per Akun** | Semua data terisolasi penuh per user — tidak ada kebocoran antar akun. |
| 🔑 **Autentikasi Siap Pakai** | Login, register, dan manajemen sesi lewat Laravel Breeze. |

## 🛠️ Teknologi

<div align="center">

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-003B57?style=flat-square&logo=sqlite&logoColor=white)

</div>

| Layer | Pilihan |
|---|---|
| Backend | Laravel 12 / 13 |
| Frontend | Blade + Tailwind CSS |
| Database | MySQL (default) atau SQLite |
| Autentikasi | Laravel Breeze |

## 🧬 Arsitektur Data

Lima tabel inti, saling terhubung lewat kepemilikan `user` sebagai pusatnya:

```mermaid
erDiagram
    USERS ||--o{ SCHEDULES : memiliki
    USERS ||--o{ TODOS : memiliki
    USERS ||--o{ CATEGORIES : memiliki
    USERS ||--o{ NOTES : memiliki
    SCHEDULES |o--o{ TODOS : "opsional terhubung"
    CATEGORIES ||--o{ NOTES : mengelompokkan

    USERS {
        id id
        string name
        string email
        string password
        string avatar
        string google_id
    }
    SCHEDULES {
        id id
        id user_id
        string title
        text description
        date date
        time start_time
        time end_time
        string priority
        string color
    }
    TODOS {
        id id
        id user_id
        id schedule_id
        string title
        boolean completed
        date due_date
    }
    CATEGORIES {
        id id
        id user_id
        string name
        string color
    }
    NOTES {
        id id
        id user_id
        id category_id
        string title
        text content
    }
```

**Ringkasan relasi:**
- `User` → hasMany `schedules`, `todos`, `categories`, `notes`
- `Category` → hasMany `notes`
- `Schedule` → hasMany `todos` *(opsional)*
- `Todo` → belongsTo `user` dan `schedule`
- `Note` → belongsTo `user` dan `category`

## 🚀 API Endpoint

Endpoint CRUD user yang sudah tersedia:

| Method | Endpoint | Keterangan |
|---|---|---|
| `GET` | `/api/users` | Daftar semua user |
| `GET` | `/api/users/{id}` | Ambil satu user berdasarkan ID |
| `POST` | `/api/users` | Buat user baru |
| `PUT` | `/api/users/{id}` | Perbarui user |
| `DELETE` | `/api/users/{id}` | Hapus user |

<details>
<summary><strong>Contoh request — <code>POST /api/users</code></strong></summary>

```json
{
  "name": "Admin",
  "email": "admin@example.com",
  "password": "password123"
}
```

</details>

## ⚙️ Instalasi

```bash
# 1. Salin file environment
cp .env.example .env

# 2. Install dependensi
composer install
npm install

# 3. Generate application key
php artisan key:generate

# 4. Jalankan migrasi database
php artisan migrate

# 5. Jalankan server lokal
php artisan serve
```

Aplikasi akan berjalan di `http://127.0.0.1:8000`.

> **Catatan:** pastikan `SESSION_DRIVER` di `.env` sesuai kebutuhanmu (`database` atau `file`). Ingin login via Google? Tinggal integrasikan [Laravel Socialite](https://laravel.com/docs/socialite) — kolom `google_id` dan `avatar` di tabel `users` sudah disiapkan untuk itu.

## 🗺️ Roadmap

- [ ] Login sosial (Google) lewat Laravel Socialite
- [ ] Notifikasi pengingat jadwal & tenggat todo
- [ ] Tampilan kalender bulanan, bukan hanya mingguan
- [ ] Ekspor jadwal ke `.ics` / Google Calendar
- [ ] Mode kolaborasi — berbagi jadwal ke pengguna lain

## 🤝 Kontribusi

Proyek ini masih terus berkembang. Ide, laporan bug, atau pull request sangat terbuka — silakan buat *issue* baru atau ajukan PR.

Dokumen perencanaan sumber tersedia di folder `required/`:
- `required/A-ProjectSpec.md`
- `required/B-Concept.md`

## 📄 Lisensi

Dirilis di bawah lisensi **MIT** — bebas digunakan, dimodifikasi, dan dikembangkan lebih lanjut.

---

<div align="center">
<img src="https://img.icons8.com/doodle/48/000000/idea.png" alt="idea" /><br/>
<em>CrePlann — rencana mingguan yang lebih mudah dan lebih visual.</em>
</div>
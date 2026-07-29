# CrePlann — Weekly Planner

<p align="center">
  <img src="https://img.icons8.com/doodle/96/000000/todo-list.png" alt="Doodle Planner" />
</p>

<p align="center">
  <strong>CrePlann</strong> adalah aplikasi web untuk mengatur jadwal mingguan, todo, dan catatan pribadi menggunakan Laravel.
</p>

## 🎯 Ringkasan Proyek

CrePlann dibuat untuk membantu pengguna:

- Mengelola jadwal mingguan (`schedules`)
- Menyusun daftar tugas harian (`todos`)
- Menyimpan catatan dengan kategori (`notes`, `categories`)
- Melihat ringkasan aktivitas pada dashboard
- Menjaga semua data tetap privat untuk setiap pengguna

## 🧩 Fitur Utama

- Autentikasi pengguna dengan Laravel Breeze
- CRUD untuk `schedules`, `todos`, `notes`, dan `categories`
- Relasi data user-private antara user, schedule, todo, category, dan note
- API endpoint untuk mengakses data pengguna
- Basis data MySQL / SQLite siap pakai sesuai konfigurasi

## 🛠️ Teknologi

- Backend: Laravel 12 / 13
- Frontend: Blade + Tailwind CSS
- Database: MySQL (default) / SQLite
- Authentication: Laravel Breeze

## 📚 Struktur Database

Tabel inti:

- `users`
  - `id`, `name`, `email`, `password`, `avatar`, `google_id`, timestamps
- `schedules`
  - `id`, `user_id`, `title`, `description`, `date`, `start_time`, `end_time`, `priority`, `color`, timestamps
- `todos`
  - `id`, `user_id`, `schedule_id`, `title`, `completed`, `due_date`, timestamps
- `categories`
  - `id`, `user_id`, `name`, `color`, timestamps
- `notes`
  - `id`, `user_id`, `category_id`, `title`, `content`, timestamps

## 🔗 Relasi Data

- `User` hasMany `schedules`, `todos`, `categories`, `notes`
- `Category` hasMany `notes`
- `Schedule` hasMany `todos` (opsional)
- `Todo` belongsTo `user` dan `schedule`
- `Note` belongsTo `user` dan `category`

## 🚀 API Endpoint User

Endpoint CRUD user saat ini:

- `GET /api/users` — daftar semua user
- `GET /api/users/{id}` — ambil satu user berdasarkan ID
- `POST /api/users` — buat user baru
- `PUT /api/users/{id}` — perbarui user
- `DELETE /api/users/{id}` — hapus user

Contoh `POST /api/users` request body:

```json
{
  "name": "Admin",
  "email": "admin@example.com",
  "password": "password123"
}
```

## ⚙️ Setup Lokal

1. Salin file environment:

```bash
cp .env.example .env
```

2. Install dependensi:

```bash
composer install
npm install
```

3. Generate application key:

```bash
php artisan key:generate
```

4. Jalankan migrasi database:

```bash
php artisan migrate
```

5. Jalankan server lokal:

```bash
php artisan serve
```

## ✔️ Catatan

- Semua data disimpan secara pribadi per akun.
- Pastikan `SESSION_DRIVER` sesuai dengan kebutuhan (database atau file).
- Jika ingin menambah Google Login, integrasikan `Laravel Socialite`.

## 📎 Referensi

Dokumen perencanaan sumber ada di folder `required/`, khususnya:

- `required/A-ProjectSpec.md`
- `required/B-Concept.md`

---

<p align="center">
  <img src="https://img.icons8.com/doodle/48/000000/idea.png" alt="Doodle Idea" />
  <em>CrePlann — rencana mingguan yang lebih mudah dan lebih visual.</em>
</p>

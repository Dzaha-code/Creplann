==========================================
PROJECT INFORMATION
==========================================

Project Name:
Weekly Planner/CrePlann

Description:
Aplikasi untuk membantu pengguna mengatur jadwal mingguan,
to-do list, dan catatan pribadi.

Framework:
Laravel 12

Database:
MySQL

Frontend:
Blade + Tailwind CSS

Authentication:
Laravel Breeze + Google Login (Socialite)

==========================================
PROJECT GOAL
==========================================

User dapat:

- Login menggunakan Google
- Membuat jadwal mingguan
- Mengelola todo
- Membuat notes
- Semua data hanya bisa diakses oleh pemilik akun.

==========================================
MAIN FEATURES
==========================================

1. Authentication
2. Weekly Schedule
3. Todo
4. Notes
5. Dashboard

==========================================
DATABASE STRUCTURE
==========================================

users

id
name
email
google_id
avatar

--------------------------

schedules

id
user_id
title
description
date
start_time
end_time
priority

--------------------------

todos

id
schedule_id
user_id
title
completed
due_date

--------------------------

categories

id
user_id
name

--------------------------

notes

id
user_id
category_id
title
content

==========================================
RELATIONSHIP
==========================================

User
 ├── Schedule
 │      └── Todo
 │
 └── Category
         └── Note

==========================================
BUSINESS LOGIC
==========================================

Schedule

- User membuat schedule
- Schedule milik satu user
- Schedule memiliki tanggal
- Schedule dapat memiliki banyak todo

Todo

- Todo dapat dibuat manual
- Todo dapat berasal dari schedule
- Todo dapat ditandai selesai

Notes

- Notes memiliki kategori
- User bebas membuat kategori

==========================================
VALIDATION
==========================================

Schedule

title
required

date
required

start_time
required

Todo

title
required

completed
boolean

Notes

title
required

content
required

==========================================

QA & REQUIREMENTS CHECKLIST
==========================================

Fitur Utama
- CRUD data utama: Tambah, Edit, Hapus, Tampil — berlaku untuk entitas utama (`users`, `schedules`, `todos`, `notes`, `categories`).
- Pencarian data: fitur pencarian/filtrasi pada `schedules`, `todos`, `notes`, dan `categories`.
- Semua tombol berfungsi tanpa error: tombol UI melakukan aksi yang diharapkan; error ditangani dengan baik.

SISTEM ROLE & KEAMANAN
- Role: `user` (pengguna biasa) dan `admin` (opsional untuk manajemen sistem).
- Pembatasan akses: hanya pemilik resource yang dapat mengakses/ubah data miliknya (policy/gate).
- Middleware: `auth`, `verified` (jika diperlukan), dan rate-limiting untuk endpoint sensitif.
- Cegah SQL Injection: selalu gunakan Eloquent/Query Builder dan parameter binding, hindari query mentah tanpa binding.
- Validasi server-side: gunakan `FormRequest` untuk memvalidasi input sebelum pemrosesan.

TESTING & DEBUGGING
- Tidak ada error: tangkap exception dan log (Log, Telescope, atau Sentry).
- Pengujian fitur sudah jalan: buat `Unit` dan `Feature` tests untuk operasi CRUD, autentikasi, dan akses kontrol.
- Coba login dengan akun berbeda: verifikasi isolasi data antar-akun (user A tidak melihat user B).

CETAK DATA / LAPORAN INFORMASI
- Data Transaksi: sediakan endpoint export (CSV/PDF) untuk data transaksi atau log penting jika relevan.
- Rekapitulasi: ringkasan mingguan/per kategori yang bisa diunduh atau ditampilkan di dashboard.

Checklist Pengujian Singkat
- [ ] Tambah/Edit/Hapus/Tampil untuk tiap entitas utama berhasil.
- [ ] Pencarian dan filter mengembalikan hasil yang benar.
- [ ] Kebijakan akses mencegah akses tidak sah.
- [ ] Tidak ditemukan SQL Injection pada endpoint yang diuji.
- [ ] Tes otomatis (unit/feature) untuk fitur kritis berjalan hijau.

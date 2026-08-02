# 🗺️ Weekly Planner Roadmap

## 🎯 Project Goal
Membuat aplikasi Weekly Planner berbasis Laravel yang memiliki fitur:
- Authentication
- Weekly Schedule
- Daily Todo
- Notes
- Dashboard
- UI yang responsif dan mudah dipakai

---

# Phase 0 - Planning

## Tujuan
Merancang aplikasi sebelum mulai pengembangan.

## Task
- [x] Menentukan fitur utama
- [x] Menyusun project specification
- [x] Menyusun flow aplikasi
- [ ] Membuat ERD
- [ ] Mendesain wireframe UI
- [ ] Menentukan tema visual aplikasi

## Output Minimal
- Spesifikasi proyek selesai
- Alur aplikasi jelas
- Struktur fitur utama sudah disepakati

---

# Phase 1 - Project Setup

## Tujuan
Menyiapkan project Laravel secara dasar.

## Task
- [x] Install Laravel
- [x] Setup Git Repository
- [x] Setup database MySQL
- [x] Konfigurasi environment
- [x] Install Tailwind CSS
- [x] Install Laravel Breeze
- [x] Menjalankan migration bawaan Laravel

## Output Minimal
- Laravel bisa berjalan
- Database terhubung
- Login dan register dasar tersedia

---

# Phase 2 - Layout & Navigation

## Tujuan
Membuat kerangka antarmuka aplikasi.

## Task
- [x] Membuat navbar
- [x] Membuat sidebar
- [ ] Membuat layout dashboard yang konsisten
- [x] Membuat footer
- [ ] Membuat layout responsif untuk mobile

## Output Minimal
- Halaman utama dan modul utama dapat diakses
- Navigasi antar fitur berjalan

---

# Phase 3 - Database Design

## Tujuan
Membuat struktur data untuk modul utama.

## Task
- [x] Membuat migration users
- [x] Membuat migration schedules
- [x] Membuat migration todos
- [x] Membuat migration categories
- [x] Membuat migration notes
- [ ] Menambahkan relasi dan constraint tambahan jika diperlukan

## Output Minimal
- Schema database utama terbentuk
- Relasi antar tabel berjalan

---

# Phase 4 - Schedule Module

## Tujuan
Membangun fitur jadwal mingguan.

## Task
- [ ] Halaman daftar schedule
- [ ] Form tambah schedule
- [ ] Form edit schedule
- [ ] Hapus schedule
- [ ] Validasi input
- [ ] Menampilkan schedule milik pengguna saja

## Output Minimal
Pengguna dapat membuat, melihat, mengubah, dan menghapus jadwal.

---

# Phase 5 - Weekly Planner View

## Tujuan
Menampilkan schedule dalam tampilan mingguan.

## Task
- [ ] Membuat tampilan grid mingguan
- [ ] Menampilkan schedule berdasarkan hari
- [ ] Menampilkan jam kegiatan
- [ ] Menampilkan warna berdasarkan prioritas

## Output Minimal
Pengguna dapat melihat jadwal dalam format mingguan dengan jelas.

---

# Phase 6 - Todo Module

## Tujuan
Membuat fitur todo harian.

## Task
- [ ] Daftar todo
- [ ] Tambah todo
- [ ] Edit todo
- [ ] Hapus todo
- [ ] Checkbox completed
- [ ] Filter todo hari ini atau berdasarkan status

## Output Minimal
Pengguna dapat mengelola todo harian dengan mudah.

---

# Phase 7 - Integration Schedule & Todo

## Tujuan
Menghubungkan jadwal dengan todo.

## Task
- [ ] Tombol generate todo dari schedule
- [ ] Membuat todo dari schedule
- [ ] Mencegah duplikasi todo

## Output Minimal
Schedule dapat menjadi sumber pembuatan todo.

---

# Phase 8 - Notes Module

## Tujuan
Membangun fitur catatan pribadi.

## Task
- [ ] CRUD notes
- [ ] CRUD category
- [ ] Filter berdasarkan category
- [ ] Pencarian notes

## Output Minimal
Pengguna dapat membuat dan mengelola catatan dengan kategori.

---

# Phase 9 - Dashboard

## Tujuan
Menampilkan ringkasan aktivitas pengguna.

## Task
- [ ] Today's Schedule
- [ ] Today's Todo
- [ ] Recent Notes
- [ ] Statistik todo
- [ ] Greeting user

## Output Minimal
Dashboard memuat ringkasan aktivitas secara dinamis.

---

# Phase 10 - UI & UX Improvement

## Tujuan
Menyempurnakan pengalaman pengguna.

## Task
- [ ] Validasi form
- [ ] Notifikasi toast
- [ ] Loading state
- [ ] Empty state
- [ ] Responsive mobile
- [ ] Dark mode (opsional)

## Output Minimal
Aplikasi terasa lebih profesional dan nyaman dipakai.

---

# Phase 11 - Authentication Google

## Tujuan
Menambahkan login menggunakan akun Google.

## Task
- [ ] Install Laravel Socialite
- [ ] Konfigurasi Google OAuth
- [ ] Login Google
- [ ] Menyimpan data user dari Google

## Output Minimal
Pengguna dapat login dengan akun Google.

---

# Phase 12 - Testing & Security

## Tujuan
Memastikan aplikasi aman dan stabil.

## Task
- [ ] Membuat unit dan feature test
- [ ] Menguji akses antar user
- [ ] Menguji validasi input dan authorization
- [ ] Meninjau keamanan route dan policy

## Output Minimal
Aplikasi siap diuji secara lebih matang sebelum deployment.

---

# Phase 13 - Deployment

## Tujuan
Mempublikasikan aplikasi.

## Task
- [ ] Menyiapkan environment produksi
- [ ] Menjalankan deployment
- [ ] Melakukan pengecekan pasca deploy

## Output Minimal
Aplikasi dapat diakses publik atau internal sesuai kebutuhan.


## Task
- [ ] Optimasi Laravel
- [ ] Upload ke Hosting / VPS
- [ ] Konfigurasi Database Production
- [ ] Testing

## Output Minimal
Aplikasi dapat diakses secara online.

---

# Checklist Project

## Authentication
- [ ] Login
- [ ] Register
- [ ] Logout
- [ ] Google Login

## Schedule
- [ ] Create
- [ ] Read
- [ ] Update
- [ ] Delete

## Todo
- [ ] Create
- [ ] Read
- [ ] Update
- [ ] Delete
- [ ] Complete

## Notes
- [ ] Category
- [ ] Create
- [ ] Read
- [ ] Update
- [ ] Delete

## Dashboard
- [ ] Today's Schedule
- [ ] Today's Todo
- [ ] Recent Notes
- [ ] Statistics

## UI
- [ ] Responsive
- [ ] Validation
- [ ] Notification
- [ ] Loading
- [ ] Empty State

---

# Project Status

Planning          [ ]
Setup             [ ]
Layout            [ ]
Database          [ ]
Schedule          [ ]
Weekly Planner    [ ]
Todo              [ ]
Integration       [ ]
Notes             [ ]
Dashboard         [ ]
Authentication    [ ]
UI Improvement    [ ]
Deployment        [ ]
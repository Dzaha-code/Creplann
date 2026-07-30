# 🗺️ Weekly Planner Roadmap

## 🎯 Project Goal
Membuat aplikasi Weekly Planner berbasis Laravel yang memiliki fitur:
- Authentication (Google Login)
- Weekly Schedule
- Daily Todo
- Notes
- Dashboard
- Responsive UI

---

# Phase 0 - Planning

## Tujuan
Merancang aplikasi sebelum mulai coding.

## Task
- [✅] Menentukan fitur utama
- [✅] Membuat PROJECT_SPEC.md
- [✅] Membuat Flowchart
- [ ] Membuat ERD (Entity Relationship Diagram)
- [ ] Mendesain Wireframe UI
- [ ] Menentukan warna dan tema aplikasi

## Output Minimal
- PROJECT_SPEC.md selesai
- ERD selesai
- Wireframe Dashboard selesai

---

# Phase 1 - Project Setup

## Tujuan
Menyiapkan project Laravel.

## Task
- [✅ ] Install Laravel
- [✅] Setup Git Repository
- [✅ ] Setup MySQL
- [✅ ] Konfigurasi .env
- [✅ ] Install Tailwind CSS
- [✅ ] Install Laravel Breeze
- [✅ ] Menjalankan Migration bawaan Laravel

## Output Minimal
- Laravel berjalan
- Database terkoneksi
- Login & Register berhasil
- Dashboard bawaan Laravel tampil

---

# Phase 2 - Layout

## Tujuan
Membuat kerangka tampilan aplikasi.

## Task
- [ ] Membuat Navbar
- [ ] Membuat Sidebar
- [ ] Membuat Dashboard Layout
- [ ] Membuat Footer
- [ ] Membuat Responsive Layout

## Output Minimal
Halaman memiliki:
- Dashboard
- Schedule
- Todo
- Notes
- Profile

Semua menu sudah bisa dibuka meskipun masih kosong.

---

# Phase 3 - Database Design

## Tujuan
Membuat struktur database.

## Task
- [ ] Membuat Migration Users (jika diperlukan)
- [ ] Membuat Migration Schedules
- [ ] Membuat Migration Todos
- [ ] Membuat Migration Categories
- [ ] Membuat Migration Notes

## Relationship
User
├── Schedule
├── Todo
├── Category
└── Note

## Output Minimal
- Semua migration berhasil
- Semua foreign key berjalan
- Semua model dibuat

---

# Phase 4 - Schedule Module

## Tujuan
Membuat CRUD Schedule.

## Task
- [ ] Halaman daftar Schedule
- [ ] Form tambah Schedule
- [ ] Edit Schedule
- [ ] Delete Schedule
- [ ] Validasi Form
- [ ] Menampilkan Schedule milik user

## Output Minimal
User dapat:
- Membuat Schedule
- Mengedit Schedule
- Menghapus Schedule
- Melihat daftar Schedule

---

# Phase 5 - Weekly Planner

## Tujuan
Menampilkan Schedule dalam tampilan mingguan.

## Task
- [ ] Membuat Grid Senin-Minggu
- [ ] Menampilkan Schedule berdasarkan hari
- [ ] Menampilkan jam kegiatan
- [ ] Warna berdasarkan prioritas (opsional)

## Output Minimal
Weekly Planner menampilkan semua jadwal sesuai tanggal.

---

# Phase 6 - Todo Module

## Tujuan
Membuat CRUD Todo.

## Task
- [ ] Daftar Todo
- [ ] Tambah Todo
- [ ] Edit Todo
- [ ] Hapus Todo
- [ ] Checkbox Completed
- [ ] Filter Todo Hari Ini

## Output Minimal
User dapat mengelola Todo harian.

---

# Phase 7 - Schedule Integration

## Tujuan
Menghubungkan Schedule dengan Todo.

## Task
- [ ] Tombol Generate Todo
- [ ] Membuat Todo dari Schedule
- [ ] Mencegah Todo Duplikat

## Output Minimal
Schedule dapat menghasilkan Todo.

---

# Phase 8 - Notes Module

## Tujuan
Membuat fitur catatan.

## Task
- [ ] CRUD Notes
- [ ] CRUD Category
- [ ] Filter berdasarkan Category
- [ ] Search Notes

## Output Minimal
User dapat membuat dan mengelola Notes.

---

# Phase 9 - Dashboard

## Tujuan
Menampilkan ringkasan aktivitas.

## Task
- [ ] Today's Schedule
- [ ] Today's Todo
- [ ] Recent Notes
- [ ] Statistik Todo
- [ ] Greeting User

## Output Minimal
Dashboard menampilkan data pengguna secara dinamis.

---

# Phase 10 - UI & UX Improvement

## Tujuan
Menyempurnakan aplikasi.

## Task
- [ ] Form Validation
- [ ] Toast Notification
- [ ] Loading Indicator
- [ ] Empty State
- [ ] Error Page
- [ ] Responsive Mobile
- [ ] Dark Mode (Opsional)
- [ ] Animasi Ringan

## Output Minimal
Aplikasi nyaman digunakan dan tampil profesional.

---

# Phase 11 - Authentication Google

## Tujuan
Menggunakan akun Google untuk Login.

## Task
- [ ] Install Laravel Socialite
- [ ] Konfigurasi Google OAuth
- [ ] Login Google
- [ ] Simpan Data User

## Output Minimal
User dapat login menggunakan akun Google.

---

# Phase 12 - Deployment

## Tujuan
Mempublikasikan aplikasi.

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
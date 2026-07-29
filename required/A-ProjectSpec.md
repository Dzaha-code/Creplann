# 📌 PROJECT SPECIFICATION

Project Name : Weekly Planner
Version      : 1.0
Author       : Dzahaa
Framework    : Laravel 12
Database     : MySQL
Frontend     : Blade + Tailwind CSS
Authentication : Laravel Breeze (Google Login akan ditambahkan)

---

# 1. Project Description

Weekly Planner merupakan aplikasi web yang membantu pengguna mengatur aktivitas sehari-hari.

Aplikasi ini berfokus pada tiga hal utama:

- Weekly Schedule
- Daily Todo
- Notes

Seluruh data bersifat private sehingga setiap pengguna hanya dapat melihat data miliknya sendiri.

---

# 2. Project Goals

Tujuan utama aplikasi:

✔ Membantu mengatur jadwal mingguan

✔ Mengelola daftar pekerjaan harian

✔ Menyimpan catatan berdasarkan kategori

✔ Menampilkan ringkasan aktivitas melalui Dashboard

✔ Memberikan antarmuka yang sederhana dan mudah digunakan

---

# 3. Main Features

## Authentication

- Register
- Login
- Logout
- Google Login (Future)

---

## Dashboard

Menampilkan informasi:

- Greeting User
- Today's Schedule
- Today's Todo
- Recent Notes
- Total Completed Todo
- Total Schedule This Week

---

## Weekly Schedule

User dapat:

- Melihat jadwal mingguan
- Menambah jadwal
- Mengedit jadwal
- Menghapus jadwal
- Menentukan tanggal
- Menentukan waktu mulai
- Menentukan waktu selesai
- Memberikan prioritas
- Memberikan warna

---

## Todo

User dapat:

- Membuat Todo
- Mengedit Todo
- Menghapus Todo
- Menandai selesai
- Memfilter Todo

---

## Notes

User dapat:

- Membuat Note
- Mengedit Note
- Menghapus Note
- Membuat Category
- Mengelompokkan Note berdasarkan Category

---

# 4. Target User

Target pengguna:

- Pelajar
- Mahasiswa
- Freelancer
- Pekerja

---

# 5. Tech Stack

Backend

- Laravel 12

Frontend

- Blade
- TailwindCSS

Database

- MySQL

Authentication

- Laravel Breeze
- Laravel Socialite (Future)

Version Control

- Git

Deployment

- Laravel Cloud / VPS / Shared Hosting

---

# 6. Database Design

users

- id
- name
- email
- password
- avatar
- google_id
- timestamps

---

schedules

- id
- user_id
- title
- description
- date
- start_time
- end_time
- priority
- color
- created_at
- updated_at

---

todos

- id
- user_id
- schedule_id (nullable)
- title
- completed
- due_date
- created_at
- updated_at

---

categories

- id
- user_id
- name
- color

---

notes

- id
- user_id
- category_id
- title
- content
- created_at
- updated_at

---

# 7. Database Relationship

User

├── hasMany Schedules

├── hasMany Todos

├── hasMany Categories

└── hasMany Notes

Category

└── hasMany Notes

Schedule

└── hasMany Todos (optional)

Todo

belongsTo User

belongsTo Schedule

Note

belongsTo User

belongsTo Category

---

# 8. Folder Structure

app

Controllers

Models

Policies

Requests

database

migrations

resources

views

dashboard

schedule

todo

notes

layouts

components

public

css

js

routes

web.php

---

# 9. UI Structure

Dashboard

Sidebar

- Dashboard
- Weekly Planner
- Todo
- Notes
- Profile
- Logout

Navbar

- Search (Future)
- User Avatar

Main Content

Footer

---

# 10. Workflow

Login

↓

Dashboard

↓

Weekly Planner

↓

Todo

↓

Notes

↓

Logout

---

# 11. Business Logic

## Authentication

- User wajib login untuk menggunakan aplikasi.

- Semua data harus berdasarkan user_id.

---

## Schedule

- User membuat Schedule.

- Schedule disimpan sesuai tanggal.

- Schedule hanya dapat diakses oleh pemiliknya.

- Schedule dapat memiliki Todo.

---

## Todo

- Todo dapat dibuat secara manual.

- Todo dapat berasal dari Schedule.

- Todo memiliki status Completed.

---

## Notes

- Notes harus memiliki Category.

- User bebas membuat Category.

---

## Dashboard

Dashboard menampilkan:

- Schedule hari ini

- Todo hari ini

- Notes terbaru

- Statistik sederhana

---

# 12. Validation Rules

Schedule

Title

Required

Maximum 100 Characters

Date

Required

Start Time

Required

End Time

Required

End Time >= Start Time

---

Todo

Title

Required

Maximum 100 Characters

Completed

Boolean

---

Category

Name

Required

Unique per User

---

Note

Title

Required

Content

Required

---

# 13. Security Rules

- Semua route menggunakan middleware auth.

- User tidak boleh melihat data user lain.

- User hanya dapat mengedit data miliknya.

- Semua form menggunakan CSRF Protection.

- Semua input harus divalidasi.

---

# 14. Coding Convention

Controller

ScheduleController

TodoController

NoteController

CategoryController

Model

Schedule

Todo

Note

Category

Variable

camelCase

Database

snake_case

Migration

plural_table_name

---

# 15. Future Features

- Google Login

- Dark Mode

- Reminder

- Calendar View

- Search

- Notification

- File Attachment

- Export PDF

- Export Excel

- Drag and Drop Schedule

- Mobile Responsive Improvement

---

# 16. Project Rules

- Tidak menggunakan package yang tidak diperlukan.

- Mengutamakan fitur bawaan Laravel.

- Semua CRUD mengikuti Resource Controller.

- Menggunakan Eloquent ORM.

- Tidak menggunakan Query Builder kecuali diperlukan.

- Menjaga struktur folder tetap rapi.

---

# 17. Development Principles

- Membuat satu fitur hingga selesai sebelum pindah ke fitur berikutnya.

- Tidak menggabungkan beberapa fitur dalam satu commit.

- Selalu melakukan testing setelah menyelesaikan satu fitur.

- Menulis kode yang mudah dibaca daripada kode yang terlalu kompleks.

- Mengutamakan maintainability dibanding optimasi yang belum diperlukan.

---

# 18. Definition of Done

Sebuah fitur dianggap selesai apabila:

- Berjalan tanpa error.
- Data tersimpan dengan benar.
- Validasi berfungsi.
- UI dapat digunakan.
- Tidak merusak fitur lain.
- Telah diuji secara manual.

---

# 19. Current Progress

Planning

✅ Done

Setup

⬜

Layout

⬜

Database

⬜

Schedule

⬜

Todo

⬜

Notes

⬜

Dashboard

⬜

Deployment

⬜
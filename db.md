# CrePlann — Database Schema & Seed Data

> **Database**: PostgreSQL (Supabase)  
> **Schema**: `public`  
> **Dibuat dari**: seluruh migration file Laravel yang ada di `database/migrations/`

Jalankan seluruh blok SQL di bawah ini secara **berurutan** melalui:
- Supabase Dashboard → **SQL Editor** → paste & run, atau
- `psql` CLI, atau
- Tools seperti TablePlus / DBeaver

---

## Daftar Isi

1. [Drop semua tabel (opsional, reset bersih)](#1-drop-semua-tabel-opsional)
2. [Tabel sistem Laravel](#2-tabel-sistem-laravel)
3. [Tabel aplikasi utama](#3-tabel-aplikasi-utama)
4. [Tabel blog & kontak](#4-tabel-blog--kontak)
5. [Insert data — Users](#5-insert-data--users)
6. [Insert data — Categories](#6-insert-data--categories)
7. [Insert data — Schedules](#7-insert-data--schedules)
8. [Insert data — Todos](#8-insert-data--todos)
9. [Insert data — Notes](#9-insert-data--notes)
10. [Insert data — Posts (Blog)](#10-insert-data--posts-blog)

---

## 1. Drop semua tabel (opsional)

> ⚠️ Hanya jalankan ini jika ingin **reset penuh** dari nol. Lewati jika database masih kosong.

```sql
-- Drop semua tabel dalam urutan terbalik (hindari FK violation)
DROP TABLE IF EXISTS contacts           CASCADE;
DROP TABLE IF EXISTS posts              CASCADE;
DROP TABLE IF EXISTS notes              CASCADE;
DROP TABLE IF EXISTS todos              CASCADE;
DROP TABLE IF EXISTS schedules          CASCADE;
DROP TABLE IF EXISTS categories         CASCADE;
DROP TABLE IF EXISTS password_reset_tokens CASCADE;
DROP TABLE IF EXISTS sessions           CASCADE;
DROP TABLE IF EXISTS cache_locks        CASCADE;
DROP TABLE IF EXISTS cache              CASCADE;
DROP TABLE IF EXISTS failed_jobs        CASCADE;
DROP TABLE IF EXISTS job_batches        CASCADE;
DROP TABLE IF EXISTS jobs               CASCADE;
DROP TABLE IF EXISTS migrations         CASCADE;
DROP TABLE IF EXISTS users              CASCADE;
```

---

## 2. Tabel sistem Laravel

### `migrations`
Digunakan Laravel untuk melacak migrasi yang sudah dijalankan.

```sql
CREATE TABLE IF NOT EXISTS migrations (
    id         SERIAL       PRIMARY KEY,
    migration  VARCHAR(255) NOT NULL,
    batch      INTEGER      NOT NULL
);
```

### `users`
Tabel utama akun pengguna. `password` nullable karena mendukung login via Google OAuth.

```sql
CREATE TABLE IF NOT EXISTS users (
    id                BIGSERIAL    PRIMARY KEY,
    name              VARCHAR(255) NOT NULL,
    email             VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMPTZ  DEFAULT NULL,
    password          VARCHAR(255) DEFAULT NULL,       -- nullable: Google OAuth users
    avatar            VARCHAR(255) DEFAULT NULL,       -- URL avatar dari Google
    google_id         VARCHAR(255) DEFAULT NULL,       -- Google OAuth user ID
    remember_token    VARCHAR(100) DEFAULT NULL,
    created_at        TIMESTAMPTZ  DEFAULT NULL,
    updated_at        TIMESTAMPTZ  DEFAULT NULL
);
```

### `password_reset_tokens`
Token untuk reset password via email.

```sql
CREATE TABLE IF NOT EXISTS password_reset_tokens (
    email      VARCHAR(255) PRIMARY KEY,
    token      VARCHAR(255) NOT NULL,
    created_at TIMESTAMPTZ  DEFAULT NULL
);
```

### `sessions`
Tabel session Laravel (dipakai jika `SESSION_DRIVER=database`).

```sql
CREATE TABLE IF NOT EXISTS sessions (
    id            VARCHAR(255) PRIMARY KEY,
    user_id       BIGINT       DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL,
    ip_address    VARCHAR(45)  DEFAULT NULL,
    user_agent    TEXT         DEFAULT NULL,
    payload       TEXT         NOT NULL,
    last_activity INTEGER      NOT NULL
);

CREATE INDEX IF NOT EXISTS sessions_user_id_index    ON sessions(user_id);
CREATE INDEX IF NOT EXISTS sessions_last_activity_idx ON sessions(last_activity);
```

### `cache` & `cache_locks`
Tabel cache Laravel (dipakai jika `CACHE_STORE=database`).

```sql
CREATE TABLE IF NOT EXISTS cache (
    key        VARCHAR(255) PRIMARY KEY,
    value      TEXT         NOT NULL,
    expiration BIGINT       NOT NULL
);

CREATE INDEX IF NOT EXISTS cache_expiration_idx ON cache(expiration);

CREATE TABLE IF NOT EXISTS cache_locks (
    key        VARCHAR(255) PRIMARY KEY,
    owner      VARCHAR(255) NOT NULL,
    expiration BIGINT       NOT NULL
);

CREATE INDEX IF NOT EXISTS cache_locks_expiration_idx ON cache_locks(expiration);
```

### `jobs`, `job_batches`, `failed_jobs`
Tabel queue Laravel.

```sql
CREATE TABLE IF NOT EXISTS jobs (
    id           BIGSERIAL    PRIMARY KEY,
    queue        VARCHAR(255) NOT NULL,
    payload      TEXT         NOT NULL,
    attempts     SMALLINT     NOT NULL,
    reserved_at  INTEGER      DEFAULT NULL,
    available_at INTEGER      NOT NULL,
    created_at   INTEGER      NOT NULL
);

CREATE INDEX IF NOT EXISTS jobs_queue_idx ON jobs(queue);

CREATE TABLE IF NOT EXISTS job_batches (
    id             VARCHAR(255) PRIMARY KEY,
    name           VARCHAR(255) NOT NULL,
    total_jobs     INTEGER      NOT NULL,
    pending_jobs   INTEGER      NOT NULL,
    failed_jobs    INTEGER      NOT NULL,
    failed_job_ids TEXT         NOT NULL,
    options        TEXT         DEFAULT NULL,
    cancelled_at   INTEGER      DEFAULT NULL,
    created_at     INTEGER      NOT NULL,
    finished_at    INTEGER      DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS failed_jobs (
    id         BIGSERIAL    PRIMARY KEY,
    uuid       VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT         NOT NULL,
    queue      TEXT         NOT NULL,
    payload    TEXT         NOT NULL,
    exception  TEXT         NOT NULL,
    failed_at  TIMESTAMPTZ  NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS failed_jobs_conn_queue_failed_idx
    ON failed_jobs(connection, queue, failed_at);
```

---

## 3. Tabel aplikasi utama

### `categories`
Kategori catatan (notes) milik per user.
Setiap user baru otomatis mendapat kategori "Umum" (dibuat via model event di `User::booted()`).

```sql
CREATE TABLE IF NOT EXISTS categories (
    id         BIGSERIAL    PRIMARY KEY,
    user_id    BIGINT       NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    name       VARCHAR(255) NOT NULL,
    color      VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMPTZ  DEFAULT NULL,
    updated_at TIMESTAMPTZ  DEFAULT NULL
);
```

### `schedules`
Jadwal mingguan per user.
`priority` enum: `low` | `medium` | `high`.

```sql
CREATE TABLE IF NOT EXISTS schedules (
    id          BIGSERIAL    PRIMARY KEY,
    user_id     BIGINT       NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title       VARCHAR(255) NOT NULL,
    description TEXT         DEFAULT NULL,
    date        DATE         NOT NULL,
    start_time  TIME         NOT NULL,
    end_time    TIME         DEFAULT NULL,
    priority    VARCHAR(255) NOT NULL DEFAULT 'medium',
    color       VARCHAR(255) DEFAULT NULL,
    created_at  TIMESTAMPTZ  DEFAULT NULL,
    updated_at  TIMESTAMPTZ  DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS schedules_user_date_idx ON schedules(user_id, date);
```

### `todos`
Daftar tugas per user. Bisa terhubung ke `schedules` (opsional).
`completed_at` diisi saat todo ditandai selesai.

```sql
CREATE TABLE IF NOT EXISTS todos (
    id           BIGSERIAL   PRIMARY KEY,
    user_id      BIGINT      NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    schedule_id  BIGINT      DEFAULT NULL REFERENCES schedules(id) ON DELETE SET NULL,
    title        VARCHAR(255) NOT NULL,
    completed    BOOLEAN     NOT NULL DEFAULT FALSE,
    completed_at TIMESTAMPTZ DEFAULT NULL,
    due_date     DATE        DEFAULT NULL,
    created_at   TIMESTAMPTZ DEFAULT NULL,
    updated_at   TIMESTAMPTZ DEFAULT NULL,

    -- Satu schedule hanya bisa punya satu todo yang di-generate
    CONSTRAINT todos_user_schedule_unique UNIQUE (user_id, schedule_id)
);

CREATE INDEX IF NOT EXISTS todos_user_due_date_idx
    ON todos(user_id, due_date);
CREATE INDEX IF NOT EXISTS todos_user_completed_at_idx
    ON todos(user_id, completed, completed_at);
```

### `notes`
Catatan per user, terhubung ke kategori (opsional).

```sql
CREATE TABLE IF NOT EXISTS notes (
    id          BIGSERIAL    PRIMARY KEY,
    user_id     BIGINT       NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    category_id BIGINT       DEFAULT NULL REFERENCES categories(id) ON DELETE SET NULL,
    title       VARCHAR(255) NOT NULL,
    content     TEXT         DEFAULT NULL,
    created_at  TIMESTAMPTZ  DEFAULT NULL,
    updated_at  TIMESTAMPTZ  DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS notes_user_created_at_idx ON notes(user_id, created_at);
```

---

## 4. Tabel blog & kontak

### `posts`
Artikel blog. `author_id` FK ke `users`.
`is_published` + `published_at` menentukan apakah artikel tampil.

```sql
CREATE TABLE IF NOT EXISTS posts (
    id             BIGSERIAL    PRIMARY KEY,
    author_id      BIGINT       NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title          VARCHAR(255) NOT NULL,
    slug           VARCHAR(255) NOT NULL UNIQUE,
    excerpt        VARCHAR(500) DEFAULT NULL,
    content        TEXT         NOT NULL,
    featured_image VARCHAR(255) DEFAULT NULL,
    is_published   BOOLEAN      NOT NULL DEFAULT FALSE,
    published_at   TIMESTAMPTZ  DEFAULT NULL,
    views          INTEGER      NOT NULL DEFAULT 0,
    created_at     TIMESTAMPTZ  DEFAULT NULL,
    updated_at     TIMESTAMPTZ  DEFAULT NULL
);

CREATE INDEX IF NOT EXISTS posts_published_at_idx ON posts(is_published, published_at);
```

### `contacts`
Pesan dari form kontak halaman publik.

```sql
CREATE TABLE IF NOT EXISTS contacts (
    id         BIGSERIAL    PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    subject    VARCHAR(255) NOT NULL,
    message    TEXT         NOT NULL,
    is_read    BOOLEAN      NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ  DEFAULT NULL,
    updated_at TIMESTAMPTZ  DEFAULT NULL
);
```

---

## 5. Insert data — Users

> Password di bawah adalah hash bcrypt dari `password123`.
> Gunakan untuk login testing.

```sql
INSERT INTO users (id, name, email, email_verified_at, password, avatar, google_id, remember_token, created_at, updated_at)
VALUES
    (
        1,
        'Dzakwan',
        'dzakwan@creplann.test',
        NOW(),
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: password123
        NULL,
        NULL,
        NULL,
        NOW(),
        NOW()
    ),
    (
        2,
        'CrePlann Editorial',
        'editorial@creplann.test',
        NOW(),
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',  -- password: password123
        NULL,
        NULL,
        NULL,
        NOW(),
        NOW()
    );

-- Reset sequence agar auto-increment tidak bentrok
SELECT setval('users_id_seq', (SELECT MAX(id) FROM users));
```

---

## 6. Insert data — Categories

> Setiap user wajib punya kategori "Umum" sebagai default.

```sql
INSERT INTO categories (id, user_id, name, color, created_at, updated_at)
VALUES
    -- User 1 (Dzakwan)
    (1, 1, 'Umum',      '#d9d9d9', NOW(), NOW()),
    (2, 1, 'Kerja',     '#5B7FA6', NOW(), NOW()),
    (3, 1, 'Belajar',   '#7E9083', NOW(), NOW()),
    (4, 1, 'Pribadi',   '#E3A93B', NOW(), NOW()),
    (5, 1, 'Ide',       '#E15B3F', NOW(), NOW()),
    -- User 2 (Editorial)
    (6, 2, 'Umum',      '#d9d9d9', NOW(), NOW());

SELECT setval('categories_id_seq', (SELECT MAX(id) FROM categories));
```

---

## 7. Insert data — Schedules

> Jadwal untuk minggu berjalan (tanggal relatif disesuaikan ke minggu saat ini).
> Ganti `CURRENT_DATE` dengan tanggal konkret jika perlu konsistensi.

```sql
INSERT INTO schedules (id, user_id, title, description, date, start_time, end_time, priority, color, created_at, updated_at)
VALUES
    -- Minggu ini — Senin
    (1,  1, 'Review laporan mingguan',
        'Tinjau pencapaian minggu lalu dan buat ringkasan.',
        DATE_TRUNC('week', CURRENT_DATE)::date,
        '07:30', '08:30', 'high',   '#E15B3F', NOW(), NOW()),

    (2,  1, 'Standup meeting tim',
        NULL,
        DATE_TRUNC('week', CURRENT_DATE)::date,
        '09:00', '09:30', 'medium', '#5B7FA6', NOW(), NOW()),

    -- Selasa
    (3,  1, 'Deep work: pengembangan fitur',
        'Fokus coding tanpa gangguan.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '1 day')::date,
        '09:00', '12:00', 'high',   '#E15B3F', NOW(), NOW()),

    (4,  1, 'Sesi belajar: PostgreSQL',
        'Pelajari fitur advanced PostgreSQL untuk proyek.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '1 day')::date,
        '19:00', '20:30', 'medium', '#7E9083', NOW(), NOW()),

    -- Rabu
    (5,  1, 'Meeting klien',
        'Presentasi progress sprint ke klien.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '2 days')::date,
        '13:00', '14:00', 'high',   '#E3A93B', NOW(), NOW()),

    -- Kamis
    (6,  1, 'Code review & refactoring',
        'Review PR tim dan perbaiki technical debt.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '3 days')::date,
        '10:00', '12:00', 'medium', '#5B7FA6', NOW(), NOW()),

    (7,  1, 'Olahraga — lari sore',
        NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '3 days')::date,
        '17:00', '18:00', 'low',    '#7E9083', NOW(), NOW()),

    -- Jumat
    (8,  1, 'Weekly review & planning',
        'Evaluasi minggu ini dan rencanakan minggu depan.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '4 days')::date,
        '16:00', '17:00', 'high',   '#E15B3F', NOW(), NOW()),

    -- Sabtu
    (9,  1, 'Side project: CrePlann',
        'Kerjakan fitur baru CrePlann.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '5 days')::date,
        '10:00', '13:00', 'medium', '#E3A93B', NOW(), NOW()),

    -- Minggu
    (10, 1, 'Istirahat & recharge',
        'Tidak ada pekerjaan. Baca buku atau jalan-jalan.',
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '6 days')::date,
        '08:00', '22:00', 'low',    '#7E9083', NOW(), NOW());

SELECT setval('schedules_id_seq', (SELECT MAX(id) FROM schedules));
```

---

## 8. Insert data — Todos

> `schedule_id` yang terisi = todo di-generate dari schedule.
> `schedule_id = NULL` = todo mandiri.

```sql
INSERT INTO todos (id, user_id, schedule_id, title, completed, completed_at, due_date, created_at, updated_at)
VALUES
    -- Terhubung ke schedule (generated)
    (1,  1, 1,    'Selesaikan review laporan mingguan',
        TRUE,  NOW() - INTERVAL '1 hour',
        DATE_TRUNC('week', CURRENT_DATE)::date,                           NOW(), NOW()),
    (2,  1, 3,    'Kerjakan fitur baru (deep work session)',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '1 day')::date,     NOW(), NOW()),
    (3,  1, 5,    'Siapkan materi presentasi klien',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '2 days')::date,    NOW(), NOW()),
    (4,  1, 8,    'Lakukan weekly review & planning',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '4 days')::date,    NOW(), NOW()),

    -- Todo mandiri (schedule_id NULL — unique constraint pakai partial index)
    (5,  1, NULL, 'Balas email dari klien A',
        TRUE,  NOW() - INTERVAL '2 hours',
        CURRENT_DATE,                                                     NOW(), NOW()),
    (6,  1, NULL, 'Update dokumentasi API',
        FALSE, NULL,
        CURRENT_DATE,                                                     NOW(), NOW()),
    (7,  1, NULL, 'Riset library untuk autentikasi baru',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '2 days')::date,    NOW(), NOW()),
    (8,  1, NULL, 'Beli buku: Atomic Habits',
        TRUE,  NOW() - INTERVAL '1 day',
        CURRENT_DATE - INTERVAL '1 day',                                  NOW(), NOW()),
    (9,  1, NULL, 'Setup environment staging server',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '3 days')::date,    NOW(), NOW()),
    (10, 1, NULL, 'Tulis artikel teknis untuk blog',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '5 days')::date,    NOW(), NOW()),
    (11, 1, NULL, 'Kirim invoice bulan ini',
        TRUE,  NOW() - INTERVAL '3 days',
        CURRENT_DATE - INTERVAL '3 days',                                 NOW(), NOW()),
    (12, 1, NULL, 'Review desain UI halaman baru',
        FALSE, NULL,
        (DATE_TRUNC('week', CURRENT_DATE) + INTERVAL '1 day')::date,     NOW(), NOW());

SELECT setval('todos_id_seq', (SELECT MAX(id) FROM todos));
```

---

## 9. Insert data — Notes

```sql
INSERT INTO notes (id, user_id, category_id, title, content, created_at, updated_at)
VALUES
    -- Kategori: Kerja (id=2)
    (1,  1, 2,
        'Arsitektur microservice: catatan riset',
        E'Pertimbangan utama:\n- Service discovery: gunakan Consul atau Kubernetes DNS\n- Communication: REST untuk sync, event bus (Kafka/RabbitMQ) untuk async\n- Database per service — hindari shared DB\n- Circuit breaker pattern (Hystrix / Resilience4j)\n\nReferensi bagus: "Building Microservices" oleh Sam Newman.',
        NOW() - INTERVAL '2 days', NOW() - INTERVAL '2 days'),

    (2,  1, 2,
        'Feedback sprint review — catatan penting',
        E'Poin yang perlu ditindaklanjuti:\n1. Loading time halaman dashboard masih >3s → optimasi query N+1\n2. Filter todo belum berfungsi di mobile\n3. Klien minta fitur export ke PDF — masukkan backlog sprint 4\n4. Tampilan error 422 kurang informatif bagi user',
        NOW() - INTERVAL '1 day', NOW() - INTERVAL '1 day'),

    (3,  1, 2,
        'Stack keputusan: Supabase vs PlanetScale',
        E'Kenapa memilih Supabase:\n✓ PostgreSQL (bukan MySQL) — lebih powerful untuk query kompleks\n✓ Built-in auth jika butuh di masa depan\n✓ Realtime capabilities\n✓ Free tier generous (500MB database, 2GB file storage)\n✓ Dashboard SQL editor memudahkan debugging\n\nTrade-off:\n- Cold start jika paused (free tier)\n- Region terbatas dibanding PlanetScale',
        NOW() - INTERVAL '5 hours', NOW() - INTERVAL '5 hours'),

    -- Kategori: Belajar (id=3)
    (4,  1, 3,
        'Rangkuman: PostgreSQL indexing strategy',
        E'Jenis index PostgreSQL yang perlu dipahami:\n\n1. B-Tree (default) — cocok untuk =, <, >, BETWEEN, LIKE prefix\n2. Hash — hanya untuk = comparison, lebih cepat dari B-tree untuk equality\n3. GIN — untuk full-text search dan array/JSONB\n4. GiST — untuk geometric data dan range types\n\nTips:\n- Composite index: kolom paling selective di kiri\n- Partial index: sangat efisien untuk query dengan WHERE statis\n- Gunakan EXPLAIN ANALYZE untuk verifikasi index dipakai',
        NOW() - INTERVAL '3 days', NOW() - INTERVAL '3 days'),

    (5,  1, 3,
        'Buku yang sedang dibaca: Atomic Habits',
        E'Poin kunci dari James Clear:\n\n"You do not rise to the level of your goals, you fall to the level of your systems."\n\nFramework 4 hukum:\n1. Make it obvious (cue)\n2. Make it attractive (craving)\n3. Make it easy (response)\n4. Make it satisfying (reward)\n\nHabit stacking: "After [CURRENT HABIT], I will [NEW HABIT]"\n→ After morning coffee, I will open CrePlann and plan the day.',
        NOW() - INTERVAL '6 hours', NOW() - INTERVAL '6 hours'),

    -- Kategori: Ide (id=5)
    (6,  1, 5,
        'Ide fitur: recurring schedule',
        E'Fitur yang sering diminta pengguna:\n\nRecurring schedule = jadwal yang berulang otomatis.\n\nImplementasi kemungkinan:\n- Tambah kolom recurrence_rule (RRULE format / cron-like)\n- Tambah kolom recurrence_end_date\n- Background job (scheduled command) yang auto-create instance per minggu\n- UI: checkbox "Ulangi setiap minggu" di form schedule\n\nPriority: medium — masukkan roadmap Q4.',
        NOW() - INTERVAL '4 hours', NOW() - INTERVAL '4 hours'),

    (7,  1, 5,
        'Ide: gamifikasi progress mingguan',
        E'Konsep: tambahkan streak counter dan achievement badge.\n\nSkenario:\n- Streak: berapa minggu berturut-turut user menyelesaikan >80% todo\n- Badge: "3 minggu berturut-turut", "100 todo selesai", "Planner sejati"\n- Weekly score: (todos selesai / total todos) × 100\n\nTujuan: meningkatkan retention dan engagement.\nRisiko: jangan sampai menambah anxiety — harus opt-in.',
        NOW() - INTERVAL '2 hours', NOW() - INTERVAL '2 hours'),

    -- Kategori: Pribadi (id=4)
    (8,  1, 4,
        'Target bulan ini',
        E'Personal OKR — Agustus 2026:\n\nObjective: Bangun kebiasaan lebih terstruktur\n\nKey Results:\n☐ Selesaikan weekly review setiap Jumat (4x)\n☐ Olahraga minimal 3x per minggu\n☐ Baca 1 buku sampai selesai\n☐ Tidur sebelum jam 23:00 setiap hari kerja\n☐ Tidak buka sosmed sebelum jam 9 pagi\n\nProgress: 40%',
        NOW() - INTERVAL '1 week', NOW() - INTERVAL '1 day'),

    -- Kategori: Umum (id=1)
    (9,  1, 1,
        'Link berguna untuk proyek CrePlann',
        E'Resources:\n- Supabase docs: https://supabase.com/docs\n- Laravel PostgreSQL: https://laravel.com/docs/database\n- Tabler Icons: https://tabler.io/icons\n- IBM Plex fonts: https://fonts.google.com/specimen/IBM+Plex+Sans\n- Big Shoulders Display: https://fonts.google.com/specimen/Big+Shoulders+Display\n\nDesign references:\n- Linear app (clean dark UI)\n- Notion (structured content)\n- Things 3 (todo UX)',
        NOW() - INTERVAL '2 weeks', NOW() - INTERVAL '2 weeks'),

    (10, 1, 1,
        'Password & akses penting (JANGAN disimpan di sini sebenarnya!)',
        E'Catatan untuk diri sendiri:\nIni contoh note saja — jangan pernah simpan password di notes aplikasi.\nGunakan password manager seperti Bitwarden atau 1Password.\n\nTool yang direkomendasikan:\n- Bitwarden (open source, gratis)\n- 1Password (berbayar, fitur tim)\n- KeePass (offline, self-hosted)',
        NOW() - INTERVAL '10 days', NOW() - INTERVAL '10 days');

SELECT setval('notes_id_seq', (SELECT MAX(id) FROM notes));
```

---

## 10. Insert data — Posts (Blog)

> 12 artikel published + 2 draft tentang produktivitas.

```sql
INSERT INTO posts (id, author_id, title, slug, excerpt, content, featured_image, is_published, published_at, views, created_at, updated_at)
VALUES
    (1, 2,
        'Cara Memulai Minggu yang Produktif dengan Weekly Planning',
        'cara-memulai-minggu-yang-produktif-dengan-weekly-planning',
        'Banyak orang kehilangan fokus di awal minggu karena tidak punya gambaran jelas tentang apa yang harus dikerjakan. Weekly planning adalah jawabannya.',
        E'<p>Memulai minggu dengan perencanaan yang matang adalah salah satu kebiasaan yang membedakan orang produktif dari yang tidak. Ketika Anda duduk sejenak setiap Senin pagi dan menuliskan apa yang ingin dicapai minggu ini, Anda memberikan arah yang jelas bagi pikiran dan energi Anda.</p>\n\n<h2>Mengapa Weekly Planning Penting?</h2>\n<p>Tanpa perencanaan, kita cenderung bereaksi terhadap hal-hal yang datang secara acak — email masuk, permintaan mendadak, notifikasi yang tidak ada habisnya. Weekly planning membalik pola ini: Anda memilih secara proaktif apa yang penting, bukan sekadar merespons apa yang mendesak.</p>\n\n<h2>Langkah Memulai Weekly Planning di CrePlann</h2>\n<p>Buka halaman Schedule, pilih rentang minggu yang ingin Anda rencanakan, lalu mulai mengisi jadwal satu per satu. Pastikan setiap jadwal memiliki judul yang jelas, waktu mulai dan selesai, serta prioritas yang realistis.</p>\n\n<h2>Tips Agar Weekly Planning Berhasil</h2>\n<ul>\n<li>Lakukan review singkat setiap Jumat sore untuk mengevaluasi minggu yang berjalan.</li>\n<li>Jangan isi jadwal terlalu penuh — sisakan buffer time untuk hal tak terduga.</li>\n<li>Prioritaskan maksimal 3 hal penting per hari agar fokus tetap terjaga.</li>\n</ul>',
        NULL, TRUE, NOW() - INTERVAL '45 days', 342, NOW() - INTERVAL '45 days', NOW()),

    (2, 2,
        'Teknik Time Blocking: Jadwalkan Waktu, Bukan Tugas',
        'teknik-time-blocking-jadwalkan-waktu-bukan-tugas',
        'Time blocking bukan tentang mengisi kalender sampai penuh. Ini tentang mengalokasikan waktu secara intentional untuk pekerjaan yang benar-benar penting bagi Anda.',
        E'<p>Time blocking adalah teknik manajemen waktu di mana Anda mengalokasikan blok waktu spesifik untuk jenis pekerjaan tertentu. Berbeda dengan to-do list biasa yang hanya mencatat apa yang perlu dilakukan, time blocking menentukan <em>kapan</em> sesuatu akan dikerjakan.</p>\n\n<h2>Perbedaan Time Blocking dan To-Do List</h2>\n<p>To-do list memberi tahu Anda apa yang harus dilakukan. Time blocking memaksa Anda untuk jujur tentang berapa lama sesuatu benar-benar membutuhkan waktu. Ketika Anda memblokir 2 jam untuk menulis laporan, Anda berkomitmen pada waktu itu.</p>\n\n<h2>Cara Menerapkan Time Blocking di CrePlann</h2>\n<p>Gunakan fitur Schedule untuk membuat blok waktu. Beri warna berbeda untuk kategori pekerjaan yang berbeda: merah untuk pekerjaan deep work, hijau untuk meeting, kuning untuk administrative tasks.</p>',
        NULL, TRUE, NOW() - INTERVAL '38 days', 218, NOW() - INTERVAL '38 days', NOW()),

    (3, 2,
        'Mengapa To-Do List Anda Selalu Tidak Selesai (dan Cara Memperbaikinya)',
        'mengapa-todo-list-anda-selalu-tidak-selesai-dan-cara-memperbaikinya',
        'Jika to-do list Anda terus bertambah panjang tanpa banyak yang tercentang, masalahnya bukan pada kurangnya disiplin — melainkan pada cara Anda menulis daftarnya.',
        E'<p>Hampir semua orang pernah mengalaminya: to-do list yang menumpuk, item yang sama muncul berhari-hari berturut-turut, dan perasaan bersalah yang terus menghantui.</p>\n\n<h2>Kesalahan Umum dalam Membuat To-Do List</h2>\n<p><strong>1. Item terlalu besar dan ambigu.</strong> "Selesaikan proyek" bukanlah to-do yang actionable. Pecah menjadi langkah-langkah kecil yang konkret.</p>\n<p><strong>2. Tidak ada due date.</strong> Tanpa tenggat waktu, otak kita secara alami menunda hal yang tidak mendesak.</p>\n<p><strong>3. Mencampur semua konteks.</strong> Item pekerjaan, belanja, dan urusan pribadi dalam satu daftar menciptakan cognitive load yang tidak perlu.</p>\n\n<h2>Cara Memperbaikinya dengan CrePlann</h2>\n<p>Saat membuat todo baru, selalu isi due date. Gunakan filter tab di halaman Todo untuk melihat todo berdasarkan status.</p>',
        NULL, TRUE, NOW() - INTERVAL '32 days', 567, NOW() - INTERVAL '32 days', NOW()),

    (4, 2,
        'Kekuatan Catatan Harian untuk Produktivitas Jangka Panjang',
        'kekuatan-catatan-harian-untuk-produktivitas-jangka-panjang',
        'Mencatat bukan sekadar menyimpan informasi. Menulis secara reguler membantu otak memproses pengalaman, mempertajam pemikiran, dan menemukan pola yang tidak terlihat.',
        E'<p>Banyak orang sukses dunia — dari Marcus Aurelius hingga Richard Feynman — memiliki kebiasaan mencatat yang konsisten. Bukan karena mereka ingin meninggalkan warisan tulisan, melainkan karena menulis membantu mereka berpikir lebih jernih.</p>\n\n<h2>Manfaat Mencatat Secara Reguler</h2>\n<p>Ketika Anda menuliskan pikiran, Anda memaksa otak untuk mengorganisasikannya menjadi kalimat yang koheren. Proses ini sering kali mengungkapkan insight yang tidak akan muncul jika Anda hanya berpikir tanpa menuliskannya.</p>\n\n<h2>Jenis Catatan yang Berguna</h2>\n<ul>\n<li><strong>Daily log:</strong> Apa yang dilakukan hari ini, apa yang dipelajari.</li>\n<li><strong>Capture notes:</strong> Ide yang muncul tiba-tiba sebelum terlupakan.</li>\n<li><strong>Reference notes:</strong> Ringkasan buku atau materi pembelajaran.</li>\n<li><strong>Project notes:</strong> Semua hal terkait proyek dalam satu tempat.</li>\n</ul>',
        NULL, TRUE, NOW() - INTERVAL '28 days', 189, NOW() - INTERVAL '28 days', NOW()),

    (5, 2,
        'Cara Membangun Sistem Produktivitas yang Tidak Bergantung pada Motivasi',
        'cara-membangun-sistem-produktivitas-yang-tidak-bergantung-pada-motivasi',
        'Motivasi datang dan pergi. Sistem yang baik bekerja bahkan di hari-hari ketika Anda tidak merasa ingin melakukan apa pun.',
        E'<p>Salah satu mitos terbesar tentang produktivitas adalah bahwa orang-orang produktif selalu termotivasi dan bersemangat. Kenyataannya, mereka juga mengalami hari-hari berat. Perbedaannya: mereka memiliki sistem yang berjalan bahkan tanpa motivasi.</p>\n\n<h2>Sistem vs. Motivasi</h2>\n<p>Motivasi adalah emosi — tidak stabil dan tidak bisa diandalkan sebagai fondasi produktivitas jangka panjang. Sistem adalah serangkaian kebiasaan dan ritual yang berjalan secara otomatis.</p>\n\n<h2>Elemen Sistem Produktivitas yang Efektif</h2>\n<p><strong>Ritual pagi:</strong> Luangkan 15 menit setiap pagi untuk melihat jadwal hari ini dan menentukan 1-3 hal terpenting.</p>\n<p><strong>Weekly review:</strong> Setiap Jumat, evaluasi minggu yang berjalan dan rencanakan minggu depan.</p>',
        NULL, TRUE, NOW() - INTERVAL '24 days', 445, NOW() - INTERVAL '24 days', NOW()),

    (6, 2,
        'Deep Work: Cara Bekerja dengan Fokus Penuh di Era Distraksi',
        'deep-work-cara-bekerja-dengan-fokus-penuh-di-era-distraksi',
        'Di era notifikasi non-stop, kemampuan untuk bekerja dengan fokus penuh selama beberapa jam menjadi keahlian yang langka — dan sangat berharga.',
        E'<p>Cal Newport mendefinisikan deep work sebagai aktivitas profesional yang dilakukan dalam kondisi bebas distraksi dengan konsentrasi penuh, mendorong kemampuan kognitif Anda ke batas maksimal.</p>\n\n<h2>Mengapa Deep Work Semakin Langka?</h2>\n<p>Open plan office, budaya selalu-online, dan ekspektasi respons email cepat telah menciptakan lingkungan yang sangat tidak ramah untuk fokus mendalam.</p>\n\n<h2>Membangun Ritual Deep Work</h2>\n<p>Tentukan waktu spesifik untuk deep work — idealnya di pagi hari ketika energi masih segar. Blokir waktu ini di Schedule CrePlann Anda sebagai "Protected Time" dengan prioritas tinggi.</p>\n\n<h2>Durasi Optimal</h2>\n<p>Mulai dengan sesi 90 menit. Setelah istirahat singkat 15-30 menit, Anda bisa memulai sesi berikutnya jika energi masih ada.</p>',
        NULL, TRUE, NOW() - INTERVAL '20 days', 621, NOW() - INTERVAL '20 days', NOW()),

    (7, 2,
        'Kenapa Weekly Review adalah Kebiasaan Produktivitas Paling Underrated',
        'kenapa-weekly-review-adalah-kebiasaan-produktivitas-paling-underrated',
        'Semua orang bicara tentang morning routine. Tapi sedikit yang konsisten melakukan weekly review — padahal inilah yang benar-benar menggerakkan jarum produktivitas jangka panjang.',
        E'<p>Weekly review adalah praktik yang dipopulerkan oleh David Allen dalam metodologi Getting Things Done (GTD). Konsepnya sederhana: satu kali seminggu, Anda duduk dan meninjau semua yang sudah dilakukan dan yang perlu direncanakan.</p>\n\n<h2>Apa yang Dilakukan dalam Weekly Review?</h2>\n<ol>\n<li><strong>Clear the inbox:</strong> Proses semua hal yang masuk selama seminggu.</li>\n<li><strong>Review in progress:</strong> Cek semua todo dan jadwal yang masih aktif.</li>\n<li><strong>Reflect:</strong> Apa yang berhasil? Apa yang tidak?</li>\n<li><strong>Plan ahead:</strong> Preview minggu depan.</li>\n</ol>\n\n<h2>Berapa Lama Seharusnya?</h2>\n<p>Weekly review yang baik membutuhkan waktu 30-60 menit. Jadwalkan setiap Jumat sore atau Minggu malam.</p>',
        NULL, TRUE, NOW() - INTERVAL '16 days', 298, NOW() - INTERVAL '16 days', NOW()),

    (8, 2,
        'Inbox Zero: Strategi Mengelola Email agar Tidak Memenuhi Pikiran',
        'inbox-zero-strategi-mengelola-email-agar-tidak-memenuhi-pikiran',
        'Inbox yang penuh bukan hanya masalah estetika — ini adalah beban kognitif yang terus-menerus menguras energi mental Anda sepanjang hari.',
        E'<p>Inbox zero bukan berarti email Anda selalu kosong. Ini adalah filosofi bahwa inbox bukanlah tempat penyimpanan permanen — setiap email perlu diproses.</p>\n\n<h2>Empat Tindakan untuk Setiap Email</h2>\n<ul>\n<li><strong>Delete/Archive:</strong> Jika tidak ada tindakan yang diperlukan.</li>\n<li><strong>Reply:</strong> Jika bisa dibalas dalam 2 menit atau kurang, balas sekarang.</li>\n<li><strong>Defer:</strong> Jika membutuhkan lebih dari 2 menit, jadwalkan waktunya.</li>\n<li><strong>Delegate:</strong> Jika bisa dikerjakan orang lain, teruskan.</li>\n</ul>\n\n<h2>Mengintegrasikan Email dengan CrePlann</h2>\n<p>Ketika email membutuhkan tindak lanjut, buat todo di CrePlann dengan due date yang realistis, lalu archive emailnya.</p>',
        NULL, TRUE, NOW() - INTERVAL '12 days', 176, NOW() - INTERVAL '12 days', NOW()),

    (9, 2,
        'Belajar Mengatakan Tidak: Kunci Menjaga Fokus pada yang Penting',
        'belajar-mengatakan-tidak-kunci-menjaga-fokus-pada-yang-penting',
        'Setiap kali Anda mengatakan ya pada sesuatu, Anda secara implisit mengatakan tidak pada hal lain. Belajar memilih dengan sadar adalah salah satu keterampilan produktivitas paling penting.',
        E'<p>Warren Buffett pernah berkata: "Perbedaan antara orang sukses dan orang sangat sukses adalah orang sangat sukses mengatakan tidak pada hampir semua hal."</p>\n\n<h2>The Paradox of Yes</h2>\n<p>Ketika Anda terus menerima permintaan tanpa selektif, Anda akhirnya menyebar energi ke terlalu banyak arah. Hasilnya: semua hal dikerjakan setengah-setengah.</p>\n\n<h2>Cara Mengevaluasi Permintaan</h2>\n<ul>\n<li>Apakah ini selaras dengan prioritas utama saya minggu ini?</li>\n<li>Jika kalender saya sudah penuh, apakah saya masih ingin melakukan ini?</li>\n<li>Apakah ini menggerakkan saya menuju tujuan jangka panjang?</li>\n</ul>',
        NULL, TRUE, NOW() - INTERVAL '9 days', 387, NOW() - INTERVAL '9 days', NOW()),

    (10, 2,
        'Pomodoro Technique: Bekerja dalam Sprint untuk Produktivitas Maksimal',
        'pomodoro-technique-bekerja-dalam-sprint-untuk-produktivitas-maksimal',
        'Otak manusia tidak dirancang untuk fokus selama berjam-jam tanpa henti. Teknik Pomodoro memanfaatkan ritme alami otak untuk memaksimalkan output.',
        E'<p>Teknik Pomodoro membagi pekerjaan menjadi interval 25 menit yang dipisahkan oleh istirahat pendek 5 menit.</p>\n\n<h2>Cara Kerja Teknik Pomodoro</h2>\n<ol>\n<li>Pilih tugas yang akan dikerjakan.</li>\n<li>Atur timer selama 25 menit.</li>\n<li>Kerjakan tugas dengan fokus penuh hingga timer berbunyi.</li>\n<li>Istirahat 5 menit.</li>\n<li>Setelah 4 pomodoro, ambil istirahat lebih panjang: 15-30 menit.</li>\n</ol>\n\n<h2>Mengapa Ini Efektif?</h2>\n<p>Interval yang terbatas menciptakan urgensi artifisial yang membantu otak masuk ke mode fokus.</p>',
        NULL, TRUE, NOW() - INTERVAL '6 days', 523, NOW() - INTERVAL '6 days', NOW()),

    (11, 2,
        'Mengelola Energi, Bukan Hanya Waktu: Pendekatan yang Lebih Manusiawi',
        'mengelola-energi-bukan-hanya-waktu-pendekatan-yang-lebih-manusiawi',
        'Manajemen waktu mengasumsikan semua jam adalah setara. Tapi jam 09.00 ketika Anda segar berbeda jauh dengan jam 15.00 setelah meeting panjang.',
        E'<p>Tony Schwartz berpendapat bahwa fondasi produktivitas bukanlah waktu, melainkan energi. Waktu adalah sumber daya yang tetap — kita semua mendapat 24 jam. Tapi energi bisa dikelola dan dimaksimalkan.</p>\n\n<h2>Empat Dimensi Energi</h2>\n<p><strong>Fisik:</strong> Tidur cukup, olahraga teratur, dan nutrisi yang baik adalah investasi produktivitas.</p>\n<p><strong>Emosional:</strong> Kemampuan untuk memilih respons emosional yang konstruktif.</p>\n<p><strong>Mental:</strong> Kemampuan untuk fokus dan membuat keputusan. Kapasitas ini terbatas dan berkurang sepanjang hari.</p>\n<p><strong>Purposeful:</strong> Terhubung dengan nilai dan tujuan yang lebih besar.</p>',
        NULL, TRUE, NOW() - INTERVAL '3 days', 241, NOW() - INTERVAL '3 days', NOW()),

    (12, 2,
        'Dari Overwhelm ke Clarity: Cara Memproses To-Do List yang Menggunung',
        'dari-overwhelm-ke-clarity-cara-memproses-todo-list-yang-menggunung',
        'Saat to-do list tumbuh lebih cepat dari yang bisa diselesaikan, solusinya bukan bekerja lebih keras — tapi memproses dengan lebih cerdas.',
        E'<p>Ada momen dalam hidup setiap pekerja produktif ketika daftar tugas terasa seperti gunung yang tidak bisa didaki. Semuanya berdesakan dalam pikiran dan menciptakan perasaan overwhelm.</p>\n\n<h2>Langkah Pertama: Keluarkan Semua dari Kepala</h2>\n<p>Tuliskan semua hal yang ada di pikiran Anda — tanpa menyaring, tanpa menilai. Proses ini disebut "mind sweep".</p>\n\n<h2>Proses dengan Pertanyaan Sederhana</h2>\n<ul>\n<li><strong>Apakah ini actionable?</strong> Jika tidak, hapus atau arsipkan.</li>\n<li><strong>Bisa diselesaikan dalam 2 menit?</strong> Kerjakan sekarang.</li>\n<li><strong>Bisa didelegasikan?</strong> Delegasikan.</li>\n<li><strong>Kapan harus dilakukan?</strong> Tambahkan sebagai todo dengan due date.</li>\n</ul>',
        NULL, TRUE, NOW() - INTERVAL '1 day', 89, NOW() - INTERVAL '1 day', NOW()),

    -- 2 draft artikel — tidak muncul di frontend
    (13, 2,
        'Panduan Lengkap Menggunakan CrePlann untuk Pelajar',
        'panduan-lengkap-menggunakan-creplann-untuk-pelajar',
        'Bagaimana mahasiswa dan pelajar bisa memaksimalkan CrePlann untuk mengelola tugas kuliah, jadwal ujian, dan catatan belajar.',
        E'<p>Draft artikel — belum dipublish.</p>',
        NULL, FALSE, NULL, 0, NOW(), NOW()),

    (14, 2,
        'Integrasi CrePlann dengan Google Calendar: Tips dan Trik',
        'integrasi-creplann-dengan-google-calendar-tips-dan-trik',
        'Cara sinkronisasi jadwal CrePlann dengan Google Calendar agar tidak perlu input dua kali.',
        E'<p>Draft artikel — belum dipublish.</p>',
        NULL, FALSE, NULL, 0, NOW(), NOW());

SELECT setval('posts_id_seq', (SELECT MAX(id) FROM posts));
```

---

## Ringkasan tabel & relasi

```
users
  ├── categories      (user_id → users.id, CASCADE)
  ├── schedules       (user_id → users.id, CASCADE)
  ├── todos           (user_id → users.id, CASCADE)
  │     └── schedules (schedule_id → schedules.id, SET NULL)
  ├── notes           (user_id → users.id, CASCADE)
  │     └── categories (category_id → categories.id, SET NULL)
  └── posts           (author_id → users.id, CASCADE)

contacts              (tidak ada FK — standalone)
sessions              (user_id → users.id, SET NULL)
```

| Tabel                  | Baris data | Keterangan                        |
|------------------------|:----------:|-----------------------------------|
| `users`                |     2      | 1 user utama + 1 editorial        |
| `categories`           |     6      | 5 kategori user 1 + 1 user 2      |
| `schedules`            |    10      | Jadwal 1 minggu penuh             |
| `todos`                |    12      | 4 generated + 8 mandiri           |
| `notes`                |    10      | Berbagai kategori                 |
| `posts`                |    14      | 12 published + 2 draft            |
| `contacts`             |     0      | Kosong — diisi via form           |

---

## Catatan untuk Supabase

1. **Jalankan SQL di atas melalui SQL Editor** — bukan Table Editor (lebih reliable untuk DDL)
2. **Urutan eksekusi wajib dijaga**: `users` → `categories` → `schedules` → `todos` → `notes` → `posts`
3. **Row Level Security (RLS)**: Supabase mengaktifkan RLS secara default. Karena Laravel terhubung via koneksi PostgreSQL langsung (bukan Supabase JS client), RLS **tidak berpengaruh** pada query Laravel — Laravel bypass RLS karena menggunakan kredensial `postgres` (superuser).
4. **Sequences**: Setiap blok `INSERT` diikuti `setval()` untuk mereset auto-increment agar tidak conflict saat data baru ditambah via aplikasi.

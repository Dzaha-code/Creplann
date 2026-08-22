<x-public-layout>
    <x-slot name="metaTitle">Pusat Bantuan — CrePlann</x-slot>
    <x-slot name="metaDescription">Panduan lengkap menggunakan Schedule, Todo, dan Notes di CrePlann. Temukan jawaban atas pertanyaan umum seputar fitur aplikasi.</x-slot>

    <x-slot name="header">
        <div class="hp-page-header">
            <div class="hp-header-inner">
                <div class="hp-header-icon" aria-hidden="true">
                    <i class="ti ti-help-hexagon"></i>
                </div>
                <div>
                    <h1 class="hp-page-title">Pusat Bantuan</h1>
                    <p class="hp-page-sub">Panduan lengkap menggunakan CrePlann</p>
                </div>
            </div>
        </div>
    </x-slot>

    @push('head')
        @vite(['resources/css/pages/help.css', 'resources/js/pages/help.js'])
    @endpush

    <div class="hp-wrap">

        {{-- ── Panduan fitur ───────────────────────────────────── --}}
        <section aria-labelledby="guideHeading">
            <h2 id="guideHeading" class="hp-section-title">
                <i class="ti ti-book" aria-hidden="true"></i>
                Panduan Fitur
            </h2>

            <div class="hp-guide-grid">

                {{-- Schedule --}}
                <div class="hp-guide-card">
                    <div class="hp-guide-card-head">
                        <div class="hp-guide-icon hp-guide-icon--schedule" aria-hidden="true">
                            <i class="ti ti-calendar"></i>
                        </div>
                        <h3 class="hp-guide-title">Schedule</h3>
                        <p class="hp-guide-sub">Kelola jadwal mingguan Anda</p>
                    </div>

                    <ul class="hp-guide-list" role="list">
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-plus"></i>
                            </span>
                            <div>
                                <strong>Tambah jadwal baru</strong>
                                <p>Klik tombol "+ Tambah Jadwal" di kanan atas, isi judul, tanggal, waktu mulai/selesai, prioritas, dan warna. Klik Simpan.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-arrows-left-right"></i>
                            </span>
                            <div>
                                <strong>Navigasi mingguan</strong>
                                <p>Gunakan tombol panah kiri/kanan untuk berpindah minggu. Klik "Hari ini" untuk kembali ke minggu berjalan.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-checkbox"></i>
                            </span>
                            <div>
                                <strong>Generate todo dari jadwal</strong>
                                <p>Buka detail jadwal, lalu klik "Generate Todo". Sistem akan otomatis membuat satu item todo yang terhubung dengan jadwal tersebut.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-pencil"></i>
                            </span>
                            <div>
                                <strong>Edit atau hapus jadwal</strong>
                                <p>Klik ikon pensil untuk mengedit, atau ikon tempat sampah untuk menghapus jadwal. Perubahan judul akan otomatis menyinkronkan todo yang terhubung.</p>
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('schedule.index') }}" class="hp-guide-cta">
                        <i class="ti ti-calendar" aria-hidden="true"></i>
                        Buka Schedule
                    </a>
                </div>

                {{-- Todo --}}
                <div class="hp-guide-card">
                    <div class="hp-guide-card-head">
                        <div class="hp-guide-icon hp-guide-icon--todo" aria-hidden="true">
                            <i class="ti ti-checkbox"></i>
                        </div>
                        <h3 class="hp-guide-title">Todo</h3>
                        <p class="hp-guide-sub">Pantau dan selesaikan tugas Anda</p>
                    </div>

                    <ul class="hp-guide-list" role="list">
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-circle-check"></i>
                            </span>
                            <div>
                                <strong>Tandai sebagai selesai</strong>
                                <p>Centang kotak di sebelah kiri item todo. Status akan langsung diperbarui dan ditandai dengan garis coret. Centang kembali untuk membatalkan.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-filter"></i>
                            </span>
                            <div>
                                <strong>Filter todo</strong>
                                <p>Gunakan tab "Semua", "Aktif", dan "Selesai" di bagian atas untuk menyaring tampilan. Semua todo yang belum selesai ditampilkan di tab Aktif.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-archive"></i>
                            </span>
                            <div>
                                <strong>Arsip bulanan</strong>
                                <p>Todo yang sudah selesai otomatis dikelompokkan per bulan. Gulir ke bawah di halaman Todo untuk melihat riwayat arsip.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-calendar-due"></i>
                            </span>
                            <div>
                                <strong>Due date</strong>
                                <p>Saat menambah todo baru, selalu isi due date agar todo muncul di tampilan weekly grid Schedule dan mudah dipantau progresnya.</p>
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('todo.index') }}" class="hp-guide-cta">
                        <i class="ti ti-checkbox" aria-hidden="true"></i>
                        Buka Todo
                    </a>
                </div>

                {{-- Notes --}}
                <div class="hp-guide-card">
                    <div class="hp-guide-card-head">
                        <div class="hp-guide-icon hp-guide-icon--notes" aria-hidden="true">
                            <i class="ti ti-notes"></i>
                        </div>
                        <h3 class="hp-guide-title">Notes</h3>
                        <p class="hp-guide-sub">Catat ide dan informasi penting</p>
                    </div>

                    <ul class="hp-guide-list" role="list">
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-plus"></i>
                            </span>
                            <div>
                                <strong>Buat catatan baru</strong>
                                <p>Klik "+ Catatan Baru", isi judul dan isi catatan, pilih kategori, lalu simpan. Catatan langsung muncul di grid halaman Notes.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-tags"></i>
                            </span>
                            <div>
                                <strong>Kelola kategori</strong>
                                <p>Klik "Kelola Kategori" untuk menambah, mengganti nama, atau menghapus kategori. Kategori "Umum" adalah default dan tidak bisa dihapus.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-search"></i>
                            </span>
                            <div>
                                <strong>Filter & cari catatan</strong>
                                <p>Klik chip kategori di bagian atas untuk memfilter catatan berdasarkan kategori tertentu. Semua kategori ditampilkan di baris filter.</p>
                            </div>
                        </li>
                        <li class="hp-guide-item">
                            <span class="hp-guide-item-icon" aria-hidden="true">
                                <i class="ti ti-eye"></i>
                            </span>
                            <div>
                                <strong>Preview catatan</strong>
                                <p>Klik kartu catatan mana saja untuk membuka preview lengkap di panel samping. Dari sini Anda bisa langsung edit atau hapus.</p>
                            </div>
                        </li>
                    </ul>

                    <a href="{{ route('note.index') }}" class="hp-guide-cta">
                        <i class="ti ti-notes" aria-hidden="true"></i>
                        Buka Notes
                    </a>
                </div>

            </div>
        </section>

        {{-- ── FAQ ─────────────────────────────────────────────── --}}
        <section class="hp-faq-section" aria-labelledby="faqHeading">
            <h2 id="faqHeading" class="hp-section-title">
                <i class="ti ti-message-question" aria-hidden="true"></i>
                Pertanyaan yang Sering Diajukan
            </h2>

            <div class="hp-faq-list" id="faqList">

                <div class="hp-faq-item" data-faq>
                    <button class="hp-faq-trigger" aria-expanded="false" type="button">
                        <span>Apakah CrePlann gratis untuk digunakan?</span>
                        <i class="ti ti-chevron-down hp-faq-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="hp-faq-body" hidden>
                        <p>Ya, CrePlann sepenuhnya gratis. Anda bisa menggunakan semua fitur — Schedule, Todo, Notes, dan Dashboard — tanpa batas dan tanpa perlu memasukkan data pembayaran.</p>
                    </div>
                </div>

                <div class="hp-faq-item" data-faq>
                    <button class="hp-faq-trigger" aria-expanded="false" type="button">
                        <span>Bisakah saya masuk menggunakan akun Google?</span>
                        <i class="ti ti-chevron-down hp-faq-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="hp-faq-body" hidden>
                        <p>Tentu. Di halaman login, klik tombol "Masuk dengan Google" dan ikuti proses autentikasi. Akun Anda akan dibuat otomatis jika belum terdaftar.</p>
                    </div>
                </div>

                <div class="hp-faq-item" data-faq>
                    <button class="hp-faq-trigger" aria-expanded="false" type="button">
                        <span>Apa itu fitur "Generate Todo dari Schedule"?</span>
                        <i class="ti ti-chevron-down hp-faq-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="hp-faq-body" hidden>
                        <p>Fitur ini memungkinkan Anda membuat satu item todo yang secara otomatis terhubung dengan jadwal tertentu. Judul todo akan mengikuti judul jadwal, dan due date-nya disesuaikan dengan tanggal jadwal. Jika Anda mengubah judul jadwal, todo-nya juga ikut diperbarui.</p>
                    </div>
                </div>

                <div class="hp-faq-item" data-faq>
                    <button class="hp-faq-trigger" aria-expanded="false" type="button">
                        <span>Bagaimana cara menghapus akun saya?</span>
                        <i class="ti ti-chevron-down hp-faq-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="hp-faq-body" hidden>
                        <p>Buka halaman <strong>Profil</strong> melalui navbar, gulir ke bagian bawah, lalu klik "Hapus Akun". Masukkan kata sandi Anda untuk konfirmasi. Semua data Anda akan dihapus permanen dan tidak bisa dipulihkan.</p>
                    </div>
                </div>

                <div class="hp-faq-item" data-faq>
                    <button class="hp-faq-trigger" aria-expanded="false" type="button">
                        <span>Data saya aman? Apakah bisa diakses orang lain?</span>
                        <i class="ti ti-chevron-down hp-faq-chevron" aria-hidden="true"></i>
                    </button>
                    <div class="hp-faq-body" hidden>
                        <p>Semua data Anda bersifat privat. Setiap schedule, todo, catatan, dan kategori hanya bisa diakses oleh akun pemiliknya. Sistem kami memverifikasi kepemilikan data di setiap request yang masuk.</p>
                    </div>
                </div>

            </div>
        </section>

        {{-- ── CTA ke Kontak ────────────────────────────────────── --}}
        <div class="hp-contact-strip">
            <div class="hp-contact-inner">
                <i class="ti ti-headset hp-contact-icon" aria-hidden="true"></i>
                <div class="hp-contact-text">
                    <span class="hp-contact-title">Tidak menemukan jawaban yang Anda cari?</span>
                    <span class="hp-contact-sub">Tim kami siap membantu. Biasanya respons dalam 1×24 jam.</span>
                </div>
                <a href="{{ route('contact.index') }}" class="nt-btn nt-btn--solid hp-contact-btn">
                    <i class="ti ti-mail" aria-hidden="true"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>

    </div>{{-- /.hp-wrap --}}

</x-public-layout>

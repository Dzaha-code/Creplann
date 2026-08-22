<x-app-layout>
    <x-slot name="header">
        <div class="nt-page-header">
            <div class="nt-header-inner">
                <div class="nt-header-icon" aria-hidden="true">
                    <i class="ti ti-notebook"></i>
                </div>
                <div>
                    <h1 class="nt-page-title">Notes</h1>
                    <p class="nt-page-sub">Kelola catatan dan kategori dalam satu tempat</p>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        $defaultCategory = $categories?->firstWhere('name', 'Umum');
        $categoryFilter = request('category');

        // Prepare categories data for JavaScript – moved out of @json to avoid parse error
        $categoriesData = ($categories ?? collect())->map(fn ($category) => [
            'id' => $category->id,
            'name' => $category->name,
            'notes_count' => $category->notes_count ?? 0,
        ])->values();

        // Dikumpulkan di @php dulu: @json di Laravel memakai explode(',') sehingga
        // TIDAK bisa menerima array literal berisi koma — hanya variabel tunggal.
        $notesConfig = [
            'categoryFilter' => (string) ($categoryFilter ?? ''),
            'noteIndexRoute' => route('note.index'),
            'noteStoreRoute' => route('note.store'),
            'defaultCategoryId' => $defaultCategory?->id,
            'categories' => $categoriesData,
        ];
    @endphp

    @push('head')
        <script>
            window.__notesConfig = @json($notesConfig);
        </script>
    @endpush

    <div class="nt-wrap">
        <div class="nt-toolbar">
            <nav class="nt-chips" id="categoryChips" aria-label="Filter kategori">
                <a href="{{ route('note.index') }}" class="nt-chip {{ ! $categoryFilter ? 'active' : '' }}">
                    <i class="ti ti-layout-list" aria-hidden="true"></i>
                    Semua
                </a>
                @foreach ($categories ?? [] as $category)
                    <a href="{{ route('note.index', ['category' => $category->id]) }}"
                        class="nt-chip {{ (string) $categoryFilter === (string) $category->id ? 'active' : '' }}">
                        <i class="ti ti-tag" aria-hidden="true"></i>
                        {{ $category->name }}
                    </a>
                @endforeach
            </nav>

            <div class="nt-toolbar-actions">
                <button id="openCategoryModalBtn" class="nt-btn nt-btn--ghost" type="button">
                    <i class="ti ti-tags" aria-hidden="true"></i>
                    Kelola Kategori
                </button>
                <button id="openCreateBtn" class="nt-btn nt-btn--solid" type="button">
                    <i class="ti ti-plus" aria-hidden="true"></i>
                    Catatan Baru
                </button>
            </div>
        </div>

        <div class="nt-grid" id="notesGrid">
            @forelse ($notes ?? [] as $note)
                <article
                    class="nt-card"
                    tabindex="0"
                    role="button"
                    aria-label="Buka catatan {{ $note->title }}"
                    data-note-id="{{ $note->id }}"
                    data-title="{{ e($note->title) }}"
                    data-content="{{ e($note->content) }}"
                    data-category="{{ e($note->category->name ?? 'Umum') }}"
                    data-category-id="{{ $note->category_id ?? $defaultCategory?->id }}"
                    data-date="{{ $note->created_at->translatedFormat('d M Y, H:i') }}"
                >
                    <div class="nt-card-body">
                        <span class="nt-cat-badge">
                            <i class="ti ti-tag" aria-hidden="true"></i>
                            {{ $note->category->name ?? 'Umum' }}
                        </span>
                        <h3 class="nt-card-title">{{ $note->title }}</h3>
                        <p class="nt-card-excerpt">{{ \Illuminate\Support\Str::limit($note->content, 140) }}</p>
                    </div>

                    <div class="nt-card-footer">
                        <span class="nt-card-date">
                            <i class="ti ti-calendar-event" aria-hidden="true"></i>
                            {{ $note->created_at->translatedFormat('d M Y') }}
                        </span>

                        <div class="nt-card-actions" role="group" aria-label="Aksi catatan">
                            <button class="nt-icon-btn nt-icon-btn--edit nt-edit-trigger" type="button" data-id="{{ $note->id }}" aria-label="Edit catatan">
                                <i class="ti ti-pencil" aria-hidden="true"></i>
                            </button>
                            <button class="nt-icon-btn nt-icon-btn--delete nt-delete-trigger" type="button" data-id="{{ $note->id }}" aria-label="Hapus catatan">
                                <i class="ti ti-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="nt-empty" id="emptyNotesState" role="status">
                    <i class="ti ti-notes-off" aria-hidden="true"></i>
                    <p class="nt-empty-title">Belum ada catatan</p>
                    <p class="nt-empty-sub">Tambahkan catatan pertama agar bisa mulai menyimpan ide atau materi.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div id="previewModal" class="nt-modal" aria-hidden="true" aria-modal="true" role="dialog">
        <div class="nt-modal-backdrop" data-close-modal="preview"></div>
        <div class="nt-modal-sheet nt-modal-sheet--preview">
            <div class="nt-modal-header">
                <div class="nt-modal-meta">
                    <span class="nt-cat-badge">
                        <i class="ti ti-tag" aria-hidden="true"></i>
                        <span id="previewCatName">Umum</span>
                    </span>
                    <span class="nt-modal-date" id="previewDate"></span>
                </div>

                <div class="nt-modal-header-actions">
                    <button id="previewEditBtn" class="nt-icon-btn nt-icon-btn--edit" type="button" aria-label="Edit catatan">
                        <i class="ti ti-pencil" aria-hidden="true"></i>
                    </button>
                    <button id="previewDeleteBtn" class="nt-icon-btn nt-icon-btn--delete" type="button" aria-label="Hapus catatan">
                        <i class="ti ti-trash" aria-hidden="true"></i>
                    </button>
                    <button id="previewCloseBtn" class="nt-icon-btn" type="button" aria-label="Tutup preview">
                        <i class="ti ti-x" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="nt-modal-body">
                <h2 id="previewTitle" class="nt-preview-title"></h2>
                <div class="nt-preview-divider" aria-hidden="true"></div>
                <div id="previewContent" class="nt-preview-content"></div>
            </div>
        </div>
    </div>

    <div id="formModal" class="nt-modal" aria-hidden="true" aria-modal="true" role="dialog">
        <div class="nt-modal-backdrop" data-close-modal="form"></div>
        <div class="nt-modal-sheet nt-modal-sheet--form">
            <div class="nt-modal-header">
                <h2 id="formModalTitle" class="nt-modal-title">Tambah Catatan</h2>
                <button id="formCloseBtn" class="nt-icon-btn" type="button" aria-label="Tutup form">
                    <i class="ti ti-x" aria-hidden="true"></i>
                </button>
            </div>

            <div class="nt-modal-body">
                <form id="noteForm" method="POST">
                    @csrf
                    <input type="hidden" id="methodInput" name="_method" value="POST">
                    <input type="hidden" id="noteIdInput" name="id" value="">

                    <div class="nt-field-row">
                        <div class="nt-field nt-field--grow">
                            <label class="nt-label" for="noteTitle">Judul</label>
                            <input id="noteTitle" class="nt-input" type="text" name="title" maxlength="255" placeholder="Tulis judul catatan" required>
                        </div>

                        <div class="nt-field nt-field--category">
                            <label class="nt-label" for="noteCategory">Kategori</label>
                            <select id="noteCategory" class="nt-input nt-select" name="category_id"></select>
                        </div>
                    </div>

                    <div class="nt-inline-row">
                        <button id="toggleQuickCategoryBtn" class="nt-link-btn" type="button">
                            <i class="ti ti-folder-plus" aria-hidden="true"></i>
                            Tambah kategori cepat
                        </button>
                        <button id="manageCategoriesFromFormBtn" class="nt-link-btn" type="button">
                            <i class="ti ti-settings" aria-hidden="true"></i>
                            Kelola kategori
                        </button>
                    </div>

                    <div id="quickCategoryPanel" class="nt-quick-panel" hidden>
                        <input id="quickCategoryName" class="nt-input" type="text" placeholder="Nama kategori baru">
                        <div class="nt-quick-actions">
                            <button id="saveQuickCategoryBtn" class="nt-btn nt-btn--solid nt-btn--sm" type="button">Simpan</button>
                            <button id="cancelQuickCategoryBtn" class="nt-btn nt-btn--ghost nt-btn--sm" type="button">Batal</button>
                        </div>
                    </div>

                    <div class="nt-field">
                        <label class="nt-label" for="noteContent">Isi Catatan</label>
                        <textarea id="noteContent" class="nt-input nt-textarea" name="content" rows="8" placeholder="Tulis isi catatan di sini" required></textarea>
                    </div>

                    <div id="formError" class="nt-form-msg nt-form-msg--error" aria-live="polite"></div>

                    <div class="nt-form-actions">
                        <button id="cancelFormBtn" class="nt-btn nt-btn--ghost" type="button">Batal</button>
                        <button id="saveNoteBtn" class="nt-btn nt-btn--solid" type="submit">
                            <i class="ti ti-device-floppy" aria-hidden="true"></i>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="categoryModal" class="nt-modal" aria-hidden="true" aria-modal="true" role="dialog">
        <div class="nt-modal-backdrop" data-close-modal="category"></div>
        <div class="nt-modal-sheet nt-modal-sheet--category">
            <div class="nt-modal-header">
                <h2 class="nt-modal-title">Kelola Kategori Notes</h2>
                <button id="categoryCloseBtn" class="nt-icon-btn" type="button" aria-label="Tutup modal kategori">
                    <i class="ti ti-x" aria-hidden="true"></i>
                </button>
            </div>

            <div class="nt-modal-body">
                <form id="categoryForm" class="nt-category-form">
                    <input type="hidden" id="categoryIdInput" value="">
                    <div class="nt-field-row">
                        <div class="nt-field nt-field--grow">
                            <label class="nt-label" for="categoryNameInput">Nama kategori</label>
                            <input id="categoryNameInput" class="nt-input" type="text" maxlength="255" placeholder="Contoh: Sekolah">
                        </div>
                    </div>

                    <div id="categoryFormError" class="nt-form-msg nt-form-msg--error" aria-live="polite"></div>

                    <div class="nt-form-actions nt-form-actions--left">
                        <button id="cancelCategoryEditBtn" class="nt-btn nt-btn--ghost" type="button" hidden>Batal Edit</button>
                        <button id="saveCategoryManageBtn" class="nt-btn nt-btn--solid" type="submit">
                            <i class="ti ti-device-floppy" aria-hidden="true"></i>
                            Simpan Kategori
                        </button>
                    </div>
                </form>

                <div class="nt-category-list-wrap">
                    <h3 class="nt-section-title">Daftar kategori</h3>
                    <div id="categoryList" class="nt-category-list"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="toast" class="nt-toast" role="status" aria-live="polite"></div>
    @push('head')
        @vite(['resources/css/pages/notes.css', 'resources/js/pages/notes.js'])
    @endpush

</x-app-layout>
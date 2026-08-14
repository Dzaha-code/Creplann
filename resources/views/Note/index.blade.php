<x-app-layout>
    <x-slot name="header">Notes</x-slot>

    <style>
        .notes-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .chip-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .chip {
            padding: 6px 18px;
            border-radius: 999px;
            background: var(--paper-soft);
            color: var(--ink-soft);
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
            border: 1.5px solid transparent;
        }
        .chip:hover {
            background: var(--paper);
            border-color: var(--line);
        }
        .chip.active {
            background: var(--coral);
            color: #fff;
            border-color: var(--coral);
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: transform .15s ease, background .2s ease;
            font-family: inherit;
            text-decoration: none;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn-solid {
            background: var(--coral);
            color: #fff;
            box-shadow: 0 10px 22px -10px rgba(225,91,63,.6);
        }
        .btn-solid:hover {
            background: var(--coral-ink);
        }
        .btn-ghost {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--line);
        }
        .btn-ghost:hover {
            border-color: var(--ink);
        }
        .btn-danger {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger:hover {
            background: #b33a2a;
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 8px;
        }
        .note-card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px 20px 16px;
            transition: all .25s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .note-card:hover {
            box-shadow: 0 12px 28px -14px rgba(36,31,26,.15);
            transform: translateY(-2px);
        }
        .note-card .cat {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: var(--ink-soft);
            background: var(--paper-soft);
            padding: 2px 12px;
            border-radius: 999px;
            display: inline-block;
            align-self: flex-start;
            margin-bottom: 12px;
        }
        .note-card h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        .note-card p {
            color: var(--ink-soft);
            font-size: 0.88rem;
            line-height: 1.5;
            margin: 0 0 16px 0;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .note-card .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
            margin-top: auto;
        }
        .note-card .date {
            font-size: 0.78rem;
            color: var(--ink-soft);
        }
        .note-card .actions {
            position: relative;
        }
        .dot-btn {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            color: var(--ink-soft);
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background .2s ease;
            line-height: 1;
        }
        .dot-btn:hover {
            background: var(--paper-soft);
        }
        .dot-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 30px;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 6px 0;
            min-width: 120px;
            box-shadow: 0 8px 24px rgba(36,31,26,.12);
            z-index: 50;
        }
        .dot-menu.show {
            display: block;
        }
        .dot-menu button {
            display: block;
            width: 100%;
            padding: 8px 16px;
            border: none;
            background: none;
            cursor: pointer;
            text-align: left;
            font-size: 0.85rem;
            font-family: inherit;
            color: var(--ink);
            transition: background .15s ease;
        }
        .dot-menu button:hover {
            background: var(--paper-soft);
        }
        .dot-menu .delete-note {
            color: var(--danger);
        }
        .dot-menu .delete-note:hover {
            background: rgba(196,67,46,.1);
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px 20px;
            color: var(--ink-soft);
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 16px;
            color: var(--line);
        }
        .empty-state p {
            font-size: 1rem;
        }

        /* Modal */
        #note-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(36,31,26,0.45);
            align-items: center;
            justify-content: center;
            z-index: 1200;
            padding: 20px;
            backdrop-filter: blur(2px);
        }
        #note-modal.open {
            display: flex;
        }
        .modal-box {
            background: #fff;
            border-radius: var(--radius);
            padding: 28px 30px 24px;
            max-width: 560px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px -20px rgba(36,31,26,.4);
            animation: modalIn .25s ease;
        }
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        .modal-box h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0 0 18px 0;
        }
        .modal-box .field-group {
            display: flex;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .modal-box .field-group input,
        .modal-box .field-group select {
            padding: 10px 14px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.92rem;
            background: #fff;
            color: var(--ink);
            transition: border-color .2s ease;
        }
        .modal-box .field-group input:focus,
        .modal-box .field-group select:focus {
            outline: none;
            border-color: var(--coral);
        }
        .modal-box .field-group input {
            flex: 2;
            min-width: 160px;
        }
        .modal-box .field-group select {
            flex: 1;
            min-width: 120px;
        }
        .modal-box textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--line);
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.92rem;
            resize: vertical;
            transition: border-color .2s ease;
        }
        .modal-box textarea:focus {
            outline: none;
            border-color: var(--coral);
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 18px;
        }
        .modal-actions .btn {
            padding: 10px 24px;
        }
        .form-error {
            color: var(--danger);
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 6px;
            min-height: 20px;
        }
        .form-success {
            color: var(--sage);
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 6px;
            min-height: 20px;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--ink);
            color: #fff;
            padding: 12px 22px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 600;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease, transform .25s ease;
            z-index: 200;
        }
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast.error {
            background: var(--danger);
        }

        @media (max-width: 600px) {
            .notes-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .notes-grid {
                grid-template-columns: 1fr;
            }
            .modal-box {
                padding: 20px;
            }
            .modal-box .field-group {
                flex-direction: column;
            }
            .modal-box .field-group input,
            .modal-box .field-group select {
                width: 100%;
            }
        }
    </style>

    <div class="wrap">
        <div class="notes-toolbar">
            <div class="chip-row">
                <a href="{{ route('note.index') }}" class="chip {{ !request('category') ? 'active' : '' }}">Semua</a>
                @foreach ($categories ?? [] as $cat)
                    <a href="{{ route('note.index', ['category' => $cat->id]) }}" class="chip {{ request('category') == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            <button id="open-note-modal" class="btn btn-solid">+ Catatan Baru</button>
        </div>

        <div class="notes-grid" id="notesGrid">
            @forelse ($notes ?? [] as $note)
                <div class="note-card" data-note-id="{{ $note->id }}">
                    <span class="cat">{{ $note->category->name ?? 'Umum' }}</span>
                    <h3>{{ $note->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($note->content, 110) }}</p>
                    <div class="meta">
                        <span class="date">{{ $note->created_at->translatedFormat('d M Y') }}</span>
                        <div class="actions">
                            <button class="dot-btn" type="button" aria-label="Actions">⋯</button>
                            <div class="dot-menu">
                                <button class="edit-note" type="button">✏️ Edit</button>
                                <button class="delete-note-btn" type="button" data-id="{{ $note->id }}">🗑️ Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke-linejoin="round"/>
                        <path d="M9 9h6M9 13h4" stroke-linecap="round"/>
                    </svg>
                    <p>Belum ada catatan. Yuk tulis yang pertama.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Note Modal -->
    <div id="note-modal">
        <div class="modal-box">
            <h3 id="modalTitle">Tambah Catatan</h3>
            <form id="noteForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodInput" value="POST">
                <input type="hidden" name="id" id="noteIdInput">

                <div class="field-group">
                    <input type="text" name="title" id="noteTitle" placeholder="Judul catatan" required maxlength="255" />
                    <select name="category_id" id="noteCategory">
                        <option value="">Umum</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <div style="display:flex;flex-direction:column;gap:6px;margin-left:8px;">
                        <button type="button" id="addCategoryBtn" class="btn btn-ghost" style="padding:8px 10px;">+ Tambah kategori</button>
                        <div id="newCategoryWrap" style="display:none;gap:6px;align-items:center;">
                            <input type="text" id="newCategoryInput" placeholder="Nama kategori" style="padding:8px;border:1px solid var(--line);border-radius:8px;" />
                            <button type="button" id="saveCategoryBtn" class="btn btn-solid" style="padding:8px 10px;">Simpan</button>
                        </div>
                    </div>
                </div>

                <textarea name="content" id="noteContent" rows="6" placeholder="Tulis catatan di sini..." required></textarea>

                <div class="form-error" id="formError"></div>
                <div class="form-success" id="formSuccess"></div>

                <div class="modal-actions">
                    <button type="button" id="cancelModalBtn" class="btn btn-ghost">Batal</button>
                    <button type="submit" id="saveNoteBtn" class="btn btn-solid">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
    (function() {
        'use strict';

        // ========== DOM References ==========
        const modal = document.getElementById('note-modal');
        const form = document.getElementById('noteForm');
        const modalTitle = document.getElementById('modalTitle');
        const methodInput = document.getElementById('methodInput');
        const idInput = document.getElementById('noteIdInput');
        const titleInput = document.getElementById('noteTitle');
        const contentInput = document.getElementById('noteContent');
        const categoryInput = document.getElementById('noteCategory');
        const formError = document.getElementById('formError');
        const formSuccess = document.getElementById('formSuccess');
        const saveBtn = document.getElementById('saveNoteBtn');
        const cancelBtn = document.getElementById('cancelModalBtn');
        const openBtn = document.getElementById('open-note-modal');
        const notesGrid = document.getElementById('notesGrid');
        const toast = document.getElementById('toast');
        // first category id to use as default when creating a note
        const defaultCategory = '{{ $categories->first()->id ?? '' }}';

        let toastTimer = null;

        // ========== CSRF Token ==========
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // ========== Toast Functions ==========
        function showToast(message, isError = false) {
            toast.textContent = message;
            toast.className = `toast ${isError ? 'error' : ''}`;
            // Force reflow
            void toast.offsetWidth;
            toast.classList.add('show');
            
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // ========== Modal Functions ==========
        function openModal(mode, noteData = null) {
            // Reset form
            form.reset();
            formError.textContent = '';
            formSuccess.textContent = '';
            formError.className = 'form-error';
            formSuccess.className = 'form-success';
            
            if (mode === 'edit' && noteData) {
                modalTitle.textContent = 'Edit Catatan';
                methodInput.value = 'PATCH';
                idInput.value = noteData.id;
                titleInput.value = noteData.title;
                contentInput.value = noteData.content;
                categoryInput.value = noteData.category_id || '';
                form.action = `/note/${noteData.id}`;
                saveBtn.textContent = 'Perbarui';
            } else {
                modalTitle.textContent = 'Tambah Catatan';
                methodInput.value = 'POST';
                idInput.value = '';
                titleInput.value = '';
                contentInput.value = '';
                categoryInput.value = defaultCategory || '';
                form.action = '{{ route('note.store') }}';
                saveBtn.textContent = 'Simpan';
            }
            
            modal.classList.add('open');
            setTimeout(() => titleInput.focus(), 100);
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        // ========== Fetch Note Data ==========
        function getNoteDataFromCard(card) {
            // Get data from DOM attributes instead of dataset to avoid escaping issues
            const id = card.dataset.noteId;
            const titleEl = card.querySelector('h3');
            const contentEl = card.querySelector('p');
            const catEl = card.querySelector('.cat');
            
            return {
                id: id,
                title: titleEl ? titleEl.textContent.trim() : '',
                content: contentEl ? contentEl.textContent.trim() : '',
                category_id: catEl ? catEl.textContent.trim() : '',
                category_name: catEl ? catEl.textContent.trim() : 'Umum'
            };
        }

        // ========== Delete Note ==========
        async function deleteNote(noteId, card) {
            if (!confirm('Apakah Anda yakin ingin menghapus catatan ini?')) {
                return;
            }

            try {
                const response = await fetch(`/note/${noteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menghapus catatan');
                }

                // Remove card with animation
                if (card) {
                    card.style.transition = 'all .3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.remove();
                        // Check if grid is empty
                        if (notesGrid.children.length === 0) {
                            notesGrid.innerHTML = `
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                        <path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke-linejoin="round"/>
                                        <path d="M9 9h6M9 13h4" stroke-linecap="round"/>
                                    </svg>
                                    <p>Belum ada catatan. Yuk tulis yang pertama.</p>
                                </div>
                            `;
                        }
                    }, 300);
                }

                showToast(result.message || 'Catatan berhasil dihapus');
            } catch (error) {
                showToast(error.message || 'Gagal menghapus catatan', true);
                console.error('Delete error:', error);
            }
        }

        // ========== Fetch Notes (for reload) ==========
        async function fetchNotes() {
            try {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) return;

                const data = await response.json();
                if (data.html) {
                    // Replace only the grid content
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    const newGrid = tempDiv.querySelector('.notes-grid');
                    if (newGrid) {
                        notesGrid.innerHTML = newGrid.innerHTML;
                        // Re-initialize event listeners
                        initNoteCardEvents();
                    }
                }
            } catch (error) {
                console.error('Error fetching notes:', error);
            }
        }

        // ========== Initialize Note Card Events ==========
        function initNoteCardEvents() {
            document.querySelectorAll('.note-card').forEach(card => {
                const dotBtn = card.querySelector('.dot-btn');
                const menu = card.querySelector('.dot-menu');
                const editBtn = card.querySelector('.edit-note');
                const deleteBtn = card.querySelector('.delete-note-btn');

                // Toggle menu
                if (dotBtn) {
                    dotBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        // Close all other menus
                        document.querySelectorAll('.dot-menu.show').forEach(m => {
                            if (m !== menu) m.classList.remove('show');
                        });
                        menu.classList.toggle('show');
                    });
                }

                // Edit button
                if (editBtn) {
                    editBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        menu.classList.remove('show');
                        const noteData = getNoteDataFromCard(card);
                        openModal('edit', noteData);
                    });
                }

                // Delete button
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        menu.classList.remove('show');
                        const noteId = this.dataset.id;
                        deleteNote(noteId, card);
                    });
                }
            });

            // Close all menus when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.dot-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });
            });
        }

        // ========== Form Submit ==========
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validate
            const title = titleInput.value.trim();
            const content = contentInput.value.trim();

            if (!title) {
                formError.textContent = 'Judul catatan harus diisi';
                formError.className = 'form-error';
                titleInput.focus();
                return;
            }

            if (!content) {
                formError.textContent = 'Konten catatan harus diisi';
                formError.className = 'form-error';
                contentInput.focus();
                return;
            }
            if (!categoryInput.value) {
                formError.textContent = 'Kategori harus dipilih';
                formError.className = 'form-error';
                categoryInput.focus();
                return;
            }

            // Clear previous messages
            formError.textContent = '';
            formSuccess.textContent = '';
            formSuccess.className = 'form-success';

            // Disable button
            saveBtn.disabled = true;
            saveBtn.textContent = 'Menyimpan...';

            const formData = new FormData(form);
            const url = form.action;
            const method = methodInput.value;

            try {
                const response = await fetch(url, {
                    method: method === 'PATCH' ? 'POST' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-HTTP-Method-Override': method
                    },
                    body: formData
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.message || 'Gagal menyimpan catatan');
                }

                // Success
                formSuccess.textContent = result.message || 'Catatan berhasil disimpan!';
                formSuccess.className = 'form-success';
                showToast(result.message || 'Catatan berhasil disimpan');

                // Close modal after short delay
                setTimeout(() => {
                    closeModal();
                    // Reload page to reflect changes
                    window.location.reload();
                }, 500);

            } catch (error) {
                formError.textContent = error.message || 'Terjadi kesalahan. Silakan coba lagi.';
                formError.className = 'form-error';
                showToast(error.message || 'Gagal menyimpan catatan', true);
                console.error('Submit error:', error);
            } finally {
                saveBtn.disabled = false;
                saveBtn.textContent = method === 'PATCH' ? 'Perbarui' : 'Simpan';
            }
        });

        // ========== Event Listeners ==========
        // Open modal for create
        openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('create');
        });

        // Close modal
        cancelBtn.addEventListener('click', closeModal);

        // Close modal on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('open')) {
                closeModal();
            }
        });

        // ========== Initialize ==========
        initNoteCardEvents();

        // ========== Inline Add Category ==========
        const addCategoryBtn = document.getElementById('addCategoryBtn');
        const newCategoryWrap = document.getElementById('newCategoryWrap');
        const newCategoryInput = document.getElementById('newCategoryInput');
        const saveCategoryBtn = document.getElementById('saveCategoryBtn');

        if (addCategoryBtn) {
            addCategoryBtn.addEventListener('click', function(e) {
                e.preventDefault();
                newCategoryWrap.style.display = newCategoryWrap.style.display === 'flex' ? 'none' : 'flex';
                if (newCategoryWrap.style.display === 'flex') newCategoryInput.focus();
            });
        }

        if (saveCategoryBtn) {
            saveCategoryBtn.addEventListener('click', async function() {
                const name = newCategoryInput.value.trim();
                if (!name) {
                    showToast('Masukkan nama kategori', true);
                    newCategoryInput.focus();
                    return;
                }

                saveCategoryBtn.disabled = true;
                saveCategoryBtn.textContent = 'Menyimpan...';

                try {
                    const res = await fetch('/planner-api/categories', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ name })
                    });

                    const payload = await res.json();
                    if (!res.ok) throw new Error(payload.message || (payload.errors && Object.values(payload.errors).flat()[0]) || 'Gagal menambah kategori');

                    const cat = payload.data;
                    // append to select
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    categoryInput.appendChild(opt);
                    categoryInput.value = cat.id;

                    showToast(payload.message || 'Kategori dibuat');
                    newCategoryInput.value = '';
                    newCategoryWrap.style.display = 'none';
                } catch (err) {
                    console.error('Create category error', err);
                    showToast(err.message || 'Gagal membuat kategori', true);
                } finally {
                    saveCategoryBtn.disabled = false;
                    saveCategoryBtn.textContent = 'Simpan';
                }
            });
        }

        // ========== Auto-refresh on back/forward ==========
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) {
                window.location.reload();
            }
        });

    })();
    </script>
</x-app-layout>
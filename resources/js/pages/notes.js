        (function () {
            'use strict';

            const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const notesGrid = document.getElementById('notesGrid');
            const categoryChips = document.getElementById('categoryChips');

            // Data server disuntikkan oleh Blade (Note/index.blade.php) via window.__notesConfig
            const pageConfig = window.__notesConfig || {};
            const currentCategoryFilter = pageConfig.categoryFilter ?? '';
            const noteIndexRoute = pageConfig.noteIndexRoute ?? '';
            const noteStoreRoute = pageConfig.noteStoreRoute ?? '';
            const defaultCategoryId = pageConfig.defaultCategoryId ?? null;

            let categories = pageConfig.categories ?? [];

            const toastEl = document.getElementById('toast');
            let toastTimer;

            function showToast(message, isError = false) {
                toastEl.textContent = message;
                toastEl.className = 'nt-toast' + (isError ? ' is-error' : '');
                window.clearTimeout(toastTimer);
                requestAnimationFrame(() => toastEl.classList.add('is-show'));
                toastTimer = window.setTimeout(() => toastEl.classList.remove('is-show'), 3200);
            }

            async function parseJson(response) {
                const text = await response.text();
                if (!text) {
                    return {};
                }

                try {
                    return JSON.parse(text);
                } catch (error) {
                    throw new Error('Respons server tidak valid.');
                }
            }

            function buildErrorMessage(payload, fallbackMessage) {
                if (payload?.errors && typeof payload.errors === 'object') {
                    const firstError = Object.values(payload.errors).flat()[0];
                    if (firstError) {
                        return firstError;
                    }
                }

                return payload?.message || fallbackMessage;
            }

            async function request(url, options = {}, fallbackMessage = 'Terjadi kesalahan.') {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                        ...(options.headers || {}),
                    },
                });

                const payload = await parseJson(response);

                if (!response.ok) {
                    throw new Error(buildErrorMessage(payload, fallbackMessage));
                }

                return payload;
            }

            function getDefaultCategory() {
                return categories.find((category) => category.name === 'Umum') || categories[0] || null;
            }

            function renderCategoryOptions(selectedValue = '') {
                const noteCategory = document.getElementById('noteCategory');
                const fragment = document.createDocumentFragment();

                categories.forEach((category) => {
                    const option = document.createElement('option');
                    option.value = String(category.id);
                    option.textContent = category.name;
                    fragment.appendChild(option);
                });

                noteCategory.innerHTML = '';
                noteCategory.appendChild(fragment);

                const fallbackValue = selectedValue || String(getDefaultCategory()?.id || '');
                noteCategory.value = String(fallbackValue);
            }

            function renderCategoryChips() {
                const categoriesMarkup = categories
                    .map((category) => {
                        const activeClass = String(currentCategoryFilter) === String(category.id) ? ' active' : '';
                        return `
                            <a href="${noteIndexRoute}?category=${category.id}" class="nt-chip${activeClass}">
                                <i class="ti ti-tag" aria-hidden="true"></i>
                                ${category.name}
                            </a>
                        `;
                    })
                    .join('');

                categoryChips.innerHTML = `
                    <a href="${noteIndexRoute}" class="nt-chip${currentCategoryFilter ? '' : ' active'}">
                        <i class="ti ti-layout-list" aria-hidden="true"></i>
                        Semua
                    </a>
                    ${categoriesMarkup}
                `;
            }

            function renderCategoryList() {
                const categoryList = document.getElementById('categoryList');

                if (!categories.length) {
                    categoryList.innerHTML = `
                        <div class="nt-empty" role="status">
                            <i class="ti ti-tags-off" aria-hidden="true"></i>
                            <p class="nt-empty-title">Belum ada kategori</p>
                            <p class="nt-empty-sub">Tambahkan kategori pertama dari form di atas.</p>
                        </div>
                    `;
                    return;
                }

                categoryList.innerHTML = categories
                    .map((category) => {
                        const isDefault = category.name === 'Umum';
                        return `
                            <div class="nt-category-item">
                                <div class="nt-category-main">
                                    <div class="nt-category-name">${category.name}</div>
                                    <div class="nt-category-meta">
                                        ${category.notes_count ?? 0} catatan${isDefault ? ' • kategori default' : ''}
                                    </div>
                                </div>
                                <div class="nt-category-actions">
                                    <button
                                        class="nt-icon-btn nt-icon-btn--edit js-edit-category"
                                        type="button"
                                        data-id="${category.id}"
                                        aria-label="Edit kategori ${category.name}"
                                    >
                                        <i class="ti ti-pencil" aria-hidden="true"></i>
                                    </button>
                                    <button
                                        class="nt-icon-btn nt-icon-btn--delete js-delete-category"
                                        type="button"
                                        data-id="${category.id}"
                                        ${isDefault ? 'disabled title="Kategori Umum tidak bisa dihapus"' : ''}
                                        aria-label="Hapus kategori ${category.name}"
                                    >
                                        <i class="ti ti-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    })
                    .join('');
            }

            function syncCategoryUi(selectedValue = '') {
                categories = [...categories].sort((a, b) => {
                    if (a.name === 'Umum') return -1;
                    if (b.name === 'Umum') return 1;
                    return a.name.localeCompare(b.name, 'id');
                });

                renderCategoryOptions(selectedValue);
                renderCategoryChips();
                renderCategoryList();
            }

            function getCardById(id) {
                return document.querySelector(`.nt-card[data-note-id="${id}"]`);
            }

            function getCardData(card) {
                return {
                    id: card.dataset.noteId,
                    title: card.dataset.title || '',
                    content: card.dataset.content || '',
                    category: card.dataset.category || 'Umum',
                    categoryId: card.dataset.categoryId || String(getDefaultCategory()?.id || ''),
                    date: card.dataset.date || '',
                };
            }

            const previewModal = document.getElementById('previewModal');
            const previewTitle = document.getElementById('previewTitle');
            const previewCatName = document.getElementById('previewCatName');
            const previewDate = document.getElementById('previewDate');
            const previewContent = document.getElementById('previewContent');
            const previewCloseBtn = document.getElementById('previewCloseBtn');
            const previewEditBtn = document.getElementById('previewEditBtn');
            const previewDeleteBtn = document.getElementById('previewDeleteBtn');
            let activePreviewCard = null;

            function openModal(modal) {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
            }

            function closeModal(modal) {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            function openPreview(card) {
                const note = getCardData(card);
                activePreviewCard = card;
                previewTitle.textContent = note.title;
                previewCatName.textContent = note.category;
                previewDate.textContent = note.date;
                previewContent.textContent = note.content;
                previewEditBtn.dataset.id = note.id;
                previewDeleteBtn.dataset.id = note.id;
                openModal(previewModal);
                previewCloseBtn.focus();
            }

            function closePreview() {
                closeModal(previewModal);
                if (activePreviewCard) {
                    activePreviewCard.focus();
                }
                activePreviewCard = null;
            }

            previewCloseBtn.addEventListener('click', closePreview);
            previewEditBtn.addEventListener('click', () => {
                const card = getCardById(previewEditBtn.dataset.id);
                closePreview();
                if (card) {
                    openNoteForm('edit', card);
                }
            });
            previewDeleteBtn.addEventListener('click', async () => {
                const card = getCardById(previewDeleteBtn.dataset.id);
                closePreview();
                if (card) {
                    await deleteNote(previewDeleteBtn.dataset.id);
                }
            });

            const formModal = document.getElementById('formModal');
            const formModalTitle = document.getElementById('formModalTitle');
            const noteForm = document.getElementById('noteForm');
            const methodInput = document.getElementById('methodInput');
            const noteIdInput = document.getElementById('noteIdInput');
            const noteTitle = document.getElementById('noteTitle');
            const noteContent = document.getElementById('noteContent');
            const formError = document.getElementById('formError');
            const saveNoteBtn = document.getElementById('saveNoteBtn');
            const quickCategoryPanel = document.getElementById('quickCategoryPanel');
            const quickCategoryName = document.getElementById('quickCategoryName');

            function resetQuickCategory() {
                quickCategoryPanel.hidden = true;
                quickCategoryName.value = '';
            }

            function openNoteForm(mode, card = null) {
                noteForm.reset();
                formError.textContent = '';
                resetQuickCategory();

                if (mode === 'edit' && card) {
                    const note = getCardData(card);
                    formModalTitle.textContent = 'Edit Catatan';
                    methodInput.value = 'PATCH';
                    noteIdInput.value = note.id;
                    noteTitle.value = note.title;
                    noteContent.value = note.content;
                    noteForm.action = `/note/${note.id}`;
                    renderCategoryOptions(note.categoryId || String(defaultCategoryId || ''));
                    saveNoteBtn.innerHTML = '<i class="ti ti-device-floppy" aria-hidden="true"></i> Perbarui';
                } else {
                    formModalTitle.textContent = 'Tambah Catatan';
                    methodInput.value = 'POST';
                    noteIdInput.value = '';
                    noteForm.action = noteStoreRoute;
                    renderCategoryOptions(String(getDefaultCategory()?.id || defaultCategoryId || ''));
                    saveNoteBtn.innerHTML = '<i class="ti ti-device-floppy" aria-hidden="true"></i> Simpan';
                }

                openModal(formModal);
                window.setTimeout(() => noteTitle.focus(), 50);
            }

            function closeNoteForm() {
                closeModal(formModal);
            }

            document.getElementById('openCreateBtn').addEventListener('click', () => openNoteForm('create'));
            document.getElementById('cancelFormBtn').addEventListener('click', closeNoteForm);
            document.getElementById('formCloseBtn').addEventListener('click', closeNoteForm);
            document.getElementById('toggleQuickCategoryBtn').addEventListener('click', () => {
                quickCategoryPanel.hidden = !quickCategoryPanel.hidden;
                if (!quickCategoryPanel.hidden) {
                    quickCategoryName.focus();
                }
            });
            document.getElementById('cancelQuickCategoryBtn').addEventListener('click', resetQuickCategory);

            noteForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const title = noteTitle.value.trim();
                const content = noteContent.value.trim();
                const noteCategory = document.getElementById('noteCategory');

                if (!title) {
                    formError.textContent = 'Judul catatan wajib diisi.';
                    noteTitle.focus();
                    return;
                }

                if (!content) {
                    formError.textContent = 'Isi catatan wajib diisi.';
                    noteContent.focus();
                    return;
                }

                if (!noteCategory.value) {
                    noteCategory.value = String(getDefaultCategory()?.id || defaultCategoryId || '');
                }

                formError.textContent = '';
                saveNoteBtn.disabled = true;

                try {
                    const formData = new FormData(noteForm);
                    const payload = await request(noteForm.action, {
                        method: 'POST',
                        headers: {
                            'X-HTTP-Method-Override': methodInput.value,
                        },
                        body: formData,
                    }, 'Gagal menyimpan catatan.');

                    showToast(payload.message || 'Catatan berhasil disimpan.');
                    closeNoteForm();
                    window.location.reload();
                } catch (error) {
                    formError.textContent = error.message;
                    showToast(error.message, true);
                } finally {
                    saveNoteBtn.disabled = false;
                }
            });

            async function deleteNote(noteId) {
                if (!window.confirm('Hapus catatan ini?')) {
                    return;
                }

                try {
                    const payload = await request(`/note/${noteId}`, {
                        method: 'DELETE',
                    }, 'Gagal menghapus catatan.');

                    showToast(payload.message || 'Catatan berhasil dihapus.');
                    window.location.reload();
                } catch (error) {
                    showToast(error.message, true);
                }
            }

            notesGrid.addEventListener('click', (event) => {
                const actionButton = event.target.closest('.nt-icon-btn');
                const card = event.target.closest('.nt-card');

                if (!card) {
                    return;
                }

                if (actionButton?.classList.contains('nt-edit-trigger')) {
                    event.stopPropagation();
                    openNoteForm('edit', card);
                    return;
                }

                if (actionButton?.classList.contains('nt-delete-trigger')) {
                    event.stopPropagation();
                    deleteNote(actionButton.dataset.id);
                    return;
                }

                openPreview(card);
            });

            notesGrid.addEventListener('keydown', (event) => {
                const card = event.target.closest('.nt-card');
                if (!card) {
                    return;
                }

                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openPreview(card);
                }
            });

            const categoryModal = document.getElementById('categoryModal');
            const categoryForm = document.getElementById('categoryForm');
            const categoryIdInput = document.getElementById('categoryIdInput');
            const categoryNameInput = document.getElementById('categoryNameInput');
            const categoryFormError = document.getElementById('categoryFormError');
            const cancelCategoryEditBtn = document.getElementById('cancelCategoryEditBtn');
            const saveCategoryManageBtn = document.getElementById('saveCategoryManageBtn');

            function resetCategoryForm() {
                categoryIdInput.value = '';
                categoryNameInput.value = '';
                categoryFormError.textContent = '';
                cancelCategoryEditBtn.hidden = true;
                saveCategoryManageBtn.innerHTML = '<i class="ti ti-device-floppy" aria-hidden="true"></i> Simpan Kategori';
            }

            function openCategoryModal() {
                resetCategoryForm();
                renderCategoryList();
                openModal(categoryModal);
                window.setTimeout(() => categoryNameInput.focus(), 50);
            }

            function closeCategoryModal() {
                closeModal(categoryModal);
                resetCategoryForm();
            }

            document.getElementById('openCategoryModalBtn').addEventListener('click', openCategoryModal);
            document.getElementById('manageCategoriesFromFormBtn').addEventListener('click', () => {
                openCategoryModal();
            });
            document.getElementById('categoryCloseBtn').addEventListener('click', closeCategoryModal);
            cancelCategoryEditBtn.addEventListener('click', resetCategoryForm);

            async function createCategory(name) {
                return request('/planner-api/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name }),
                }, 'Gagal membuat kategori.');
            }

            async function saveQuickCategory() {
                const name = quickCategoryName.value.trim();
                if (!name) {
                    showToast('Nama kategori wajib diisi.', true);
                    quickCategoryName.focus();
                    return;
                }

                const saveQuickCategoryBtn = document.getElementById('saveQuickCategoryBtn');
                saveQuickCategoryBtn.disabled = true;

                try {
                    const payload = await createCategory(name);
                    const newCategory = payload.data;
                    categories = [...categories, newCategory];
                    syncCategoryUi(String(newCategory.id));
                    quickCategoryName.value = '';
                    quickCategoryPanel.hidden = true;
                    showToast(payload.message || 'Kategori berhasil dibuat.');
                } catch (error) {
                    showToast(error.message, true);
                } finally {
                    saveQuickCategoryBtn.disabled = false;
                }
            }

            document.getElementById('saveQuickCategoryBtn').addEventListener('click', saveQuickCategory);
            quickCategoryName.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    saveQuickCategory();
                }
            });

            categoryForm.addEventListener('submit', async (event) => {
                event.preventDefault();

                const name = categoryNameInput.value.trim();
                if (!name) {
                    categoryFormError.textContent = 'Nama kategori wajib diisi.';
                    categoryNameInput.focus();
                    return;
                }

                categoryFormError.textContent = '';
                saveCategoryManageBtn.disabled = true;

                const categoryId = categoryIdInput.value;
                const isEdit = Boolean(categoryId);

                try {
                    const payload = await request(
                        isEdit ? `/planner-api/categories/${categoryId}` : '/planner-api/categories',
                        {
                            method: isEdit ? 'PATCH' : 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ name }),
                        },
                        isEdit ? 'Gagal memperbarui kategori.' : 'Gagal membuat kategori.',
                    );

                    const updatedCategory = payload.data;
                    const existingIndex = categories.findIndex((category) => String(category.id) === String(updatedCategory.id));

                    if (existingIndex >= 0) {
                        categories[existingIndex] = updatedCategory;
                    } else {
                        categories.push(updatedCategory);
                    }

                    syncCategoryUi(String(updatedCategory.id));
                    resetCategoryForm();
                    showToast(payload.message || 'Kategori berhasil disimpan.');
                } catch (error) {
                    categoryFormError.textContent = error.message;
                    showToast(error.message, true);
                } finally {
                    saveCategoryManageBtn.disabled = false;
                }
            });

            document.getElementById('categoryList').addEventListener('click', async (event) => {
                const editButton = event.target.closest('.js-edit-category');
                if (editButton) {
                    const category = categories.find((item) => String(item.id) === String(editButton.dataset.id));
                    if (!category) {
                        return;
                    }

                    categoryIdInput.value = category.id;
                    categoryNameInput.value = category.name;
                    categoryFormError.textContent = '';
                    cancelCategoryEditBtn.hidden = false;
                    saveCategoryManageBtn.innerHTML = '<i class="ti ti-device-floppy" aria-hidden="true"></i> Perbarui Kategori';
                    categoryNameInput.focus();
                    return;
                }

                const deleteButton = event.target.closest('.js-delete-category');
                if (!deleteButton || deleteButton.disabled) {
                    return;
                }

                const categoryId = deleteButton.dataset.id;
                const category = categories.find((item) => String(item.id) === String(categoryId));
                if (!category) {
                    return;
                }

                if (!window.confirm(`Hapus kategori "${category.name}"? Catatan di dalamnya akan dipindahkan ke kategori Umum.`)) {
                    return;
                }

                try {
                    const payload = await request(`/planner-api/categories/${categoryId}`, {
                        method: 'DELETE',
                    }, 'Gagal menghapus kategori.');

                    categories = categories.filter((item) => String(item.id) !== String(categoryId));
                    syncCategoryUi(String(getDefaultCategory()?.id || defaultCategoryId || ''));
                    resetCategoryForm();
                    showToast(payload.message || 'Kategori berhasil dihapus.');
                } catch (error) {
                    showToast(error.message, true);
                }
            });

            document.querySelectorAll('[data-close-modal]').forEach((element) => {
                element.addEventListener('click', () => {
                    const target = element.getAttribute('data-close-modal');
                    if (target === 'preview') closePreview();
                    if (target === 'form') closeNoteForm();
                    if (target === 'category') closeCategoryModal();
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') {
                    return;
                }

                if (previewModal.classList.contains('is-open')) closePreview();
                if (formModal.classList.contains('is-open')) closeNoteForm();
                if (categoryModal.classList.contains('is-open')) closeCategoryModal();
            });

            syncCategoryUi(String(defaultCategoryId || getDefaultCategory()?.id || ''));
        })();
    
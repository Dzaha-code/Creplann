        (function() {
            'use strict';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
            const dayNames = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            const grid = document.getElementById('weeklyGrid');
            const weekLabel = document.getElementById('weekLabel');
            const todoOverview = document.getElementById('todoOverview');
            const todoCount = document.getElementById('todoCount');
            const modal = document.getElementById('scheduleModal');
            const form = document.getElementById('scheduleForm');
            const modalTitle = document.getElementById('modalTitle');
            const submitBtn = document.getElementById('submitBtn');
            const formMsg = document.getElementById('formMsg');
            const idInput = document.getElementById('schedule_id');
            const colorInput = document.getElementById('color');
            const toast = document.getElementById('toast');

            let allSchedules = [];
            let currentWeekStart = getMonday(new Date());
            let editingId = null;
            let toastTimer = null;

            // ─── date helpers ──────────────────────────────────────────────
            function getMonday(d) {
                const date = new Date(d);
                const day = date.getDay();
                const diff = day === 0 ? -6 : 1 - day;
                date.setDate(date.getDate() + diff);
                date.setHours(0, 0, 0, 0);
                return date;
            }

            function toDateStr(d) {
                const y = d.getFullYear();
                const m = String(d.getMonth() + 1).padStart(2, '0');
                const day = String(d.getDate()).padStart(2, '0');
                return `${y}-${m}-${day}`;
            }

            function addDays(d, n) {
                const copy = new Date(d);
                copy.setDate(copy.getDate() + n);
                return copy;
            }

            function isSameDay(a, b) {
                return toDateStr(a) === toDateStr(b);
            }

            function formatDateDisplay(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = monthNames[date.getMonth()];
                const year = date.getFullYear();
                return `${day} ${month} ${year}`;
            }

            // ─── toast ──────────────────────────────────────────────────────
            function showToast(message, isError = false) {
                toast.textContent = message;
                toast.className = `toast ${isError ? 'error' : ''}`;
                // force reflow
                void toast.offsetWidth;
                toast.classList.add('show');
                clearTimeout(toastTimer);
                toastTimer = setTimeout(() => toast.classList.remove('show'), 2800);
            }

            // ─── render skeleton ──────────────────────────────────────────
            function renderSkeleton() {
                grid.innerHTML = Array.from({ length: 7 }).map((_, i) => `
                    <div class="day-col">
                        <div class="day-head">
                            <div class="day-info">
                                <div class="dname">${dayNames[i]}</div>
                                <div class="dnum">--</div>
                            </div>
                            <div class="badge-wrap">
                                <span class="count-badge">0</span>
                            </div>
                        </div>
                        <div class="day-body">
                            <div class="day-skeleton"></div>
                            <div class="day-skeleton"></div>
                        </div>
                    </div>
                `).join('');
            }

    
            // ─── render week ──────────────────────────────────────────────
            function renderWeek() {
                const weekEnd = addDays(currentWeekStart, 6);
                weekLabel.textContent =
                    `${formatDateDisplay(currentWeekStart)} – ${formatDateDisplay(weekEnd)}`;

                const today = new Date();
                let html = '';

                for (let i = 0; i < 7; i++) {
                    const day = addDays(currentWeekStart, i);
                    const dateStr = toDateStr(day);
                    const items = allSchedules
                        .filter(s => (s.date || '').slice(0, 10) === dateStr)
                        .sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));

                    const isToday = isSameDay(day, today);
                    const count = items.length;
                    const pct = Math.min(100, Math.round((count / 4) * 100));

                    html += `
                        <div class="day-col ${isToday ? 'today' : ''}">
                            <div class="day-head">
                                <div class="day-info">
                                    <div class="dname">${dayNames[i]}</div>
                                    <div class="dnum">${String(day.getDate()).padStart(2, '0')}</div>
                                </div>
                                <div class="badge-wrap">
                                    <span class="count-badge">${count}</span>
                                    <div class="progress-mini"><div class="fill" style="width:${pct}%;"></div></div>
                                </div>
                                <button type="button" class="day-add" data-date="${dateStr}">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                                </button>
                            </div>
                            <div class="day-body">
                                ${count ? items.map(itemCardHtml).join('') : `
                                    <div class="day-empty">
                                        <div class="empty-icon">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><path d="M4 4h16v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 8h8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg>
                                        </div>
                                        <div>Belum ada jadwal</div>
                                        <div class="quick-actions">
                                            <button class="mini-btn" data-action="add" data-date="${dateStr}">+ Jadwal</button>
                                        </div>
                                    </div>
                                `}
                            </div>
                        </div>
                    `;
                }

                grid.innerHTML = html;

                // Stagger entrance
                Array.from(grid.children).forEach((el, idx) => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(6px)';
                    setTimeout(() => {
                        el.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                        el.style.opacity = '1';
                        el.style.transform = 'translateY(0)';
                    }, 50 * idx);
                });

                // Attach quick actions
                grid.querySelectorAll('.mini-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const action = btn.dataset.action;
                        const date = btn.dataset.date;
                        if (action === 'add') {
                            openModalForDate(date);
                        } else if (action === 'break') {
                            showToast('Istirahat 15 menit ditambahkan (demo)');
                        } else if (action === 'note') {
                            window.location.href = '/note?date=' + date;
                        }
                    });
                });

                // Attach day-add buttons
                grid.querySelectorAll('.day-add').forEach(b => {
                    b.addEventListener('click', (e) => {
                        e.stopPropagation();
                        openModalForDate(b.dataset.date);
                    });
                });
            }

            // ─── item card HTML ───────────────────────────────────────────
            function itemCardHtml(item) {
                const color = item.color || '#E15B3F';
                const time = (item.start_time || item.end_time) ?
                    `${item.start_time || '--:--'} – ${item.end_time || '--:--'}` :
                    '';
                const todoGenerated = Boolean(item.todo_generated || (item.todos_count ?? 0) > 0);
                const todoBtn = todoGenerated
                    ? `<button type="button" class="todo-btn is-done" disabled title="Todo sudah dibuat">Todo ✓</button>`
                    : `<button type="button" class="todo-btn" data-action="generate-todo" data-id="${item.id}" title="Generate Todo">+ Todo</button>`;
                return `
                    <div class="item-card" style="border-left-color:${color};">
                        ${time ? `<div class="item-time">${time}</div>` : ''}
                        <div class="item-title">${escapeHtml(item.title || '')}</div>
                        ${item.description ? `<div class="item-desc">${escapeHtml(item.description)}</div>` : ''}
                        <div class="item-actions">
                            ${todoBtn}
                            <button type="button" class="edit-btn" data-action="edit" data-id="${item.id}">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 20l1-4.2L15.5 5.3a1.5 1.5 0 012.1 0l1.1 1.1a1.5 1.5 0 010 2.1L8.2 19l-4.2 1z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                            </button>
                            <button type="button" class="del-btn" data-action="delete" data-id="${item.id}">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M5 7h14M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                `;
            }

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str;
                return div.innerHTML;
            }

            // ─── modal helpers ────────────────────────────────────────────
            function openModalForDate(dateStr) {
                document.getElementById('date').value = dateStr;
                modalTitle.textContent = 'Tambah Jadwal';
                idInput.value = '';
                form.reset();
                colorInput.value = '#E15B3F';
                editingId = null;
                submitBtn.textContent = 'Simpan';
                formMsg.textContent = '';
                formMsg.className = 'form-msg';
                document.getElementById('start_time_error').textContent = '';
                document.getElementById('end_time_error').textContent = '';
                modal.classList.add('open');
            }

            function openModal(mode, item = null) {
                form.reset();
                colorInput.value = '#E15B3F';
                formMsg.textContent = '';
                formMsg.className = 'form-msg';
                document.getElementById('start_time_error').textContent = '';
                document.getElementById('end_time_error').textContent = '';

                if (mode === 'edit' && item) {
                    editingId = item.id;
                    modalTitle.textContent = 'Edit Jadwal';
                    submitBtn.textContent = 'Perbarui';
                    document.getElementById('title').value = item.title || '';
                    document.getElementById('description').value = item.description || '';
                    document.getElementById('date').value = (item.date || '').slice(0, 10);
                    document.getElementById('start_time').value = item.start_time || '';
                    document.getElementById('end_time').value = item.end_time || '';
                    document.getElementById('priority').value = item.priority || 'medium';
                    colorInput.value = item.color || '#E15B3F';
                } else {
                    editingId = null;
                    modalTitle.textContent = 'Tambah Jadwal';
                    submitBtn.textContent = 'Simpan';
                    const today = new Date();
                    document.getElementById('date').value = toDateStr(today);
                }
                modal.classList.add('open');
            }

            function closeModal() {
                modal.classList.remove('open');
            }

            // ─── load data ────────────────────────────────────────────────
            async function loadSchedules() {
                renderSkeleton();
                try {
                    const res = await fetch('/planner-api/schedules', { headers: { Accept: 'application/json' } });
                    const result = await res.json();
                    allSchedules = (result.data || []).map(item => ({
                        ...item,
                        date: item.date ? item.date.slice(0, 10) : '',
                        todo_generated: (item.todos_count ?? 0) > 0,
                    }));
                    renderWeek();
                } catch (e) {
                    grid.innerHTML =
                        `<div class="day-empty" style="grid-column:1/-1;padding:40px;">Gagal memuat data jadwal.</div>`;
                    console.error('loadSchedules error:', e);
                }
            }

            async function loadTodos() {
                const weekDateStr = toDateStr(currentWeekStart);
                try {
                    const res = await fetch(`/planner-api/todos?week_date=${weekDateStr}`, {
                        headers: { Accept: 'application/json' }
                    });
                    const result = await res.json();
                    const todos = result.data || [];
                    renderTodoOverview(todos);
                } catch (e) {
                    todoOverview.innerHTML =
                        `<div style="padding:20px;text-align:center;color:var(--ink-soft);">Gagal memuat todo.</div>`;
                    console.error('loadTodos error:', e);
                }
            }

            function renderTodoOverview(todos) {
                todoCount.textContent = todos.length;
                if (todos.length === 0) {
                    todoOverview.innerHTML =
                        `<div style="padding:20px;text-align:center;color:var(--ink-soft);"><svg width="18" height="18" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2"/></svg> Belum ada todo untuk minggu ini.</div>`;
                    return;
                }

                let html = '';
                todos.forEach(todo => {
                    const scheduleLabel = todo.schedule_title ?
                        `<span class="tag">${escapeHtml(todo.schedule_title)}</span>` :
                        '';
                    const doneClass = todo.completed ? 'done' : '';
                    html += `
                        <div class="todo-item">
                            <input type="checkbox" ${todo.completed ? 'checked' : ''} data-todo-id="${todo.id}" class="todo-checkbox">
                            <div class="todo-content">
                                <div class="todo-title ${doneClass}">${escapeHtml(todo.title || '')}</div>
                                <div class="todo-meta">
                                                    ${todo.due_date ? `<span style="display:inline-flex;align-items:center;gap:6px;color:var(--ink-soft);font-size:0.85rem;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" style="vertical-align:middle"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.2" fill="none"/></svg> ${todo.due_date}</span>` : ''}
                                    ${scheduleLabel}
                                </div>
                            </div>
                        </div>
                    `;
                });
                todoOverview.innerHTML = html;

                // checkbox events
                todoOverview.querySelectorAll('.todo-checkbox').forEach(cb => {
                    cb.addEventListener('change', async (e) => {
                        const todoId = e.target.dataset.todoId;
                        try {
                            const res = await fetch(`/planner-api/todos/${todoId}?toggle=1`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    Accept: 'application/json',
                                }
                            });
                            const result = await res.json();
                            if (res.ok) {
                                loadTodos();
                                showToast(result.message || 'Status todo diperbarui.');
                            } else {
                                showToast(result.message || 'Gagal mengubah status', true);
                            }
                        } catch (err) {
                            showToast('Gagal mengubah status', true);
                            console.error(err);
                        }
                    });
                });
            }

            // ─── event listeners ──────────────────────────────────────────

            // Modal controls
            document.getElementById('addBtn').addEventListener('click', () => {
                const today = new Date();
                openModal('add', null);
                document.getElementById('date').value = toDateStr(today);
            });
            document.getElementById('closeModalBtn').addEventListener('click', closeModal);
            document.getElementById('cancelBtn').addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

            // Color swatches
            document.querySelectorAll('.swatch').forEach(sw => {
                sw.addEventListener('click', () => {
                    colorInput.value = sw.dataset.color;
                });
            });

            // Week navigation
            document.getElementById('prevWeekBtn').addEventListener('click', () => {
                currentWeekStart = addDays(currentWeekStart, -7);
                renderWeek();
                loadTodos();
            });
            document.getElementById('nextWeekBtn').addEventListener('click', () => {
                currentWeekStart = addDays(currentWeekStart, 7);
                renderWeek();
                loadTodos();
            });
            document.getElementById('todayBtn').addEventListener('click', () => {
                currentWeekStart = getMonday(new Date());
                renderWeek();
                loadTodos();
            });

            // Grid events (edit/delete via delegation)
            grid.addEventListener('click', async (e) => {
                const generateBtn = e.target.closest('[data-action="generate-todo"]');
                if (generateBtn) {
                    const id = generateBtn.dataset.id;
                    generateBtn.disabled = true;
                    try {
                        const res = await fetch(`/planner-api/schedules/${id}/generate-todo`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                        });
                        const result = await res.json();
                        if (!res.ok) throw new Error(result.message || 'Gagal generate todo');
                        const idx = allSchedules.findIndex(s => String(s.id) === id);
                        if (idx > -1) {
                            allSchedules[idx].todo_generated = true;
                            allSchedules[idx].todos_count = 1;
                        }
                        renderWeek();
                        loadTodos();
                        showToast(result.message || 'Todo berhasil dibuat dari jadwal.');
                    } catch (err) {
                        showToast(err.message, true);
                        generateBtn.disabled = false;
                    }
                    return;
                }

                const editBtn = e.target.closest('[data-action="edit"]');
                if (editBtn) {
                    const item = allSchedules.find(s => String(s.id) === editBtn.dataset.id);
                    if (item) openModal('edit', item);
                    return;
                }

                const delBtn = e.target.closest('[data-action="delete"]');
                if (delBtn) {
                    if (!confirm('Hapus jadwal ini?')) return;
                    const id = delBtn.dataset.id;
                    try {
                        const res = await fetch(`/planner-api/schedules/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                        });
                        const result = await res.json();
                        if (!res.ok) throw new Error(result.message || 'Gagal hapus');
                        allSchedules = allSchedules.filter(s => String(s.id) !== id);
                        renderWeek();
                        loadTodos();
                        showToast(result.message || 'Jadwal dihapus.');
                    } catch (err) {
                        showToast(err.message, true);
                        console.error(err);
                    }
                }
            });

            // Form submit
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const startTime = document.getElementById('start_time').value;
                const endTime = document.getElementById('end_time').value;
                const startTimeError = document.getElementById('start_time_error');
                const endTimeError = document.getElementById('end_time_error');
                startTimeError.textContent = '';
                endTimeError.textContent = '';

                if (startTime && endTime && startTime > endTime) {
                    endTimeError.textContent = 'Selesai harus setelah mulai.';
                    return;
                }

                const dateInput = document.getElementById('date').value;
                if (!dateInput) {
                    formMsg.textContent = 'Tanggal harus diisi.';
                    formMsg.className = 'form-msg error';
                    return;
                }

                const payload = {
                    title: document.getElementById('title').value.trim(),
                    description: document.getElementById('description').value.trim(),
                    date: dateInput,
                    start_time: startTime,
                    end_time: endTime,
                    priority: document.getElementById('priority').value,
                    color: colorInput.value,
                };

                if (!payload.title) {
                    formMsg.textContent = 'Judul harus diisi.';
                    formMsg.className = 'form-msg error';
                    return;
                }

                const url = editingId ? `/planner-api/schedules/${editingId}` : '/planner-api/schedules';
                const method = editingId ? 'PUT' : 'POST';

                submitBtn.disabled = true;
                formMsg.textContent = '';
                formMsg.className = 'form-msg';

                try {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            Accept: 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });
                    const result = await res.json();
                    if (!res.ok) throw new Error(result.message || 'Gagal menyimpan');

                    const savedData = result.data || { id: editingId || Date.now(), ...payload };
                    savedData.date = savedData.date ? savedData.date.slice(0, 10) : '';

                    if (editingId) {
                        const idx = allSchedules.findIndex(s => String(s.id) === String(editingId));
                        if (idx > -1) allSchedules[idx] = savedData;
                        else allSchedules.push(savedData);
                    } else {
                        allSchedules.push(savedData);
                    }

                    renderWeek();
                    loadTodos();
                    showToast(result.message || 'Jadwal tersimpan.');
                    closeModal();
                } catch (err) {
                    formMsg.textContent = err.message || 'Terjadi kesalahan.';
                    formMsg.className = 'form-msg error';
                    console.error(err);
                } finally {
                    submitBtn.disabled = false;
                }
            });

            // ─── init ──────────────────────────────────────────────────────
            renderSkeleton();
            loadSchedules();
            loadTodos();

            console.log('Schedule initialized');
            console.log('Timezone offset:', new Date().getTimezoneOffset());
            console.log('Today:', toDateStr(new Date()));
            console.log('Week start:', toDateStr(currentWeekStart));

        })();
    
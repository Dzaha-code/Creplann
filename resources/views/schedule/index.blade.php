<x-app-layout>
    <x-slot name="header">Schedule</x-slot>

    <style>
        .week-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:22px;flex-wrap:wrap;}
        .week-nav{display:flex;align-items:center;gap:10px;}
        .week-nav button{
            width:36px;height:36px;border-radius:50%;background:#fff;border:1px solid var(--line);cursor:pointer;
            display:flex;align-items:center;justify-content:center;color:var(--ink-soft);transition:background .2s ease,color .2s ease;
        }
        .week-nav button:hover{background:var(--paper-soft);color:var(--ink);}
        .week-nav button svg{width:16px;height:16px;}
        .week-nav .today-btn{
            width:auto;border-radius:999px;padding:0 14px;font-size:0.82rem;font-weight:700;font-family:inherit;
        }
        .week-label{font-family:'Fraunces', serif;font-weight:600;font-size:1.1rem;white-space:nowrap;}

        .btn{
            display:inline-flex;align-items:center;justify-content:center;gap:6px;
            padding:12px 22px;border-radius:14px;font-weight:700;font-size:0.9rem;
            border:none;cursor:pointer;transition:transform .15s ease, background .2s ease;font-family:inherit;
        }
        .btn:hover{transform:translateY(-1px);}
        .btn-solid{background:var(--coral);color:#fff;box-shadow:0 10px 22px -10px rgba(225,91,63,.6);}
        .btn-solid:hover{background:var(--coral-ink);}
        .btn-ghost{background:#fff;color:var(--ink);border:1.5px solid var(--line);}
        .btn-ghost:hover{border-color:var(--ink);}

        /* Weekly grid */
        .weekly-grid{display:grid;grid-template-columns:repeat(7, minmax(150px, 1fr));gap:12px;overflow-x:auto;padding-bottom:8px;}
        .day-col{background:#fff;border:1px solid var(--line);border-radius:var(--radius);display:flex;flex-direction:column;min-height:340px;transition:border-color .2s ease, box-shadow .2s ease;}
        .day-col.today{border-color:var(--coral);box-shadow:0 12px 26px -18px rgba(225,91,63,.4);}
        .day-head{padding:14px 14px 12px;border-bottom:1px solid var(--line);text-align:center;position:relative;}
        .day-head .dname{font-size:0.76rem;font-weight:700;color:var(--ink-soft);text-transform:uppercase;letter-spacing:.03em;}
        .day-head .dnum{font-family:'Fraunces', serif;font-weight:600;font-size:1.25rem;margin-top:2px;}
        .day-col.today .dname, .day-col.today .dnum{color:var(--coral-ink);}
        .day-add{
            position:absolute;top:10px;right:10px;width:24px;height:24px;border-radius:50%;
            background:var(--paper-soft);border:none;color:var(--ink-soft);cursor:pointer;
            display:flex;align-items:center;justify-content:center;transition:background .2s ease,color .2s ease;
        }
        .day-add:hover{background:var(--coral);color:#fff;}
        .day-add svg{width:13px;height:13px;}

        .day-body{padding:10px;display:flex;flex-direction:column;gap:8px;flex:1;}
        .item-card{background:var(--paper-soft);border-radius:12px;padding:10px 11px;border-left:3px solid var(--coral);animation:fadeIn .25s ease;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(3px);}to{opacity:1;transform:translateY(0);}}
        .item-time{font-size:0.72rem;font-weight:700;color:var(--ink-soft);margin-bottom:2px;}
        .item-title{font-size:0.85rem;font-weight:600;line-height:1.3;margin-bottom:2px;word-break:break-word;}
        .item-desc{font-size:0.74rem;color:var(--ink-soft);margin-bottom:6px;line-height:1.4;}
        .item-actions{display:flex;gap:4px;}
        .item-actions button{border:none;background:transparent;color:var(--ink-soft);cursor:pointer;padding:4px;border-radius:6px;display:flex;align-items:center;justify-content:center;}
        .item-actions button svg{width:13px;height:13px;}
        .item-actions .edit-btn:hover{background:rgba(225,91,63,.1);color:var(--coral-ink);}
        .item-actions .del-btn:hover{background:rgba(196,67,46,.1);color:var(--danger);}

        .day-empty{margin:auto;text-align:center;padding:14px 6px;color:var(--ink-soft);font-size:0.78rem;}
        .day-skeleton{background:linear-gradient(90deg, var(--paper-soft) 25%, #efe7d8 37%, var(--paper-soft) 63%);background-size:400% 100%;animation:shimmer 1.4s ease infinite;border-radius:12px;height:52px;margin:0 10px 8px;}
        @keyframes shimmer{0%{background-position:100% 0;}100%{background-position:0 0;}}

        .toast{
            position:fixed;bottom:24px;left:50%;transform:translateX(-50%) translateY(20px);
            background:var(--ink);color:#fff;padding:12px 22px;border-radius:999px;font-size:0.88rem;font-weight:600;
            opacity:0;pointer-events:none;transition:opacity .25s ease, transform .25s ease;z-index:200;
        }
        .toast.show{opacity:1;transform:translateX(-50%) translateY(0);}
        .toast.error{background:var(--danger);}

        /* Modal */
        .modal-backdrop{display:none;position:fixed;inset:0;background:rgba(36,31,26,0.35);z-index:100;align-items:center;justify-content:center;padding:20px;}
        .modal-backdrop.open{display:flex;}
        .modal-panel{background:var(--paper);border:1px solid var(--line);border-radius:var(--radius);width:100%;max-width:440px;padding:26px 26px 22px;box-shadow:0 30px 60px -20px rgba(36,31,26,.4);max-height:90vh;overflow-y:auto;}
        .modal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
        .modal-head h3{font-family:'Fraunces', serif;font-weight:600;font-size:1.2rem;margin:0;}
        .modal-close{border:none;background:var(--paper-soft);width:30px;height:30px;border-radius:50%;cursor:pointer;color:var(--ink-soft);font-size:1.1rem;line-height:1;}
        .modal-close:hover{background:var(--line);}

        .field{margin-bottom:16px;}
        .field label{display:block;font-size:0.83rem;font-weight:600;color:var(--ink);margin-bottom:6px;}
        .field input, .field select, .field textarea{width:100%;padding:10px 13px;border-radius:12px;border:1.5px solid var(--line);background:#fff;color:var(--ink);font-family:inherit;font-size:0.92rem;}
        .field input:focus, .field select:focus, .field textarea:focus{outline:none;border-color:var(--coral);}
        .field-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}

        .color-row{display:flex;align-items:center;gap:10px;}
        .swatch-row{display:flex;gap:8px;}
        .swatch{width:24px;height:24px;border-radius:50%;cursor:pointer;border:2px solid transparent;transition:transform .15s ease;flex-shrink:0;}
        .swatch:hover{transform:scale(1.1);}
        .swatch.active{border-color:var(--ink);}
        #color{width:38px;height:38px;padding:2px;border-radius:50%;border:1.5px solid var(--line);cursor:pointer;flex-shrink:0;}

        .modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px;}
        .form-msg{font-size:0.82rem;font-weight:600;margin-top:2px;min-height:18px;}
        .form-msg.error{color:var(--danger);}
        .form-msg.success{color:var(--sage);}

        @media (max-width:900px){.weekly-grid{grid-template-columns:repeat(7, 220px);}}
    </style>

    <div class="wrap">
        <div class="week-toolbar">
            <div class="week-nav">
                <button type="button" id="prevWeekBtn" title="Pekan sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <span class="week-label" id="weekLabel">Memuat…</span>
                <button type="button" id="nextWeekBtn" title="Pekan berikutnya">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button type="button" id="todayBtn" class="today-btn btn-ghost">Hari ini</button>
            </div>
            <button type="button" class="btn btn-solid" id="addBtn">+ Tambah Jadwal</button>
        </div>

        <div class="weekly-grid" id="weeklyGrid">
            {{-- diisi oleh JS --}}
        </div>

        {{-- Todo Overview --}}
        <div style="margin-top:32px;padding:22px;background:var(--paper-soft);border-radius:var(--radius);border:1px solid var(--line);">
            <h3 style="font-family:'Fraunces',serif;font-weight:600;font-size:1.1rem;margin-bottom:14px;">Todo Mingguan</h3>
            <div id="todoOverview" style="display:flex;flex-direction:column;gap:10px;">
                <div style="padding:20px;text-align:center;color:var(--ink-soft);">Memuat todo…</div>
            </div>
        </div>
    </div>

    {{-- Modal tambah / edit --}}
    <div class="modal-backdrop" id="scheduleModal">
        <div class="modal-panel">
            <div class="modal-head">
                <h3 id="modalTitle">Tambah Jadwal</h3>
                <button type="button" class="modal-close" id="closeModalBtn">&times;</button>
            </div>

            <form id="scheduleForm">
                <input type="hidden" id="schedule_id">

                <div class="field">
                    <label for="title">Judul</label>
                    <input type="text" id="title" name="title" required>
                </div>

                <div class="field">
                    <label for="description">Deskripsi <span style="font-weight:400;color:var(--ink-soft);">(opsional)</span></label>
                    <textarea id="description" name="description" rows="2"></textarea>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="date">Tanggal</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="field">
                        <label for="priority">Prioritas</label>
                        <select id="priority" name="priority">
                            <option value="low">Rendah</option>
                            <option value="medium" selected>Normal</option>
                            <option value="high">Tinggi</option>
                        </select>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="start_time">Mulai</label>
                        <input type="time" id="start_time" name="start_time">
                        <div class="form-msg error" id="start_time_error"></div>
                    </div>
                    <div class="field">
                        <label for="end_time">Selesai</label>
                        <input type="time" id="end_time" name="end_time">
                        <div class="form-msg error" id="end_time_error"></div>
                    </div>
                </div>

                <div class="field">
                    <label>Warna</label>
                    <div class="color-row">
                        <input type="color" id="color" name="color" value="#E15B3F">
                        <div class="swatch-row">
                            <span class="swatch" style="background:#E15B3F" data-color="#E15B3F"></span>
                            <span class="swatch" style="background:#7E9083" data-color="#7E9083"></span>
                            <span class="swatch" style="background:#E3A93B" data-color="#E3A93B"></span>
                            <span class="swatch" style="background:#5B7FA6" data-color="#5B7FA6"></span>
                        </div>
                    </div>
                </div>

                <div class="form-msg" id="formMsg"></div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-ghost" id="cancelBtn">Batal</button>
                    <button type="submit" class="btn btn-solid" id="submitBtn">Simpan Jadwal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    (function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const dayNames = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        const grid = document.getElementById('weeklyGrid');
        const weekLabel = document.getElementById('weekLabel');
        const todoOverview = document.getElementById('todoOverview');
        const modal = document.getElementById('scheduleModal');
        const form = document.getElementById('scheduleForm');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const formMsg = document.getElementById('formMsg');
        const idInput = document.getElementById('schedule_id');
        const colorInput = document.getElementById('color');

        let allSchedules = [];
        let currentWeekStart = getMonday(new Date());
        let editingId = null;
        let loaded = false;

        function getMonday(d) {
            const date = new Date(d);
            const day = date.getDay(); // 0 = Sunday
            const diff = day === 0 ? -6 : 1 - day;
            date.setDate(date.getDate() + diff);
            date.setHours(0, 0, 0, 0);
            return date;
        }

        function toDateStr(d) {
            return d.toISOString().split('T')[0];
        }

        function addDays(d, n) {
            const copy = new Date(d);
            copy.setDate(copy.getDate() + n);
            return copy;
        }

        function isSameDay(a, b) {
            return toDateStr(a) === toDateStr(b);
        }

        function renderSkeleton() {
            grid.innerHTML = Array.from({ length: 7 }).map((_, i) => `
                <div class="day-col">
                    <div class="day-head">
                        <div class="dname">${dayNames[i]}</div>
                        <div class="dnum">--</div>
                    </div>
                    <div class="day-body">
                        <div class="day-skeleton"></div>
                        <div class="day-skeleton"></div>
                    </div>
                </div>
            `).join('');
        }

        function renderWeek() {
            const weekEnd = addDays(currentWeekStart, 6);
            weekLabel.textContent =
                `${currentWeekStart.getDate().toString().padStart(2,'0')} ${monthNames[currentWeekStart.getMonth()]} – ${weekEnd.getDate().toString().padStart(2,'0')} ${monthNames[weekEnd.getMonth()]} ${weekEnd.getFullYear()}`;

            const today = new Date();
            let html = '';

            for (let i = 0; i < 7; i++) {
                const day = addDays(currentWeekStart, i);
                const dateStr = toDateStr(day);
                const items = allSchedules
                    .filter(s => (s.date || '').slice(0, 10) === dateStr)
                    .sort((a, b) => (a.start_time || '').localeCompare(b.start_time || ''));

                const isToday = isSameDay(day, today);

                html += `
                    <div class="day-col ${isToday ? 'today' : ''}">
                        <div class="day-head">
                            <div class="dname">${dayNames[i]}</div>
                            <div class="dnum">${day.getDate().toString().padStart(2,'0')}</div>
                            <button type="button" class="day-add" data-date="${dateStr}" title="Tambah jadwal ${dayNames[i]}">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
                            </button>
                        </div>
                        <div class="day-body">
                            ${items.length ? items.map(itemCardHtml).join('') : '<div class="day-empty">Belum ada jadwal</div>'}
                        </div>
                    </div>
                `;
            }
            grid.innerHTML = html;
        }

        function itemCardHtml(item) {
            const color = item.color || '#E15B3F';
            const time = (item.start_time || item.end_time)
                ? `${item.start_time || '--:--'}–${item.end_time || '--:--'}`
                : '';
            return `
                <div class="item-card" style="border-left-color:${color};">
                    ${time ? `<div class="item-time">${time}</div>` : ''}
                    <div class="item-title">${escapeHtml(item.title || '')}</div>
                    ${item.description ? `<div class="item-desc">${escapeHtml(item.description)}</div>` : ''}
                    <div class="item-actions">
                        <button type="button" class="edit-btn" data-action="edit" data-id="${item.id}" title="Edit">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M4 20l1-4.2L15.5 5.3a1.5 1.5 0 012.1 0l1.1 1.1a1.5 1.5 0 010 2.1L8.2 19l-4.2 1z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                        </button>
                        <button type="button" class="del-btn" data-action="delete" data-id="${item.id}" title="Hapus">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M5 7h14M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
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

        function showToast(message, isError = false) {
            let toast = document.querySelector('.toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.className = 'toast';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.className = `toast ${isError ? 'error' : ''}`;
            requestAnimationFrame(() => toast.classList.add('show'));
            clearTimeout(toast._timer);
            toast._timer = setTimeout(() => toast.classList.remove('show'), 2600);
        }

        async function loadSchedules() {
            renderSkeleton();
            try {
                const res = await fetch('/planner-api/schedules', { headers: { Accept: 'application/json' } });
                const result = await res.json();
                allSchedules = result.data || [];
                loaded = true;
                renderWeek();
            } catch (e) {
                grid.innerHTML = `<div class="day-empty" style="grid-column:1/-1;padding:40px;">Gagal memuat data jadwal.</div>`;
            }
        }

        async function loadTodos() {
            const weekStart = currentWeekStart;
            const weekDateStr = toDateStr(weekStart);
            
            try {
                const res = await fetch(`/planner-api/todos?week_date=${weekDateStr}`, { 
                    headers: { Accept: 'application/json' } 
                });
                const result = await res.json();
                const todos = result.data || [];
                renderTodoOverview(todos);
            } catch (e) {
                todoOverview.innerHTML = `<div style="padding:20px;text-align:center;color:var(--ink-soft);">Gagal memuat todo.</div>`;
            }
        }

        function renderTodoOverview(todos) {
            if (todos.length === 0) {
                todoOverview.innerHTML = `<div style="padding:20px;text-align:center;color:var(--ink-soft);">Belum ada todo untuk minggu ini.</div>`;
                return;
            }

            const html = todos.map(todo => {
                const scheduleLabel = todo.schedule_title ? ` <span style="font-size:0.8rem;color:var(--ink-soft);">(${escapeHtml(todo.schedule_title)})</span>` : '';
                const checkboxClass = todo.completed ? 'style="text-decoration:line-through;color:var(--ink-soft);"' : '';
                return `
                    <div style="padding:12px 14px;background:#fff;border-radius:10px;border:1px solid var(--line);display:flex;align-items:center;gap:12px;">
                        <input type="checkbox" ${todo.completed ? 'checked' : ''} data-todo-id="${todo.id}" class="todo-checkbox" style="width:18px;height:18px;cursor:pointer;">
                        <div style="flex:1;">
                            <div ${checkboxClass}>${escapeHtml(todo.title || '')}</div>
                            ${todo.due_date ? `<div style="font-size:0.8rem;color:var(--ink-soft);">Jatuh tempo: ${todo.due_date}${scheduleLabel}</div>` : scheduleLabel}
                        </div>
                    </div>
                `;
            }).join('');

            todoOverview.innerHTML = html;

            // Tambahkan event listener untuk checkbox
            todoOverview.querySelectorAll('.todo-checkbox').forEach(checkbox => {
                checkbox.addEventListener('change', async (e) => {
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
                    }
                });
            });
        }

        function openModal(mode, item = null, presetDate = null) {
            form.reset();
            colorInput.value = '#E15B3F';
            formMsg.textContent = '';
            formMsg.className = 'form-msg';

            if (mode === 'edit' && item) {
                editingId = item.id;
                modalTitle.textContent = 'Edit Jadwal';
                submitBtn.textContent = 'Perbarui Jadwal';
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
                submitBtn.textContent = 'Simpan Jadwal';
                if (presetDate) document.getElementById('date').value = presetDate;
            }
            modal.classList.add('open');
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        document.getElementById('addBtn').addEventListener('click', () => openModal('add', null, toDateStr(new Date())));
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);
        document.getElementById('cancelBtn').addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

        document.querySelectorAll('.swatch').forEach(sw => {
            sw.addEventListener('click', () => { colorInput.value = sw.dataset.color; });
        });

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

        grid.addEventListener('click', async (e) => {
            const addBtn = e.target.closest('.day-add');
            if (addBtn) {
                openModal('add', null, addBtn.dataset.date);
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
                    if (!res.ok) throw new Error(result.message || 'Gagal menghapus jadwal');

                    allSchedules = allSchedules.filter(s => String(s.id) !== id);
                    renderWeek();
                    loadTodos();
                    showToast(result.message || 'Jadwal dihapus.');
                } catch (err) {
                    showToast(err.message, true);
                }
            }
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Validasi time
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const startTimeError = document.getElementById('start_time_error');
            const endTimeError = document.getElementById('end_time_error');
            
            startTimeError.textContent = '';
            endTimeError.textContent = '';
            
            if (startTime && endTime && startTime > endTime) {
                endTimeError.textContent = 'Jam selesai harus setelah jam mulai.';
                return;
            }
            
            const payload = {
                title: document.getElementById('title').value,
                description: document.getElementById('description').value,
                date: document.getElementById('date').value,
                start_time: startTime,
                end_time: endTime,
                priority: document.getElementById('priority').value,
                color: colorInput.value,
            };

            const url = editingId ? `/planner-api/schedules/${editingId}` : '/planner-api/schedules';
            const method = editingId ? 'PUT' : 'POST';

            submitBtn.disabled = true;
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
                if (!res.ok) {
                    throw new Error(result.message || 'Gagal menyimpan jadwal');
                }

                if (editingId) {
                    const idx = allSchedules.findIndex(s => String(s.id) === String(editingId));
                    if (idx > -1) allSchedules[idx] = result.data || { ...allSchedules[idx], ...payload };
                } else {
                    allSchedules.push(result.data || { id: Date.now(), ...payload });
                }

                renderWeek();
                loadTodos();
                showToast(result.message || 'Jadwal tersimpan.');
                closeModal();
            } catch (err) {
                formMsg.textContent = err.message;
                formMsg.className = 'form-msg error';
            } finally {
                submitBtn.disabled = false;
            }
        });

        renderSkeleton();
        loadSchedules();
        loadTodos();
    })();
    </script>
</x-app-layout>
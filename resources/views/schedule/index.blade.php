<x-app-layout>
    <x-slot name="header">Schedule</x-slot>

    <style>
        /* ── root variables (fallback) ── */
        :root {
            --coral: #E15B3F;
            --coral-ink: #b84a33;
            --ink: #241f1a;
            --ink-soft: #6b6560;
            --paper: #fcf9f5;
            --paper-soft: #f5f0e8;
            --line: #e5e0d8;
            --radius: 16px;
            --danger: #c44b2e;
            --sage: #7E9083;
        }

        /* ── toolbar ── */
        .week-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 28px;
            flex-wrap: wrap;
        }
        .week-nav {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .week-nav button {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #fff;
            border: 1px solid var(--line);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-soft);
            transition: all 0.2s ease;
        }
        .week-nav button:hover {
            background: var(--paper-soft);
            color: var(--ink);
            border-color: var(--ink-soft);
        }
        .week-nav button svg {
            width: 18px;
            height: 18px;
        }
        .week-nav .today-btn {
            width: auto;
            border-radius: 999px;
            padding: 0 18px;
            font-size: 0.8rem;
            font-weight: 700;
            font-family: inherit;
            background: #fff;
            border: 1.5px solid var(--line);
            color: var(--ink);
            height: 38px;
        }
        .week-nav .today-btn:hover {
            border-color: var(--coral);
            color: var(--coral);
        }
        .week-label {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.1rem;
            white-space: nowrap;
            letter-spacing: -0.01em;
            color: var(--ink);
            padding: 0 4px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 24px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: inherit;
            text-decoration: none;
        }
        .btn-solid {
            background: var(--coral);
            color: #fff;
            box-shadow: 0 8px 20px -8px rgba(225, 91, 63, 0.4);
        }
        .btn-solid:hover {
            background: var(--coral-ink);
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -10px rgba(225, 91, 63, 0.5);
        }
        .btn-ghost {
            background: #fff;
            color: var(--ink);
            border: 1.5px solid var(--line);
        }
        .btn-ghost:hover {
            border-color: var(--ink);
            background: var(--paper-soft);
        }

        /* ── grid ── */
        .weekly-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 14px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .day-col {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            display: flex;
            flex-direction: column;
            min-height: 320px;
            transition: border-color 0.2s ease, box-shadow 0.25s ease, transform 0.2s ease;
            overflow: hidden;
            position: relative;
        }
        .day-col:hover {
            border-color: #ccc;
            box-shadow: 0 8px 24px -12px rgba(36, 31, 26, 0.12);
        }
        .day-col.today {
            border-color: var(--coral);
            box-shadow: 0 12px 32px -16px rgba(225, 91, 63, 0.3);
        }

        .day-head {
            padding: 14px 16px 10px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
            position: relative;
            background: var(--paper-soft);
        }
        .day-head .dname {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--ink-soft);
        }
        .day-head .dnum {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.3rem;
            line-height: 1.2;
            color: var(--ink);
        }
        .day-col.today .dname,
        .day-col.today .dnum {
            color: var(--coral-ink);
        }

        .day-head .badge-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .day-head .count-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 28px;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--line);
            font-weight: 700;
            font-size: 0.75rem;
            color: var(--ink);
            padding: 0 8px;
        }
        .day-head .progress-mini {
            width: 48px;
            height: 4px;
            border-radius: 999px;
            background: var(--line);
            overflow: hidden;
        }
        .day-head .progress-mini .fill {
            height: 100%;
            border-radius: 999px;
            background: var(--coral);
            transition: width 0.6s ease;
            width: 0%;
        }

        .day-add {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-soft);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .day-add:hover {
            background: var(--coral);
            color: #fff;
            border-color: var(--coral);
            transform: scale(1.05);
        }
        .day-add svg {
            width: 14px;
            height: 14px;
        }

        .day-body {
            padding: 12px 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
            background: #fff;
        }

        /* ── item card ── */
        .item-card {
            background: var(--paper-soft);
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            animation: fadeIn 0.25s ease;
            border-left: 3px solid transparent;
            transition: all 0.18s ease;
            position: relative;
        }
        .item-card:hover {
            background: #ede8df;
            transform: translateX(2px);
        }
        .item-card .item-time {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--ink-soft);
            letter-spacing: 0.02em;
        }
        .item-card .item-title {
            font-size: 0.85rem;
            font-weight: 600;
            line-height: 1.25;
            color: var(--ink);
        }
        .item-card .item-desc {
            font-size: 0.74rem;
            color: var(--ink-soft);
            line-height: 1.35;
            margin-top: 2px;
        }
        .item-card .item-actions {
            display: flex;
            gap: 6px;
            justify-content: flex-end;
            margin-top: 6px;
        }
        .item-card .item-actions button {
            border: none;
            background: transparent;
            color: var(--ink-soft);
            cursor: pointer;
            padding: 4px 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }
        .item-card .item-actions button svg {
            width: 14px;
            height: 14px;
        }
        .item-card .item-actions .edit-btn:hover {
            background: rgba(225, 91, 63, 0.1);
            color: var(--coral-ink);
        }
        .item-card .item-actions .del-btn:hover {
            background: rgba(196, 67, 46, 0.1);
            color: var(--danger);
        }

        /* ── empty state ── */
        .day-empty {
            margin: auto 0;
            text-align: center;
            padding: 14px 6px;
            color: var(--ink-soft);
            font-size: 0.78rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        .day-empty .empty-icon {
            font-size: 1.6rem;
            opacity: 0.5;
        }
        .day-empty .quick-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .day-empty .quick-actions .mini-btn {
            background: transparent;
            border: 1px solid var(--line);
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            color: var(--ink-soft);
            font-family: inherit;
        }
        .day-empty .quick-actions .mini-btn:hover {
            background: var(--coral);
            color: #fff;
            border-color: var(--coral);
            transform: translateY(-1px);
        }

        .day-skeleton {
            background: linear-gradient(90deg, var(--paper-soft) 25%, #ede8df 37%, var(--paper-soft) 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease infinite;
            border-radius: 12px;
            height: 52px;
            margin: 4px 0;
        }
        @keyframes shimmer {
            0% {
                background-position: 100% 0;
            }
            100% {
                background-position: 0 0;
            }
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── todo overview ── */
        .todo-wrap {
            margin-top: 36px;
            padding: 22px 26px 26px;
            background: #fff;
            border-radius: var(--radius);
            border: 1px solid var(--line);
        }
        .todo-wrap h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0 0 16px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .todo-wrap h3 span {
            font-size: 0.75rem;
            font-weight: 700;
            font-family: system-ui, sans-serif;
            background: var(--coral);
            color: #fff;
            padding: 0 12px;
            border-radius: 999px;
            line-height: 22px;
        }
        .todo-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .todo-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            background: var(--paper-soft);
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .todo-item:hover {
            background: #ede8df;
        }
        .todo-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--coral);
            flex-shrink: 0;
        }
        .todo-item .todo-content {
            flex: 1;
            min-width: 0;
        }
        .todo-item .todo-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--ink);
        }
        .todo-item .todo-title.done {
            text-decoration: line-through;
            color: var(--ink-soft);
        }
        .todo-item .todo-meta {
            font-size: 0.75rem;
            color: var(--ink-soft);
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 2px;
        }
        .todo-item .todo-meta .tag {
            background: #fff;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid var(--line);
            font-size: 0.65rem;
            font-weight: 600;
        }

        /* ── toast ── */
        .toast {
            position: fixed;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: var(--ink);
            color: #fff;
            padding: 12px 28px;
            border-radius: 999px;
            font-size: 0.88rem;
            font-weight: 600;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease, transform 0.3s ease;
            z-index: 200;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast.error {
            background: var(--danger);
        }

        /* ── modal ── */
        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(36, 31, 26, 0.4);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(2px);
        }
        .modal-backdrop.open {
            display: flex;
        }
        .modal-panel {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: var(--radius);
            width: 100%;
            max-width: 460px;
            padding: 28px 30px 24px;
            box-shadow: 0 40px 80px -24px rgba(36, 31, 26, 0.35);
            max-height: 90vh;
            overflow-y: auto;
            animation: modalIn 0.25s ease;
        }
        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(12px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        .modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .modal-head h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.25rem;
            margin: 0;
        }
        .modal-close {
            border: none;
            background: var(--paper-soft);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            color: var(--ink-soft);
            font-size: 1.2rem;
            line-height: 1;
            transition: all 0.2s ease;
        }
        .modal-close:hover {
            background: var(--line);
            color: var(--ink);
        }

        .field {
            margin-bottom: 18px;
        }
        .field label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 5px;
        }
        .field input,
        .field select,
        .field textarea {
            width: 100%;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1.5px solid var(--line);
            background: #fff;
            color: var(--ink);
            font-family: inherit;
            font-size: 0.92rem;
            transition: border-color 0.2s ease;
        }
        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--coral);
            box-shadow: 0 0 0 3px rgba(225, 91, 63, 0.1);
        }
        .field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        .color-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .swatch-row {
            display: flex;
            gap: 8px;
        }
        .swatch {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }
        .swatch:hover {
            transform: scale(1.1);
        }
        .swatch.active {
            border-color: var(--ink);
        }
        #color {
            width: 40px;
            height: 40px;
            padding: 2px;
            border-radius: 50%;
            border: 1.5px solid var(--line);
            cursor: pointer;
            flex-shrink: 0;
        }
        .form-msg {
            font-size: 0.82rem;
            font-weight: 600;
            margin-top: 4px;
            min-height: 20px;
        }
        .form-msg.error {
            color: var(--danger);
        }
        .form-msg.success {
            color: var(--sage);
        }
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 22px;
        }

        /* ── responsive ── */
        @media (max-width: 1024px) {
            .weekly-grid {
                grid-template-columns: repeat(7, 200px);
                gap: 12px;
            }
        }
        @media (max-width: 640px) {
            .week-toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .week-nav {
                flex-wrap: wrap;
                justify-content: center;
            }
            .weekly-grid {
                grid-template-columns: repeat(7, 170px);
                gap: 10px;
            }
            .modal-panel {
                padding: 20px 18px;
            }
            .field-row {
                grid-template-columns: 1fr;
            }
            .todo-item {
                flex-wrap: wrap;
            }
            .todo-wrap {
                padding: 16px 18px;
            }
        }
        @media (max-width: 420px) {
            .weekly-grid {
                grid-template-columns: repeat(7, 150px);
            }
            .day-head {
                padding: 10px 10px 8px;
            }
            .day-head .dnum {
                font-size: 1.1rem;
            }
        }
    </style>

    <div class="wrap">
        <!-- toolbar -->
        <div class="week-toolbar">
            <div class="week-nav">
                <button type="button" id="prevWeekBtn" title="Minggu sebelumnya">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M15 6l-6 6 6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <span class="week-label" id="weekLabel">Memuat…</span>
                <button type="button" id="nextWeekBtn" title="Minggu berikutnya">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M9 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button type="button" id="todayBtn" class="today-btn">Hari ini</button>
            </div>
            <button type="button" class="btn btn-solid" id="addBtn">+ Tambah Jadwal</button>
        </div>

        <!-- weekly grid -->
        <div class="weekly-grid" id="weeklyGrid"></div>

        <!-- todo overview -->
        <div class="todo-wrap">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:8px"><rect x="7" y="3" width="10" height="4" rx="1" fill="currentColor" opacity="0.12"/><path d="M7 7h10v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Todo Mingguan
                    <span id="todoCount">0</span>
                </h3>
            <div id="todoOverview" class="todo-list">
                <div style="padding:24px;text-align:center;color:var(--ink-soft);">Memuat todo…</div>
            </div>
        </div>
    </div>

    <!-- Modal -->
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
                    <button type="submit" class="btn btn-solid" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- toast -->
    <div class="toast" id="toast"></div>

    <script>
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
                            <div>
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
                                <div>
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
                                            <button class="mini-btn" data-action="break" data-date="${dateStr}"><svg width="14" height="14" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"> <path d="M12 7a5 5 0 100 10 5 5 0 000-10z" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>Istirahat</button>
                                            <button class="mini-btn" data-action="note" data-date="${dateStr}"><svg width="14" height="14" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:6px;"><path d="M4 20l1-4.2L15.5 5.3a1.5 1.5 0 012.1 0l1.1 1.1a1.5 1.5 0 010 2.1L8.2 19l-4.2 1z" stroke="currentColor" stroke-width="1.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>Catatan</button>
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
                return `
                    <div class="item-card" style="border-left-color:${color};">
                        ${time ? `<div class="item-time">${time}</div>` : ''}
                        <div class="item-title">${escapeHtml(item.title || '')}</div>
                        ${item.description ? `<div class="item-desc">${escapeHtml(item.description)}</div>` : ''}
                        <div class="item-actions">
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
                        date: item.date ? item.date.slice(0, 10) : ''
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
    </script>
</x-app-layout>
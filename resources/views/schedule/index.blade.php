<x-app-layout>
    <x-slot name="header">Schedule</x-slot>
    @push('head')
        @vite(['resources/css/pages/schedule.css', 'resources/js/pages/schedule.js'])
    @endpush


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

</x-app-layout>
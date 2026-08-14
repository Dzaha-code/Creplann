<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
            <span class="text-sm text-gray-500" id="currentDateTime"></span>
        </div>
    </x-slot>

    <style>
        /* ===== DASHBOARD STYLES ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 8px;
        }
        .stat-card {
            background: #fff;
            border: 1px solid var(--line, #e5e0d8);
            border-radius: var(--radius, 16px);
            padding: 20px 22px;
            transition: all .25s ease;
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -14px rgba(36,31,26,.15);
        }
        .stat-card .stat-icon {
            font-size: 1.4rem;
            margin-bottom: 6px;
            opacity: 0.7;
        }
        .stat-card .stat-number {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1.8rem;
            line-height: 1.2;
            color: var(--ink, #241f1a);
        }
        .stat-card .stat-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--ink-soft, #6b6560);
            text-transform: uppercase;
            letter-spacing: .03em;
        }
        .stat-card .stat-trend {
            position: absolute;
            top: 16px;
            right: 16px;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 999px;
            background: var(--paper-soft, #f5f0e8);
            color: var(--ink-soft, #6b6560);
        }
        .stat-card .stat-trend.up { color: #2d7a4a; background: #e6f3ec; }
        .stat-card .stat-trend.down { color: #c44b2e; background: #f5e8e4; }
        .stat-card .stat-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 0 3px 0 0;
            transition: width 1s ease;
        }
        .stat-card:nth-child(1) .stat-bar { background: var(--coral, #E15B3F); width: 0%; }
        .stat-card:nth-child(2) .stat-bar { background: #7E9083; width: 0%; }
        .stat-card:nth-child(3) .stat-bar { background: #E3A93B; width: 0%; }
        .stat-card:nth-child(4) .stat-bar { background: #5B7FA6; width: 0%; }

        /* Main Content Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-top: 8px;
        }

        /* Cards */
        .card {
            background: #fff;
            border: 1px solid var(--line, #e5e0d8);
            border-radius: var(--radius, 16px);
            padding: 22px 24px 24px;
            transition: all .25s ease;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-header h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.05rem;
            color: var(--ink, #241f1a);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .card-header h3 .badge {
            font-size: 0.6rem;
            font-family: system-ui, sans-serif;
            font-weight: 700;
            background: var(--coral, #E15B3F);
            color: #fff;
            padding: 1px 10px;
            border-radius: 999px;
            margin-left: 4px;
        }
        .card-header .view-all {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--coral, #E15B3F);
            text-decoration: none;
            transition: color .2s ease;
        }
        .card-header .view-all:hover {
            color: var(--coral-ink, #b84a33);
            text-decoration: underline;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .quick-action {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18px 12px;
            background: var(--paper-soft, #f5f0e8);
            border-radius: 12px;
            text-decoration: none;
            color: var(--ink, #241f1a);
            transition: all .2s ease;
            border: 1.5px solid transparent;
            text-align: center;
            gap: 4px;
        }
        .quick-action:hover {
            border-color: var(--coral, #E15B3F);
            background: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -12px rgba(36,31,26,.2);
        }
        .quick-action .qa-icon {
            font-size: 1.6rem;
            line-height: 1;
        }
        .quick-action .qa-label {
            font-size: 0.78rem;
            font-weight: 600;
        }
        .quick-action .qa-desc {
            font-size: 0.68rem;
            color: var(--ink-soft, #6b6560);
        }

        /* Activity List */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 340px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .activity-list::-webkit-scrollbar {
            width: 4px;
        }
        .activity-list::-webkit-scrollbar-track {
            background: var(--paper-soft, #f5f0e8);
            border-radius: 999px;
        }
        .activity-list::-webkit-scrollbar-thumb {
            background: var(--line, #e5e0d8);
            border-radius: 999px;
        }

        .activity-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 14px;
            background: var(--paper-soft, #f5f0e8);
            border-radius: 12px;
            transition: background .2s ease;
            position: relative;
        }
        .activity-item:hover {
            background: #ede8df;
        }
        .activity-item .av {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            background: #fff;
            border: 1px solid var(--line, #e5e0d8);
        }
        .activity-item .av.blue { background: #e8edf5; border-color: #b8c9dd; }
        .activity-item .av.green { background: #e6f3ec; border-color: #a8c9b8; }
        .activity-item .av.orange { background: #f5ede6; border-color: #ddc9b8; }
        .activity-item .av.pink { background: #f5e8ec; border-color: #ddb8c4; }

        .activity-item .ac-body {
            flex: 1;
            min-width: 0;
        }
        .activity-item .ac-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .activity-item .ac-title {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--ink, #241f1a);
            margin-bottom: 1px;
        }
        .activity-item .ac-meta {
            font-size: 0.72rem;
            color: var(--ink-soft, #6b6560);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .activity-item .ac-meta .dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--ink-soft, #6b6560);
            display: inline-block;
        }
        .activity-item .ac-time {
            font-size: 0.68rem;
            color: var(--ink-soft, #6b6560);
            flex-shrink: 0;
            position: absolute;
            right: 12px;
            top: 12px;
            white-space: nowrap;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 30px 20px 20px;
            color: var(--ink-soft, #6b6560);
        }
        .empty-state svg {
            width: 42px;
            height: 42px;
            color: var(--line, #e5e0d8);
            margin-bottom: 8px;
        }
        .empty-state p {
            font-size: 0.88rem;
            margin: 0;
        }

        /* Today's Schedule */
        .schedule-mini-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .schedule-mini-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            background: var(--paper-soft, #f5f0e8);
            border-radius: 10px;
            border-left: 3px solid var(--coral, #E15B3F);
            transition: all .2s ease;
        }
        .schedule-mini-item:hover {
            background: #ede8df;
            transform: translateX(2px);
        }
        .schedule-mini-item .sm-time {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--ink-soft, #6b6560);
            min-width: 44px;
            flex-shrink: 0;
        }
        .schedule-mini-item .sm-title {
            font-size: 0.85rem;
            font-weight: 500;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .schedule-mini-item .sm-priority {
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 1px 10px;
            border-radius: 999px;
            flex-shrink: 0;
        }
        .schedule-mini-item .sm-priority.high { background: #f5e8e4; color: #c44b2e; }
        .schedule-mini-item .sm-priority.medium { background: #f5ede6; color: #b88a3b; }
        .schedule-mini-item .sm-priority.low { background: #e6f3ec; color: #2d7a4a; }

        /* Progress Ring */
        .progress-ring-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 24px;
            padding: 8px 0;
        }
        .progress-ring {
            position: relative;
            width: 90px;
            height: 90px;
        }
        .progress-ring svg {
            transform: rotate(-90deg);
        }
        .progress-ring .ring-bg {
            fill: none;
            stroke: var(--paper-soft, #f5f0e8);
            stroke-width: 6;
        }
        .progress-ring .ring-fg {
            fill: none;
            stroke: var(--coral, #E15B3F);
            stroke-width: 6;
            stroke-linecap: round;
            transition: stroke-dashoffset 1.2s ease;
        }
        .progress-ring .ring-center {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .progress-ring .ring-center .num {
            font-family: 'Fraunces', serif;
            font-weight: 700;
            font-size: 1.5rem;
            line-height: 1;
            color: var(--ink, #241f1a);
        }
        .progress-ring .ring-center .label {
            font-size: 0.6rem;
            font-weight: 600;
            color: var(--ink-soft, #6b6560);
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 640px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
            .stat-card {
                padding: 16px 18px;
            }
            .stat-card .stat-number {
                font-size: 1.4rem;
            }
            .card {
                padding: 16px 18px;
            }
            .quick-actions {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .quick-action {
                padding: 14px 8px;
            }
            .quick-action .qa-icon {
                font-size: 1.3rem;
            }
            .progress-ring-wrap {
                flex-direction: column;
                gap: 12px;
            }
        }

        @media (max-width: 420px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
            .stat-card .stat-number {
                font-size: 1.2rem;
            }
            .stat-card .stat-label {
                font-size: 0.65rem;
            }
        }
    </style>

    <div class="wrap">
        <!-- ===== STATS GRID ===== -->
        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                    <div class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="7" y="3" width="10" height="4" rx="1" fill="currentColor" opacity="0.15"/><path d="M7 7h10v12a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V7z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <div class="stat-number" id="totalNotes">0</div>
                <div class="stat-label">Total Catatan</div>
                <div class="stat-trend" id="notesTrend">+0%</div>
                <div class="stat-bar" id="notesBar"></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M16 3v4M8 3v4M3 11h18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <div class="stat-number" id="totalSchedules">0</div>
                <div class="stat-label">Total Jadwal</div>
                <div class="stat-trend" id="schedulesTrend">+0%</div>
                <div class="stat-bar" id="schedulesBar"></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.4"/><path d="M8 12.5l2.2 2.2L16 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
                <div class="stat-number" id="totalTodos">0</div>
                <div class="stat-label">Total Todo</div>
                <div class="stat-trend" id="todosTrend">+0%</div>
                <div class="stat-bar" id="todosBar"></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 19v-8" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M10 19V9" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M16 19v-4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M22 19v-2" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></div>
                <div class="stat-number" id="completionRate">0%</div>
                <div class="stat-label">Tingkat Penyelesaian</div>
                <div class="stat-trend" id="completionTrend">+0%</div>
                <div class="stat-bar" id="completionBar"></div>
            </div>
        </div>

        <!-- ===== MAIN GRID ===== -->
        <div class="main-grid">
            <!-- LEFT COLUMN -->
            <div class="left-col" style="display:flex;flex-direction:column;gap:24px;">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3><svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="margin-right:6px;vertical-align:middle"><path d="M13 2L3 14h9l-1 8L21 10h-9l1-8z" fill="currentColor"/></svg> Aksi Cepat</h3>
                    </div>
                    <div class="quick-actions">
                        <a href="{{ route('note.index') }}" class="quick-action">
                            <span class="qa-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M4 21h16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M14.5 3.5l6 6L10 20l-6 1 1-6 10.5-11.5z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg></span>
                            <span class="qa-label">Catatan Baru</span>
                            <span class="qa-desc">Tulis ide atau catatan</span>
                        </a>
                        <a href="{{ route('schedule.index') }}" class="quick-action">
                            <span class="qa-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.4"/><path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            <span class="qa-label">Jadwal Baru</span>
                            <span class="qa-desc">Atur aktivitas hari ini</span>
                        </a>
                        <a href="{{ route('todo.index') }}" class="quick-action">
                            <span class="qa-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.4"/><path d="M8 12.5l2.2 2.2L16 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            <span class="qa-label">Todo Baru</span>
                            <span class="qa-desc">Buat daftar tugas</span>
                        </a>
                        <a href="#" class="quick-action" onclick="window.location.reload(); return false;">
                            <span class="qa-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M20 17.5A7.5 7.5 0 1 0 9 21" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 17.5v-5h-5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                            <span class="qa-label">Refresh</span>
                            <span class="qa-desc">Perbarui dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- Today's Schedule -->
                <div class="card">
                    <div class="card-header">
                        <h3><svg width="16" height="16" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:middle"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.2" fill="none"/></svg> Jadwal Hari Ini <span class="badge" id="todayBadge">0</span></h3>
                        <a href="{{ route('schedule.index') }}" class="view-all">Lihat semua →</a>
                    </div>
                    <div id="todaySchedule">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                            <p>Belum ada jadwal untuk hari ini.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-col" style="display:flex;flex-direction:column;gap:24px;">
                <!-- Progress -->
                <div class="card">
                    <div class="card-header">
                        <h3><svg width="16" height="16" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:middle"><path d="M4 19v-8M10 19V9M16 19v-4M22 19v-2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg> Progress</h3>
                    </div>
                    <div class="progress-ring-wrap">
                        <div class="progress-ring">
                            <svg width="90" height="90" viewBox="0 0 90 90">
                                <circle class="ring-bg" cx="45" cy="45" r="38"/>
                                <circle class="ring-fg" id="progressRing" cx="45" cy="45" r="38"
                                    stroke-dasharray="238.76"
                                    stroke-dashoffset="238.76"/>
                            </svg>
                            <div class="ring-center">
                                <span class="num" id="progressPercent">0%</span>
                                <span class="label">Selesai</span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:6px;font-size:0.85rem;">
                            <div><span style="font-weight:600;" id="completedTodos">0</span> Todo selesai</div>
                            <div><span style="font-weight:600;" id="pendingTodos">0</span> Todo pending</div>
                            <div style="font-size:0.75rem;color:var(--ink-soft);margin-top:2px;">
                                Dari <span id="totalTodosProgress">0</span> total todo
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <h3><svg width="16" height="16" viewBox="0 0 24 24" style="margin-right:8px;vertical-align:middle"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2" fill="none"/><path d="M12 7v6l4 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg> Aktivitas Terbaru</h3>
                    </div>
                    <div id="recentActivity">
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                            <p>Belum ada aktivitas.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function() {
        'use strict';

        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

        // ===== DATE HELPERS =====
        function toDateStr(d) {
            const year = d.getFullYear();
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function getToday() {
            const now = new Date();
            return toDateStr(now);
        }

        // ===== UPDATE DATE TIME =====
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            const el = document.getElementById('currentDateTime');
            if (el) {
                el.textContent = now.toLocaleDateString('id-ID', options);
            }
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);

        // ===== FETCH DASHBOARD DATA =====
        async function fetchDashboardData() {
            try {
                const response = await fetch('/planner-api/dashboard', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal memuat data dashboard');
                }

                const data = await response.json();
                renderDashboard(data);
            } catch (error) {
                console.error('Dashboard error:', error);
                // Show fallback / retry
                setTimeout(fetchDashboardData, 5000);
            }
        }

        // ===== RENDER DASHBOARD =====
        function renderDashboard(data) {
            // Stats
            const totalNotes = data.stats?.total_notes || 0;
            const totalSchedules = data.stats?.total_schedules || 0;
            const totalTodos = data.stats?.total_todos || 0;
            const completedTodos = data.stats?.completed_todos || 0;
            const pendingTodos = data.stats?.pending_todos || 0;
            const completionRate = totalTodos > 0 ? Math.round((completedTodos / totalTodos) * 100) : 0;

            // Update stat numbers with animation
            animateNumber('totalNotes', totalNotes);
            animateNumber('totalSchedules', totalSchedules);
            animateNumber('totalTodos', totalTodos);
            document.getElementById('completionRate').textContent = completionRate + '%';

            // Update stat bars
            const maxStat = Math.max(totalNotes, totalSchedules, totalTodos, 1);
            document.getElementById('notesBar').style.width = Math.min((totalNotes / maxStat) * 100, 100) + '%';
            document.getElementById('schedulesBar').style.width = Math.min((totalSchedules / maxStat) * 100, 100) + '%';
            document.getElementById('todosBar').style.width = Math.min((totalTodos / maxStat) * 100, 100) + '%';
            document.getElementById('completionBar').style.width = completionRate + '%';

            // Update stats trends (dummy - bisa diganti dengan data real)
            updateTrend('notesTrend', totalNotes);
            updateTrend('schedulesTrend', totalSchedules);
            updateTrend('todosTrend', totalTodos);
            updateTrend('completionTrend', completionRate);

            // Progress ring
            const circumference = 238.76;
            const offset = circumference - (completionRate / 100) * circumference;
            const ring = document.getElementById('progressRing');
            if (ring) {
                ring.style.strokeDashoffset = offset;
            }
            document.getElementById('progressPercent').textContent = completionRate + '%';
            document.getElementById('completedTodos').textContent = completedTodos;
            document.getElementById('pendingTodos').textContent = pendingTodos;
            document.getElementById('totalTodosProgress').textContent = totalTodos;

            // Today's schedule
            const todaySchedules = data.today_schedules || [];
            const todayBadge = document.getElementById('todayBadge');
            if (todayBadge) todayBadge.textContent = todaySchedules.length;

            const scheduleContainer = document.getElementById('todaySchedule');
            if (todaySchedules.length === 0) {
                scheduleContainer.innerHTML = `
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                        <p>Belum ada jadwal untuk hari ini.</p>
                    </div>
                `;
            } else {
                scheduleContainer.innerHTML = `
                    <div class="schedule-mini-list">
                        ${todaySchedules.map(s => `
                            <div class="schedule-mini-item" style="border-left-color: ${s.color || '#E15B3F'}">
                                <span class="sm-time">${s.start_time || '--:--'}</span>
                                <span class="sm-title">${escapeHtml(s.title || '')}</span>
                                <span class="sm-priority ${s.priority || 'medium'}">${s.priority || 'medium'}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            }

            // Recent activity
            const activities = data.recent_activities || [];
            const activityContainer = document.getElementById('recentActivity');
            if (activities.length === 0) {
                activityContainer.innerHTML = `
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                        <p>Belum ada aktivitas.</p>
                    </div>
                `;
            } else {
                const avatarColors = ['blue', 'green', 'orange', 'pink'];
                activityContainer.innerHTML = `
                    <div class="activity-list">
                        ${activities.map((act, index) => `
                            <div class="activity-item">
                                <div class="av ${avatarColors[index % avatarColors.length]}">${getActivityIconSvg(act.type)}</div>
                                <div class="ac-body">
                                    <div class="ac-title">${escapeHtml(act.title || '')}</div>
                                    <div class="ac-meta">
                                        <span>${escapeHtml(act.type || '')}</span>
                                        <span class="dot"></span>
                                        <span>${escapeHtml(act.category || 'Umum')}</span>
                                    </div>
                                </div>
                                <span class="ac-time">${formatActivityTime(act.time)}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            }
        }

        // ===== ANIMATE NUMBER =====
        function animateNumber(elementId, targetValue) {
            const el = document.getElementById(elementId);
            if (!el) return;

            const duration = 600;
            const startTime = performance.now();
            const startValue = parseInt(el.textContent) || 0;

            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                // Ease out cubic
                const eased = 1 - Math.pow(1 - progress, 3);
                const current = Math.round(startValue + (targetValue - startValue) * eased);
                el.textContent = current;

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    el.textContent = targetValue;
                }
            }
            requestAnimationFrame(update);
        }

        // ===== UPDATE TREND =====
        function updateTrend(elementId, value) {
            const el = document.getElementById(elementId);
            if (!el) return;

            // Dummy trend logic - bisa diganti dengan data real
            const random = Math.random();
            if (value === 0) {
                el.textContent = '0%';
                el.className = 'stat-trend';
                return;
            }

            const isUp = random > 0.4;
            const percent = Math.round((isUp ? 5 + Math.random() * 15 : -(5 + Math.random() * 10)));
            const sign = percent > 0 ? '+' : '';
            el.textContent = `${sign}${percent}%`;
            el.className = `stat-trend ${isUp ? 'up' : 'down'}`;
        }

        // ===== ESCAPE HTML =====
        function escapeHtml(str) {
            if (!str) return '';
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }

        // ===== Format activity time =====
        function formatActivityTime(value) {
            if (!value) return '';
            // try parse ISO or simple date
            const d = new Date(value);
            if (!isNaN(d.getTime())) {
                // show date (and time if time present)
                const hasTime = /T|:/.test(value);
                const opts = hasTime
                    ? { year: 'numeric', month: 'short', day: '2-digit', hour: '2-digit', minute: '2-digit' }
                    : { year: 'numeric', month: 'short', day: '2-digit' };
                return d.toLocaleString('id-ID', opts);
            }
            return value;
        }
        // ===== Activity Icon SVGs =====
        function getActivityIconSvg(type) {
            switch ((type || '').toLowerCase()) {
                case 'todo':
                    return `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M9 11l2 2 4-4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
                case 'schedule':
                    return `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.2"/><path d="M8 3v4M16 3v4M3 11h18" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>`;
                case 'note':
                    return `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><path d="M14.5 3.5l6 6L10 20l-6 1 1-6 10.5-11.5z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`;
                default:
                    return `<svg width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.2"/><path d="M12 8v5l3 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/></svg>`;
            }
        }

        // ===== INIT =====
        fetchDashboardData();

        // Auto refresh every 60 seconds
        setInterval(fetchDashboardData, 60000);

    })();
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="db-topbar">
            <div class="db-topbar-left">
                <div class="db-greeting-dot" aria-hidden="true"></div>
                <div>
                    <span class="db-greeting-label">Selamat datang kembali</span>
                    <h1 class="db-greeting-name">Dashboard</h1>
                </div>
            </div>
            <div class="db-topbar-right">
                <div class="db-datetime-pill">
                    <i class="ti ti-clock-hour-4" aria-hidden="true"></i>
                    <span id="dbDateTime">—</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="db-wrap">

        {{-- ----------------------------------------
             BENTO GRID
        ---------------------------------------- --}}
        <div class="db-bento">

            {{-- -- Stat: Notes -- --}}
            <div class="db-tile db-tile--stat db-tile--coral" style="--accent:#c96442">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    <i class="ti ti-notebook"></i>
                </div>
                <div class="db-tile-content">
                    <i class="ti ti-notebook db-tile-icon" aria-hidden="true"></i>
                    <div class="db-stat-num" id="totalNotes">0</div>
                    <div class="db-stat-label">Catatan</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="notesBar" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- -- Stat: Schedules -- --}}
            <div class="db-tile db-tile--stat db-tile--sage" style="--accent:#5a7a5c">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    <i class="ti ti-calendar-event"></i>
                </div>
                <div class="db-tile-content">
                    <i class="ti ti-calendar-event db-tile-icon" aria-hidden="true"></i>
                    <div class="db-stat-num" id="totalSchedules">0</div>
                    <div class="db-stat-label">Jadwal</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="schedulesBar" style="width:0%;background:var(--accent)"></div>
                    </div>
                </div>
            </div>

            {{-- -- Stat: Todos -- --}}
            <div class="db-tile db-tile--stat db-tile--amber" style="--accent:#b87c2e">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    <i class="ti ti-checkbox"></i>
                </div>
                <div class="db-tile-content">
                    <i class="ti ti-checkbox db-tile-icon" aria-hidden="true"></i>
                    <div class="db-stat-num" id="totalTodos">0</div>
                    <div class="db-stat-label">Todo</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="todosBar" style="width:0%;background:var(--accent)"></div>
                    </div>
                </div>
            </div>

            {{-- -- Progress ring (tall tile) -- --}}
            <div class="db-tile db-tile--progress">
                <div class="db-tile-header">
                    <i class="ti ti-chart-donut" aria-hidden="true"></i>
                    <span>Penyelesaian</span>
                </div>
                <div class="db-ring-wrap">
                    <div class="db-ring" role="meter" aria-label="Persentase todo selesai" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <svg viewBox="0 0 120 120" width="120" height="120">
                            <circle class="db-ring-bg" cx="60" cy="60" r="50"/>
                            <circle class="db-ring-fg" id="progressRing" cx="60" cy="60" r="50"
                                stroke-dasharray="314.16"
                                stroke-dashoffset="314.16"/>
                        </svg>
                        <div class="db-ring-center">
                            <span class="db-ring-num" id="progressPercent">0%</span>
                            <span class="db-ring-sub">selesai</span>
                        </div>
                    </div>
                </div>
                <div class="db-ring-legend">
                    <div class="db-legend-item">
                        <span class="db-legend-dot" style="background:#c96442"></span>
                        <span><b id="completedTodos">0</b> selesai</span>
                    </div>
                    <div class="db-legend-item">
                        <span class="db-legend-dot" style="background:#e0ddd8"></span>
                        <span><b id="pendingTodos">0</b> pending</span>
                    </div>
                </div>
            </div>

            {{-- -- Quick Actions (wide tile) -- --}}
            <div class="db-tile db-tile--actions">
                <div class="db-tile-header">
                    <i class="ti ti-bolt" aria-hidden="true"></i>
                    <span>Aksi Cepat</span>
                </div>
                <div class="db-qa-grid">
                    <a href="{{ route('note.index') }}" class="db-qa">
                        <span class="db-qa-icon"><i class="ti ti-notebook" aria-hidden="true"></i></span>
                        <span class="db-qa-label">Catatan Baru</span>
                        <span class="db-qa-sub">Tulis ide</span>
                    </a>
                    <a href="{{ route('schedule.index') }}" class="db-qa">
                        <span class="db-qa-icon"><i class="ti ti-calendar-plus" aria-hidden="true"></i></span>
                        <span class="db-qa-label">Jadwal Baru</span>
                        <span class="db-qa-sub">Atur waktu</span>
                    </a>
                    <a href="{{ route('todo.index') }}" class="db-qa">
                        <span class="db-qa-icon"><i class="ti ti-circle-plus" aria-hidden="true"></i></span>
                        <span class="db-qa-label">Todo Baru</span>
                        <span class="db-qa-sub">Tambah tugas</span>
                    </a>
                    <a href="#" class="db-qa" onclick="window.location.reload();return false">
                        <span class="db-qa-icon"><i class="ti ti-refresh" aria-hidden="true"></i></span>
                        <span class="db-qa-label">Refresh</span>
                        <span class="db-qa-sub">Perbarui data</span>
                    </a>
                </div>
            </div>

            {{-- -- Today's Schedule (wide tall tile) -- --}}
            <div class="db-tile db-tile--schedule">
                <div class="db-tile-header">
                    <i class="ti ti-calendar-today" aria-hidden="true"></i>
                    <span>Jadwal Hari Ini</span>
                    <span class="db-badge" id="todayBadge">0</span>
                    <a href="{{ route('schedule.index') }}" class="db-tile-link">
                        Lihat semua <i class="ti ti-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="todaySchedule" class="db-schedule-list"></div>
            </div>

            {{-- -- Recent Activity -- --}}
            <div class="db-tile db-tile--activity">
                <div class="db-tile-header">
                    <i class="ti ti-history" aria-hidden="true"></i>
                    <span>Aktivitas Terbaru</span>
                </div>
                <div id="recentActivity" class="db-activity-list"></div>
            </div>

        </div>{{-- /.db-bento --}}
    </div>{{-- /.db-wrap --}}
    @push('head')
        @vite(['resources/css/pages/dashboard.css', 'resources/js/pages/dashboard.js'])
    @endpush

</x-app-layout>
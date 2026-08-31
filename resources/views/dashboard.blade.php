<x-app-layout>
    {{-- CSS & JS dimuat di <head> via @push, sebelum render body --}}
    @push('head')
        @vite(['resources/css/pages/dashboard.css', 'resources/js/pages/dashboard.js'])
    @endpush

    <x-slot name="header">
        <div class="db-topbar">
            <div class="db-topbar-left">
                <a href="{{ route('profile.edit') }}" class="db-user-avatar-link" title="Edit profil">
                    @if (auth()->user()->avatar)
                        <span class="db-user-avatar">
                            <img src="{{ auth()->user()->avatarUrl() }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="db-user-avatar--img"
                                 width="46" height="46">
                        </span>
                    @else
                        <span class="db-user-avatar db-user-avatar--initial" aria-hidden="true">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                </a>
                <div>
                    <span class="db-greeting-label">Selamat datang kembali</span>
                    <h1 class="db-greeting-name">{{ auth()->user()->name }}</h1>
                </div>
            </div>
            <div class="db-topbar-right">
                <div class="db-datetime-pill">
                    {{-- SVG inline — tidak tergantung webfont --}}
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span id="dbDateTime">—</span>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="db-wrap">
        <div class="db-bento">

            {{-- Stat: Notes --}}
            <div class="db-tile db-tile--stat db-tile--coral" style="--accent:#c96442">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    {{-- SVG inline — render instan tanpa webfont --}}
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 19a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6v13M12 6v13M21 6v13"/>
                    </svg>
                </div>
                <div class="db-tile-content">
                    <svg class="db-tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 19a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6v13M12 6v13M21 6v13"/>
                    </svg>
                    <div class="db-stat-num" id="totalNotes">–</div>
                    <div class="db-stat-label">Catatan</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="notesBar" style="width:0%"></div>
                    </div>
                </div>
            </div>

            {{-- Stat: Schedules --}}
            <div class="db-tile db-tile--stat db-tile--sage" style="--accent:#5a7a5c">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                        <line x1="11" y1="15" x2="12" y2="15"/><line x1="12" y1="15" x2="12" y2="18"/>
                    </svg>
                </div>
                <div class="db-tile-content">
                    <svg class="db-tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                        <line x1="11" y1="15" x2="12" y2="15"/><line x1="12" y1="15" x2="12" y2="18"/>
                    </svg>
                    <div class="db-stat-num" id="totalSchedules">–</div>
                    <div class="db-stat-label">Jadwal</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="schedulesBar" style="width:0%;background:var(--accent)"></div>
                    </div>
                </div>
            </div>

            {{-- Stat: Todos --}}
            <div class="db-tile db-tile--stat db-tile--amber" style="--accent:#b87c2e">
                <div class="db-tile-bg-glyph" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                </div>
                <div class="db-tile-content">
                    <svg class="db-tile-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                    </svg>
                    <div class="db-stat-num" id="totalTodos">–</div>
                    <div class="db-stat-label">Todo</div>
                    <div class="db-stat-bar-wrap" aria-hidden="true">
                        <div class="db-stat-bar" id="todosBar" style="width:0%;background:var(--accent)"></div>
                    </div>
                </div>
            </div>

            {{-- Progress ring --}}
            <div class="db-tile db-tile--progress">
                <div class="db-tile-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 6v6l4 2"/>
                    </svg>
                    <span>Penyelesaian</span>
                </div>
                <div class="db-ring-wrap">
                    <div class="db-ring" role="meter" aria-label="Persentase todo selesai"
                         aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <svg viewBox="0 0 120 120" width="120" height="120">
                            <circle class="db-ring-bg" cx="60" cy="60" r="50"/>
                            <circle class="db-ring-fg" id="progressRing" cx="60" cy="60" r="50"
                                    stroke-dasharray="314.16" stroke-dashoffset="314.16"/>
                        </svg>
                        <div class="db-ring-center">
                            <span class="db-ring-num" id="progressPercent">–</span>
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

            {{-- Quick Actions --}}
            <div class="db-tile db-tile--actions">
                <div class="db-tile-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                    </svg>
                    <span>Aksi Cepat</span>
                </div>
                <div class="db-qa-grid">
                    <a href="{{ route('note.index') }}" class="db-qa">
                        <span class="db-qa-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M3 19a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6a9 9 0 0 1 9 0 9 9 0 0 1 9 0M3 6v13M12 6v13M21 6v13"/>
                            </svg>
                        </span>
                        <span class="db-qa-label">Catatan Baru</span>
                        <span class="db-qa-sub">Tulis ide</span>
                    </a>
                    <a href="{{ route('schedule.index') }}" class="db-qa">
                        <span class="db-qa-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="5" width="16" height="16" rx="2"/>
                                <line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/>
                                <line x1="4" y1="11" x2="20" y2="11"/><line x1="12" y1="15" x2="12" y2="18"/>
                            </svg>
                        </span>
                        <span class="db-qa-label">Jadwal Baru</span>
                        <span class="db-qa-sub">Atur waktu</span>
                    </a>
                    <a href="{{ route('todo.index') }}" class="db-qa">
                        <span class="db-qa-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/>
                                <line x1="8" y1="12" x2="16" y2="12"/>
                            </svg>
                        </span>
                        <span class="db-qa-label">Todo Baru</span>
                        <span class="db-qa-sub">Tambah tugas</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="db-qa">
                        <span class="db-qa-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/>
                                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/>
                            </svg>
                        </span>
                        <span class="db-qa-label">Refresh</span>
                        <span class="db-qa-sub">Perbarui data</span>
                    </a>
                </div>
            </div>

            {{-- Today's Schedule --}}
            <div class="db-tile db-tile--schedule">
                <div class="db-tile-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="4" y="5" width="16" height="16" rx="2"/>
                        <line x1="16" y1="3" x2="16" y2="7"/><line x1="8" y1="3" x2="8" y2="7"/>
                        <line x1="4" y1="11" x2="20" y2="11"/>
                        <circle cx="12" cy="16" r="1" fill="currentColor"/>
                    </svg>
                    <span>Jadwal Hari Ini</span>
                    <span class="db-badge" id="todayBadge">0</span>
                    <a href="{{ route('schedule.index') }}" class="db-tile-link">
                        Lihat semua
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                </div>
                <div id="todaySchedule" class="db-schedule-list"></div>
            </div>

            {{-- Recent Activity --}}
            <div class="db-tile db-tile--activity">
                <div class="db-tile-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 8 12 12 15 15"/>
                    </svg>
                    <span>Aktivitas Terbaru</span>
                </div>
                <div id="recentActivity" class="db-activity-list"></div>
            </div>

        </div>{{-- /.db-bento --}}
    </div>{{-- /.db-wrap --}}

</x-app-layout>

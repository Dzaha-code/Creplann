<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div class="page-header-inner">

                <div>
                    <h1 class="page-title">Todo</h1>
                    <p class="page-sub">Kelola tugas harianmu dengan mudah</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="td-wrap">
        @php
            $filter = request('filter', 'all');

            $activeTodos = collect($todos ?? [])
                ->where('completed', false)
                ->values();

            $archivedTodos = collect($todos ?? [])
                ->where('completed', true)
                ->sortByDesc(function ($todo) {
                    $dt = $todo->completed_at ?? $todo->updated_at ?? $todo->created_at;
                    return $dt?->timestamp ?? 0;
                })
                ->values();

            $archivedByMonth = $archivedTodos->groupBy(function ($todo) {
                $dt = $todo->completed_at ?? $todo->updated_at ?? $todo->created_at;
                return $dt?->format('Y-m') ?? 'unknown';
            });

            $isArchiveView = $filter === 'done';
            $showActiveSection = $filter !== 'done';
            $showArchiveSection = $filter !== 'active';

            $counterCount = $isArchiveView ? $archivedTodos->count() : $activeTodos->count();
            $counterText = $isArchiveView ? 'selesai' : 'tugas';
        @endphp

        {{-- ── Success banner ── --}}
        @if (session('success'))
            <div class="td-banner td-banner--success" role="alert">
                <i class="ti ti-circle-check" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- ── Error banner ── --}}
        @if ($errors->any())
            <div class="td-banner td-banner--error" role="alert">
                <i class="ti ti-alert-circle" aria-hidden="true"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Add form ── --}}
        <div class="td-add-card">
            <form method="POST" action="{{ route('todo.store') }}" class="td-add-form">
                @csrf
                <div class="td-add-inner">
                    <i class="ti ti-pencil-plus td-add-prefix" aria-hidden="true"></i>
                    <input
                        type="text"
                        name="title"
                        placeholder="Tulis tugas baru…"
                        required
                        autofocus
                        value="{{ old('title') }}"
                        class="td-add-input"
                        autocomplete="off"
                    >
                    <div class="td-add-sep" aria-hidden="true"></div>
                    <div class="td-date-wrap">
                        <i class="ti ti-calendar td-date-icon" aria-hidden="true"></i>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="td-date-input">
                    </div>
                    <button type="submit" class="td-add-btn">
                        <i class="ti ti-plus" aria-hidden="true"></i>
                        <span>Tambah</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- ── Filter bar ── --}}
        <div class="td-filter-bar">
            <nav class="td-filter-tabs" role="tablist" aria-label="Filter tugas">
                <a
                    href="{{ route('todo.index') }}"
                    class="td-filter-tab {{ request('filter', 'all') === 'all' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ request('filter', 'all') === 'all' ? 'true' : 'false' }}"
                >
                    <i class="ti ti-layout-list" aria-hidden="true"></i> Semua
                </a>
                <a
                    href="{{ route('todo.index', ['filter' => 'active']) }}"
                    class="td-filter-tab {{ request('filter') === 'active' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ request('filter') === 'active' ? 'true' : 'false' }}"
                >
                    <i class="ti ti-circle-dot" aria-hidden="true"></i> Aktif
                </a>
                <a
                    href="{{ route('todo.index', ['filter' => 'done']) }}"
                    class="td-filter-tab {{ request('filter') === 'done' ? 'active' : '' }}"
                    role="tab"
                    aria-selected="{{ request('filter') === 'done' ? 'true' : 'false' }}"
                >
                    <i class="ti ti-circle-check" aria-hidden="true"></i> Selesai
                </a>
            </nav>

            <div class="td-counter" aria-live="polite">
                <i class="ti ti-list-check" aria-hidden="true"></i>
                <span class="td-counter-num">{{ $counterCount }}</span>
                <span>{{ $counterText }}</span>
            </div>
        </div>

        {{-- ── Active todo list ── --}}
        @if ($showActiveSection)
            <div class="td-list">
                @forelse ($activeTodos as $todo)
                    @include('todo._item', ['todo' => $todo])
                @empty
                    <div class="td-empty" role="status">
                        <i class="ti ti-mood-empty" aria-hidden="true"></i>
                        <p class="td-empty-title">Belum ada tugas aktif</p>
                        <p class="td-empty-sub">Tambahkan tugas baru di atas atau buka arsip selesai.</p>
                    </div>
                @endforelse
            </div>
        @endif

        {{-- ── Completed archive ── --}}
        @if ($showArchiveSection)
            <details class="td-archive" {{ $isArchiveView ? 'open' : '' }}>
                <summary class="td-archive-summary">
                    <span class="td-archive-heading">
                        <i class="ti ti-archive" aria-hidden="true"></i>
                        <span>Arsip selesai</span>
                        <span class="td-archive-count">{{ $archivedTodos->count() }}</span>
                    </span>
                    <i class="ti ti-chevron-down td-archive-chevron" aria-hidden="true"></i>
                </summary>

                <div class="td-archive-body">
                    @if ($archivedTodos->isNotEmpty())
                        @foreach ($archivedByMonth as $monthKey => $monthTodos)
                            @php
                                $monthLabel = $monthKey === 'unknown'
                                    ? 'Tanpa tanggal'
                                    : \Illuminate\Support\Carbon::createFromFormat('Y-m', $monthKey)->translatedFormat('F Y');
                            @endphp

                            <details class="td-archive-month" {{ $isArchiveView && $loop->first ? 'open' : '' }}>
                                <summary class="td-archive-month-summary">
                                    <span class="td-archive-month-heading">
                                        <span class="td-archive-month-title">{{ $monthLabel }}</span>
                                        <span class="td-archive-month-count">{{ $monthTodos->count() }}</span>
                                    </span>
                                    <i class="ti ti-chevron-down td-archive-month-chevron" aria-hidden="true"></i>
                                </summary>

                                <div class="td-list td-archive-month-list">
                                    @foreach ($monthTodos as $todo)
                                        @include('todo._item', ['todo' => $todo])
                                    @endforeach
                                </div>
                            </details>
                        @endforeach
                    @else
                        <div class="td-empty td-empty--archive" role="status">
                            <i class="ti ti-archive-off" aria-hidden="true"></i>
                            <p class="td-empty-title">Belum ada tugas selesai</p>
                            <p class="td-empty-sub">Tugas yang kamu selesaikan akan otomatis masuk ke arsip per bulan.</p>
                        </div>
                    @endif
                </div>
            </details>
        @endif

    </div>{{-- /.td-wrap --}}
    @push('head')
        @vite(['resources/css/pages/todo.css', 'resources/js/pages/todo.js'])
    @endpush

</x-app-layout>

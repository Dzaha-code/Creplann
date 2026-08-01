<x-app-layout>
    <x-slot name="header">Todo</x-slot>

    <div class="wrap">

        {{-- Tambah todo baru --}}
        <form method="POST" action="{{ route('todo.store') }}" class="todo-add">
            @csrf
            <input type="text" name="title" placeholder="Tulis tugas baru…" required autofocus>
            <button type="submit" class="btn btn-solid">+ Tambah</button>
        </form>

        {{-- Filter --}}
        <div class="filter-tabs">
            <a href="{{ route('todo.index') }}" class="{{ request('filter', 'all') === 'all' ? 'active' : '' }}">Semua</a>
            <a href="{{ route('todo.index', ['filter' => 'active']) }}" class="{{ request('filter') === 'active' ? 'active' : '' }}">Aktif</a>
            <a href="{{ route('todo.index', ['filter' => 'done']) }}" class="{{ request('filter') === 'done' ? 'active' : '' }}">Selesai</a>
        </div>

        <div class="todo-list">
            <div class="card">
                @forelse ($todos ?? [] as $todo)
                    <div class="todo-row {{ $todo->completed ? 'checked' : '' }}">
                        <form method="POST" action="{{ route('todo.update', $todo->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" name="toggle" value="1" class="checkbox-btn">
                                @if ($todo->completed)
                                    <svg viewBox="0 0 24 24" fill="none" width="14" height="14">
                                        <path d="M5 12l4.5 4.5L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                            </button>
                            <span class="title">{{ $todo->title }}</span>
                            @if ($todo->due_date)
                                <span class="due">{{ \Illuminate\Support\Carbon::parse($todo->due_date)->translatedFormat('d M') }}</span>
                            @endif
                        </form>
                        <form method="POST" action="{{ route('todo.destroy', $todo->id) }}" onsubmit="return confirm('Hapus tugas ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn" title="Hapus">
                                <svg viewBox="0 0 24 24" fill="none">
                                    <path d="M5 7h14M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none"><rect x="3.5" y="3.5" width="17" height="17" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M7.5 12l2.6 2.6L16.5 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <p>Belum ada tugas di sini. Tambahkan yang pertama di atas.</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</x-app-layout>
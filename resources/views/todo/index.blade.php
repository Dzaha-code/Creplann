<x-app-layout>
    <x-slot name="header">Todo</x-slot>

    <div class="wrap">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Tambah todo baru --}}
        <form method="POST" action="{{ route('todo.store') }}" class="todo-add">
            @csrf
            <input type="text" name="title" placeholder="Tulis tugas baru…" required autofocus>
            <input type="date" name="due_date" value="{{ old('due_date') }}">
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
                        <div class="flex items-center justify-between gap-3">
                            <form method="POST" action="{{ route('todo.update', $todo->id) }}" class="flex flex-1 items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" name="toggle" value="1" class="checkbox-btn" title="Tandai selesai/belum selesai">
                                    @if ($todo->completed)
                                        <svg viewBox="0 0 24 24" fill="none" width="14" height="14">
                                            <path d="M5 12l4.5 4.5L19 7" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </button>
                                <div class="flex flex-col">
                                    <span class="title">{{ $todo->title }}</span>
                                    @if ($todo->due_date)
                                        <span class="due">{{ \Illuminate\Support\Carbon::parse($todo->due_date)->translatedFormat('d M') }}</span>
                                    @endif
                                </div>
                            </form>

                            <div class="flex items-center gap-2">
                                <button type="button" class="btn btn-outline" onclick="document.getElementById('edit-todo-{{ $todo->id }}').classList.toggle('hidden')">
                                    Edit
                                </button>
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
                        </div>

                        <form id="edit-todo-{{ $todo->id }}" method="POST" action="{{ route('todo.update', $todo->id) }}" class="mt-3 hidden">
                            @csrf
                            @method('PATCH')
                            <div class="flex flex-col gap-2 md:flex-row">
                                <input type="text" name="title" value="{{ $todo->title }}" required class="flex-1">
                                <input type="date" name="due_date" value="{{ $todo->due_date ? \Illuminate\Support\Carbon::parse($todo->due_date)->format('Y-m-d') : '' }}">
                                <button type="submit" class="btn btn-solid">Simpan</button>
                            </div>
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
<x-app-layout>
    <x-slot name="header">Notes</x-slot>

    <div class="wrap">

        <div class="notes-toolbar">
            <div class="chip-row">
                <a href="{{ route('note.index') }}" class="chip {{ !request('category') ? 'active' : '' }}">Semua</a>
                @foreach ($categories ?? [] as $cat)
                    <a href="{{ route('note.index', ['category' => $cat->id]) }}" class="chip {{ request('category') == $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
            <a href="{{ route('note.create') }}" class="btn btn-solid">+ Catatan Baru</a>
        </div>

        <div class="notes-grid">
            @forelse ($notes ?? [] as $note)
                <div class="note-card">
                    <span class="cat">{{ $note->category->name ?? 'Umum' }}</span>
                    <h3>{{ $note->title }}</h3>
                    <p>{{ \Illuminate\Support\Str::limit($note->content, 110) }}</p>
                    <div class="meta">
                        <span class="date">{{ $note->created_at->translatedFormat('d M Y') }}</span>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('note.edit', $note->id) }}" class="delete-btn" title="Edit">
                                <svg viewBox="0 0 24 24" fill="none"><path d="M4 20l1-4.2L15.5 5.3a1.5 1.5 0 012.1 0l1.1 1.1a1.5 1.5 0 010 2.1L8.2 19l-4.2 1z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                            </a>
                            <form method="POST" action="{{ route('note.destroy', $note->id) }}" onsubmit="return confirm('Hapus catatan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" title="Hapus">
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 7h14M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2m-8 0v12a2 2 0 002 2h6a2 2 0 002-2V7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M6 3.5h9l3.5 3.5V19a1.6 1.6 0 01-1.6 1.6H6A1.6 1.6 0 014.4 19V5.1A1.6 1.6 0 016 3.5z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    <p>Belum ada catatan. Yuk tulis yang pertama.</p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>
<div class="td-item {{ $todo->completed ? 'is-done' : '' }}" data-id="{{ $todo->id }}">
    <div class="td-row">
        <form method="POST" action="{{ route('todo.update', $todo->id) }}" class="td-toggle-form">
            @csrf
            @method('PATCH')
            <input type="hidden" name="toggle" value="1">
            <button
                type="submit"
                class="td-checkbox"
                aria-label="{{ $todo->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}"
                title="{{ $todo->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}"
            >
                @if ($todo->completed)
                    <i class="ti ti-check" aria-hidden="true"></i>
                @endif
            </button>
        </form>

        <div class="td-content">
            <span class="td-title">{{ $todo->title }}</span>
            @if ($todo->due_date)
                <span class="td-due">
                    <i class="ti ti-calendar-event" aria-hidden="true"></i>
                    {{ \Illuminate\Support\Carbon::parse($todo->due_date)->translatedFormat('d M Y') }}
                </span>
            @endif
        </div>

        <div class="td-actions" aria-label="Aksi tugas">
            <button
                type="button"
                class="td-btn-edit"
                onclick="tdToggleEdit({{ $todo->id }})"
                aria-label="Edit tugas"
                title="Edit"
                aria-expanded="false"
                aria-controls="edit-{{ $todo->id }}"
            >
                <i class="ti ti-pencil" aria-hidden="true"></i>
            </button>

            <form
                method="POST"
                action="{{ route('todo.destroy', $todo->id) }}"
                class="td-delete-form"
                onsubmit="return confirm('Hapus tugas ini?')"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="td-btn-delete" aria-label="Hapus tugas" title="Hapus">
                    <i class="ti ti-trash" aria-hidden="true"></i>
                </button>
            </form>
        </div>
    </div>

    <div id="edit-{{ $todo->id }}" class="td-edit-form" aria-hidden="true">
        <form method="POST" action="{{ route('todo.update', $todo->id) }}" class="td-edit-inner">
            @csrf
            @method('PATCH')
            <div class="td-edit-fields">
                <div class="td-edit-title-wrap">
                    <i class="ti ti-pencil td-edit-icon" aria-hidden="true"></i>
                    <input type="text" name="title" value="{{ $todo->title }}" required class="td-edit-input" placeholder="Judul tugas">
                </div>
                <input
                    type="date"
                    name="due_date"
                    value="{{ $todo->due_date ? \Illuminate\Support\Carbon::parse($todo->due_date)->format('Y-m-d') : '' }}"
                    class="td-edit-date"
                >
                <div class="td-edit-actions">
                    <button type="submit" class="td-btn-save">
                        <i class="ti ti-device-floppy" aria-hidden="true"></i>
                        Simpan
                    </button>
                    <button type="button" class="td-btn-cancel" onclick="tdCloseEdit({{ $todo->id }})">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

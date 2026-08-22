<x-app-layout>
    <x-slot name="header">Profile</x-slot>

    <style>
        .profile-stack{display:flex;flex-direction:column;gap:20px;max-width:640px;}
        .section-title{font-family:'Fraunces', serif;font-weight:600;font-size:1.15rem;margin:0 0 4px;}
        .section-sub{font-size:0.87rem;color:var(--ink-soft);margin:0 0 22px;}

        .field{margin-bottom:18px;}
        .field label{display:block;font-size:0.85rem;font-weight:600;color:var(--ink);margin-bottom:7px;}
        .field input{
            width:100%;padding:11px 14px;border-radius:12px;border:1.5px solid var(--line);
            background:var(--paper-soft);color:var(--ink);font-family:inherit;font-size:0.95rem;
        }
        .field input:focus{outline:none;border-color:var(--coral);background:#fff;}
        .field-error{margin-top:6px;font-size:0.82rem;color:var(--danger);font-weight:500;}

        .status-banner{
            background:rgba(126,144,131,0.14);border:1px solid rgba(126,144,131,0.35);
            color:#4d5c50;font-size:0.86rem;font-weight:600;padding:10px 15px;border-radius:12px;margin-bottom:18px;
        }

        .btn{
            display:inline-flex;align-items:center;justify-content:center;padding:11px 24px;
            border-radius:999px;font-weight:700;font-size:0.9rem;border:none;cursor:pointer;
            transition:transform .15s ease, background .2s ease;font-family:inherit;
        }
        .btn:hover{transform:translateY(-1px);}
        .btn-solid{background:var(--coral);color:#fff;box-shadow:0 10px 22px -10px rgba(225,91,63,.6);}
        .btn-solid:hover{background:var(--coral-ink);}
        .btn-danger{background:#fff;color:var(--danger);border:1.5px solid rgba(196,67,46,0.4);}
        .btn-danger:hover{background:rgba(196,67,46,0.08);}

        .danger-card{border-color:rgba(196,67,46,0.3);}
    </style>

    <div class="wrap">
        <div class="profile-stack">

            {{-- Informasi profil --}}
            <div class="card">
                <h2 class="section-title">Informasi Profil</h2>
                <p class="section-sub">Perbarui nama dan alamat email akunmu.</p>

                @if (session('status') === 'profile-updated')
                    <div class="status-banner">Profil berhasil diperbarui.</div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="field">
                        <label for="name">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                        @error('name') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-solid">Simpan Perubahan</button>
                </form>
            </div>

            {{-- Perbarui kata sandi --}}
            <div class="card">
                <h2 class="section-title">Perbarui Kata Sandi</h2>
                <p class="section-sub">Gunakan kata sandi yang panjang dan acak agar akunmu tetap aman.</p>

                @if (session('status') === 'password-updated')
                    <div class="status-banner">Kata sandi berhasil diperbarui.</div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="field">
                        <label for="current_password">Kata Sandi Saat Ini</label>
                        <input id="current_password" type="password" name="current_password" autocomplete="current-password">
                        @error('current_password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password">Kata Sandi Baru</label>
                        <input id="password" type="password" name="password" autocomplete="new-password">
                        @error('password', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="field">
                        <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                        @error('password_confirmation', 'updatePassword') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-solid">Perbarui Kata Sandi</button>
                </form>
            </div>

            {{-- Hapus akun --}}
            <div class="card danger-card">
                <h2 class="section-title">Hapus Akun</h2>
                <p class="section-sub">Setelah dihapus, semua jadwal, todo, dan catatanmu akan ikut terhapus permanen. Aksi ini tidak bisa dibatalkan.</p>

                <form method="POST" action="{{ route('profile.destroy') }}"
                      onsubmit="return confirm('Yakin ingin menghapus akun secara permanen?');">
                    @csrf
                    @method('DELETE')

                    <div class="field">
                        <label for="password_delete">Konfirmasi Kata Sandi</label>
                        <input id="password_delete" type="password" name="password" placeholder="Masukkan kata sandimu">
                        @error('password', 'userDeletion') <div class="field-error">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-danger">Hapus Akun Saya</button>
                </form>
            </div>

        </div>
        /* ── Design correction pass for profile page ── */
        .profile-stack {
            max-width: 760px;
            margin-top: 10px;
        }

        .card {
            background: rgba(255, 253, 248, 0.82);
            border: 1px solid rgba(32, 36, 31, 0.12);
            border-radius: 18px;
            padding: 24px 24px 18px;
            box-shadow: 0 10px 18px rgba(32, 36, 31, 0.03);
        }

        .section-title {
            font-family: 'Big Shoulders Display', sans-serif;
            font-size: 2.1rem;
            letter-spacing: -0.04em;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 4px;
        }

        .section-sub {
            color: rgba(32, 36, 31, 0.65);
            font-size: 0.78rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .field label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.66rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(32, 36, 31, 0.7);
        }

        .field input {
            border: 1px solid rgba(32, 36, 31, 0.12);
            background: rgba(232, 227, 210, 0.28);
            border-radius: 12px;
            padding: 12px 14px;
        }

        .field input:focus {
            border-color: rgba(217, 143, 43, 0.75);
            box-shadow: 0 0 0 4px rgba(217, 143, 43, 0.1);
        }

        .btn {
            border-radius: 12px;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 0.7rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding-inline: 18px;
        }

        .btn-solid {
            background: var(--coral);
            color: var(--board);
            box-shadow: 0 10px 18px -12px rgba(217, 143, 43, 0.7);
        }

        .btn-danger {
            border: 1px solid rgba(173, 58, 44, 0.25);
            background: rgba(255, 255, 255, 0.5);
            color: var(--danger);
        }
    </style>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="pf-page-header">
            <div class="pf-header-inner">
                <div class="pf-header-avatar" aria-hidden="true">
                    @if (auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatarUrl() }}"
                             alt="{{ auth()->user()->name }}"
                             class="pf-header-avatar-img">
                    @else
                        <span class="pf-header-avatar-initial">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <h1 class="pf-page-title">{{ auth()->user()->name }}</h1>
                    <p class="pf-page-sub">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    @push('head')
        @vite(['resources/css/pages/profile.css', 'resources/js/pages/profile.js'])
    @endpush

    <div class="pf-wrap">

        {{-- ── Upload / Update Avatar ──────────────────────── --}}
        <section class="pf-section" aria-labelledby="avatar-heading">
            <div class="pf-section-head">
                <div>
                    <div class="pf-kicker">Foto Profil</div>
                    <h2 class="pf-section-title" id="avatar-heading">Ganti Foto</h2>
                </div>
            </div>

            <form method="post"
                  action="{{ route('profile.update') }}"
                  enctype="multipart/form-data"
                  id="avatarForm"
                  class="pf-avatar-form">
                @csrf
                @method('patch')

                <div class="pf-avatar-area">
                    {{-- Preview avatar --}}
                    <div class="pf-avatar-preview" id="avatarPreview" aria-label="Foto profil saat ini">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatarUrl() }}"
                                 alt="{{ auth()->user()->name }}"
                                 id="avatarPreviewImg"
                                 class="pf-avatar-preview-img">
                        @else
                            <span class="pf-avatar-preview-initial" id="avatarPreviewInitial">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        @endif

                        {{-- Overlay kamera --}}
                        <label for="avatarInput" class="pf-avatar-overlay" aria-label="Pilih foto baru">
                            <i class="ti ti-camera" aria-hidden="true"></i>
                            <span>Ganti</span>
                        </label>
                    </div>

                    <div class="pf-avatar-meta">
                        <label for="avatarInput" class="nt-btn nt-btn--ghost pf-pick-btn">
                            <i class="ti ti-upload" aria-hidden="true"></i>
                            Pilih Foto
                        </label>

                        @if (auth()->user()->avatar)
                            <button type="button"
                                    id="removeAvatarBtn"
                                    class="nt-btn nt-btn--ghost pf-remove-btn">
                                <i class="ti ti-trash" aria-hidden="true"></i>
                                Hapus Foto
                            </button>
                        @endif

                        <input type="file"
                               id="avatarInput"
                               name="avatar"
                               accept="image/jpeg,image/png,image/webp,image/gif"
                               class="pf-file-input"
                               aria-describedby="avatarHint">
                        <input type="hidden" id="removeAvatarInput" name="remove_avatar" value="0">

                        <p class="pf-avatar-hint" id="avatarHint">
                            JPG, PNG, atau WebP. Maksimal 2 MB.
                        </p>

                        @error('avatar')
                            <p class="pf-field-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Juga sertakan name & email agar validasi tidak gagal --}}
                <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                <input type="hidden" name="email" value="{{ auth()->user()->email }}">

                <div class="pf-form-actions">
                    <button type="submit" class="nt-btn nt-btn--solid" id="avatarSaveBtn">
                        <i class="ti ti-device-floppy" aria-hidden="true"></i>
                        Simpan Foto
                    </button>
                    @if (session('status') === 'profile-updated')
                        <span class="pf-saved-notice" role="status">
                            <i class="ti ti-circle-check" aria-hidden="true"></i>
                            Tersimpan!
                        </span>
                    @endif
                </div>
            </form>
        </section>

        {{-- ── Update Nama & Email ──────────────────────── --}}
        <section class="pf-section" aria-labelledby="info-heading">
            <div class="pf-section-head">
                <div>
                    <div class="pf-kicker">Informasi Akun</div>
                    <h2 class="pf-section-title" id="info-heading">Nama & Email</h2>
                </div>
            </div>

            @include('profile.partials.update-profile-information-form')
        </section>

        {{-- ── Ganti Password ───────────────────────────── --}}
        <section class="pf-section" aria-labelledby="pass-heading">
            <div class="pf-section-head">
                <div>
                    <div class="pf-kicker">Keamanan</div>
                    <h2 class="pf-section-title" id="pass-heading">Ganti Password</h2>
                </div>
            </div>

            @include('profile.partials.update-password-form')
        </section>

        {{-- ── Hapus Akun ───────────────────────────────── --}}
        <section class="pf-section pf-section--danger" aria-labelledby="del-heading">
            <div class="pf-section-head">
                <div>
                    <div class="pf-kicker pf-kicker--danger">Zona Berbahaya</div>
                    <h2 class="pf-section-title" id="del-heading">Hapus Akun</h2>
                </div>
            </div>

            @include('profile.partials.delete-user-form')
        </section>

    </div>{{-- /.pf-wrap --}}

</x-app-layout>

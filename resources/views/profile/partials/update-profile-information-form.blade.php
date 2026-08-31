<form method="post" action="{{ route('profile.update') }}" class="pf-form">
    @csrf
    @method('patch')

    {{-- avatar hidden (tidak diubah di form ini) --}}
    <input type="hidden" name="avatar" value="">

    <div class="pf-field {{ $errors->has('name') ? 'has-error' : '' }}">
        <label class="pf-label" for="name">Nama Lengkap</label>
        <input id="name"
               name="name"
               type="text"
               class="pf-input"
               value="{{ old('name', $user->name) }}"
               required
               autofocus
               autocomplete="name"
               maxlength="255">
        @error('name')
            <p class="pf-field-error" role="alert">{{ $message }}</p>
        @enderror
    </div>

    <div class="pf-field {{ $errors->has('email') ? 'has-error' : '' }}">
        <label class="pf-label" for="email">Alamat Email</label>
        <input id="email"
               name="email"
               type="email"
               class="pf-input"
               value="{{ old('email', $user->email) }}"
               required
               autocomplete="username"
               maxlength="255">
        @error('email')
            <p class="pf-field-error" role="alert">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="pf-verify-notice">
                <i class="ti ti-alert-circle" aria-hidden="true"></i>
                Email belum diverifikasi.
                <form id="send-verification" method="post" action="{{ route('verification.send') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="pf-link-btn">Kirim ulang verifikasi</button>
                </form>
            </div>
            @if (session('status') === 'verification-link-sent')
                <p class="pf-success-notice" role="status">
                    <i class="ti ti-circle-check" aria-hidden="true"></i>
                    Link verifikasi telah dikirim ke email Anda.
                </p>
            @endif
        @endif
    </div>

    <div class="pf-form-actions">
        <button type="submit" class="nt-btn nt-btn--solid">
            <i class="ti ti-device-floppy" aria-hidden="true"></i>
            Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span class="pf-saved-notice" role="status">
                <i class="ti ti-circle-check" aria-hidden="true"></i>
                Tersimpan!
            </span>
        @endif
    </div>
</form>

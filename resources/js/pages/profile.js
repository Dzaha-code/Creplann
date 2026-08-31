/**
 * profile.js — Halaman profil pengguna
 * Fitur:
 * - Live preview foto sebelum di-upload
 * - Konfirmasi hapus foto
 * - Auto-submit saat file dipilih (optional UX)
 */
(function () {
    'use strict';

    const avatarInput     = document.getElementById('avatarInput');
    const removeAvatarBtn = document.getElementById('removeAvatarBtn');
    const removeAvatarInput = document.getElementById('removeAvatarInput');
    const avatarPreview   = document.getElementById('avatarPreview');

    if (!avatarInput) return;

    // ── Live preview saat file dipilih ───────────────────────
    avatarInput.addEventListener('change', () => {
        const file = avatarInput.files?.[0];
        if (!file) return;

        // Validasi ukuran di sisi client (max 2 MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 2 MB.');
            avatarInput.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            // Hapus initial atau gambar lama, tampilkan preview baru
            avatarPreview.innerHTML = `
                <img src="${e.target.result}"
                     alt="Preview foto baru"
                     id="avatarPreviewImg"
                     class="pf-avatar-preview-img">
                <label for="avatarInput" class="pf-avatar-overlay" aria-label="Pilih foto lain">
                    <i class="ti ti-camera" aria-hidden="true"></i>
                    <span>Ganti</span>
                </label>
            `;
        };
        reader.readAsDataURL(file);

        // Reset flag hapus jika user memilih file baru
        if (removeAvatarInput) removeAvatarInput.value = '0';
    });

    // ── Tombol hapus foto ────────────────────────────────────
    if (removeAvatarBtn) {
        removeAvatarBtn.addEventListener('click', () => {
            if (!confirm('Hapus foto profil? Tampilan akan kembali ke inisial nama.')) return;

            // Set flag hapus
            removeAvatarInput.value = '1';

            // Reset input file agar tidak ikut ter-upload
            avatarInput.value = '';

            // Tampilkan preview inisial
            const initial = removeAvatarBtn.closest('form')
                ?.querySelector('[name="name"]')?.value?.charAt(0)?.toUpperCase()
                ?? '?';

            avatarPreview.innerHTML = `
                <span class="pf-avatar-preview-initial" id="avatarPreviewInitial">
                    ${initial}
                </span>
                <label for="avatarInput" class="pf-avatar-overlay" aria-label="Pilih foto baru">
                    <i class="ti ti-camera" aria-hidden="true"></i>
                    <span>Pilih</span>
                </label>
            `;

            // Sembunyikan tombol hapus
            removeAvatarBtn.hidden = true;
        });
    }

})();

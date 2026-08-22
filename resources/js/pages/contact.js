/**
 * contact.js — Halaman Hubungi Kami
 * Menggunakan fetch() untuk submit form dan menampilkan toast/error inline,
 * sesuai pola yang dipakai di notes.js dan schedule.js.
 */
(function () {
    'use strict';

    const form     = document.getElementById('contactForm');
    const toast    = document.getElementById('ct_toast');
    const submitBtn   = document.getElementById('ct_submit');
    const submitLabel = document.getElementById('ct_submit_label');
    const globalError = document.getElementById('ct_global_error');

    if (!form) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Toast ────────────────────────────────────────────────
    let toastTimer;

    function showToast(message, type = 'success') {
        clearTimeout(toastTimer);
        toast.textContent = message;
        toast.className   = `ct-toast is-show is-${type}`;
        toastTimer = setTimeout(() => {
            toast.classList.remove('is-show');
        }, 4000);
    }

    // ── Error helpers ────────────────────────────────────────

    /**
     * Tampilkan error inline di bawah field tertentu.
     * @param {string} fieldName  - nama field ('name', 'email', …)
     * @param {string} message
     */
    function setFieldError(fieldName, message) {
        const field = document.getElementById(`field_${fieldName}`);
        const errEl = document.getElementById(`err_${fieldName}`);
        if (field)  field.classList.add('has-error');
        if (errEl)  errEl.textContent = message;
    }

    /** Bersihkan semua error di semua field. */
    function clearErrors() {
        document.querySelectorAll('.ct-field').forEach((el) => {
            el.classList.remove('has-error');
        });
        document.querySelectorAll('.ct-field-error').forEach((el) => {
            el.textContent = '';
        });
        globalError.textContent = '';
        globalError.hidden = true;
    }

    /** Proses payload errors dari Laravel validation (422). */
    function applyValidationErrors(errors) {
        // `errors` = { name: ['...'], email: ['...'], ... }
        let first = null;
        Object.entries(errors).forEach(([field, messages]) => {
            const msg = Array.isArray(messages) ? messages[0] : messages;
            setFieldError(field, msg);
            if (!first) first = document.getElementById(`ct_${field}`);
        });
        // Fokus field pertama yang error untuk aksesibilitas
        first?.focus();
    }

    // ── Loading state ────────────────────────────────────────

    function setLoading(loading) {
        submitBtn.disabled = loading;
        const icon = submitBtn.querySelector('i');

        if (loading) {
            submitLabel.textContent = 'Mengirim…';
            icon.className = 'ti ti-loader-2';
            submitBtn.classList.add('is-loading');
        } else {
            submitLabel.textContent = 'Kirim Pesan';
            icon.className = 'ti ti-send';
            submitBtn.classList.remove('is-loading');
        }
    }

    // ── Submit ───────────────────────────────────────────────

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearErrors();

        // Validasi sisi klien sebelum kirim
        const name    = form.querySelector('#ct_name').value.trim();
        const email   = form.querySelector('#ct_email').value.trim();
        const subject = form.querySelector('#ct_subject').value.trim();
        const message = form.querySelector('#ct_message').value.trim();
        let hasClientError = false;

        if (!name)    { setFieldError('name',    'Nama wajib diisi.');    hasClientError = true; }
        if (!email)   { setFieldError('email',   'Email wajib diisi.');   hasClientError = true; }
        if (!subject) { setFieldError('subject', 'Subjek wajib diisi.');  hasClientError = true; }
        if (!message) { setFieldError('message', 'Pesan wajib diisi.');   hasClientError = true; }

        if (hasClientError) return;

        setLoading(true);

        try {
            const response = await fetch(form.action || '/contact', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name, email, subject, message }),
            });

            const data = await response.json().catch(() => ({}));

            if (response.status === 422 && data.errors) {
                // Error validasi dari Laravel
                applyValidationErrors(data.errors);
                return;
            }

            if (!response.ok) {
                const msg = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
                globalError.textContent = msg;
                globalError.hidden = false;
                showToast(msg, 'error');
                return;
            }

            // Sukses
            form.reset();
            showToast(data.message || 'Pesan berhasil dikirim! Kami akan segera merespons.', 'success');

        } catch (err) {
            const msg = 'Koneksi gagal. Periksa jaringan Anda dan coba lagi.';
            globalError.textContent = msg;
            globalError.hidden = false;
            showToast(msg, 'error');
        } finally {
            setLoading(false);
        }
    });

    // Hapus error field saat user mulai mengetik
    form.querySelectorAll('.ct-input').forEach((input) => {
        input.addEventListener('input', () => {
            const fieldName = input.name;
            const field = document.getElementById(`field_${fieldName}`);
            const errEl = document.getElementById(`err_${fieldName}`);
            if (field) field.classList.remove('has-error');
            if (errEl) errEl.textContent = '';
        });
    });

})();

    (function () {
        'use strict';

        /* ── Auto-dismiss success banner ── */
        const banner = document.querySelector('.td-banner--success');
        if (banner) {
            setTimeout(() => {
                banner.style.transition = 'opacity .4s ease, transform .4s ease';
                banner.style.opacity    = '0';
                banner.style.transform  = 'translateY(-6px)';
                setTimeout(() => (banner.style.display = 'none'), 420);
            }, 4000);
        }

        /* ── Edit toggle ── */
        window.tdToggleEdit = function (id) {
            const form = document.getElementById('edit-' + id);
            const btn  = document.querySelector('[aria-controls="edit-' + id + '"]');
            if (!form) return;

            const isOpen = form.classList.contains('is-open');
            // close all others first
            document.querySelectorAll('.td-edit-form.is-open').forEach(f => {
                f.classList.remove('is-open');
                f.setAttribute('aria-hidden', 'true');
            });
            document.querySelectorAll('.td-btn-edit').forEach(b => b.setAttribute('aria-expanded', 'false'));

            if (!isOpen) {
                form.classList.add('is-open');
                form.setAttribute('aria-hidden', 'false');
                if (btn) btn.setAttribute('aria-expanded', 'true');
                const inp = form.querySelector('input[type="text"]');
                if (inp) setTimeout(() => inp.focus(), 80);
            }
        };

        window.tdCloseEdit = function (id) {
            const form = document.getElementById('edit-' + id);
            const btn  = document.querySelector('[aria-controls="edit-' + id + '"]');
            if (!form) return;
            form.classList.remove('is-open');
            form.setAttribute('aria-hidden', 'true');
            if (btn) btn.setAttribute('aria-expanded', 'false');
        };

        /* ── Close edit on Escape ── */
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') return;
            document.querySelectorAll('.td-edit-form.is-open').forEach(form => {
                const id = form.id.replace('edit-', '');
                window.tdCloseEdit(id);
            });
        });

    })();
    
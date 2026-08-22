/**
 * help.js — Pusat Bantuan
 * Accordion FAQ sederhana dengan aksesibilitas aria-expanded.
 */
(function () {
    'use strict';

    const faqItems = document.querySelectorAll('[data-faq]');
    if (!faqItems.length) return;

    faqItems.forEach((item) => {
        const trigger = item.querySelector('.hp-faq-trigger');
        const body    = item.querySelector('.hp-faq-body');
        if (!trigger || !body) return;

        trigger.addEventListener('click', () => {
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';

            // Tutup semua FAQ lain sebelum membuka yang baru (one-open accordion)
            faqItems.forEach((other) => {
                if (other === item) return;
                const otherTrigger = other.querySelector('.hp-faq-trigger');
                const otherBody    = other.querySelector('.hp-faq-body');
                if (otherTrigger && otherBody) {
                    otherTrigger.setAttribute('aria-expanded', 'false');
                    otherBody.hidden = true;
                }
            });

            // Toggle item yang diklik
            trigger.setAttribute('aria-expanded', String(!isOpen));
            body.hidden = isOpen;
        });

        // Keyboard: Space dan Enter sudah ditangani oleh browser untuk button,
        // tapi pastikan Escape menutup FAQ yang terbuka
        trigger.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                trigger.setAttribute('aria-expanded', 'false');
                body.hidden = true;
                trigger.blur();
            }
        });
    });

})();

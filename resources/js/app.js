import Alpine from 'alpinejs';

window.Alpine = Alpine;

/*
 * Scroll handler navbar (sebelumnya inline di layouts/navigation.blade.php).
 * Di-throttle dengan requestAnimationFrame agar tidak memicu layout thrash
 * pada scroll — cukup 1 update per frame.
 */
const navbar = document.querySelector('.navbar-wrap');
if (navbar) {
    let ticking = false;

    const update = () => {
        navbar.classList.toggle('is-scrolled', window.scrollY > 24);
        ticking = false;
    };

    const onScroll = () => {
        if (!ticking) {
            ticking = true;
            requestAnimationFrame(update);
        }
    };

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
}

Alpine.start();

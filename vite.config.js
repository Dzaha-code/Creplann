import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // App (auth) — dimuat di layout aplikasi
                'resources/css/app.css',
                'resources/js/app.js',

                // Landing page (welcome)
                'resources/css/welcome.css',
                'resources/js/welcome.js',

                // CSS & JS spesifik per halaman (di-extract dari inline <style>/<script>)
                'resources/css/pages/dashboard.css',
                'resources/js/pages/dashboard.js',
                'resources/css/pages/schedule.css',
                'resources/js/pages/schedule.js',
                'resources/css/pages/todo.css',
                'resources/js/pages/todo.js',
                'resources/css/pages/notes.css',
                'resources/js/pages/notes.js',

                // Halaman baru: Blog, Contact, Help
                'resources/css/pages/blog.css',
                'resources/css/pages/contact.css',
                'resources/css/pages/help.css',
                'resources/js/pages/contact.js',
                'resources/js/pages/help.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        target: 'es2018',
        cssCodeSplit: true,
        sourcemap: false,
        minify: 'oxc',
        chunkSizeWarningLimit: 250,
        rollupOptions: {
            output: {
                // Code splitting: pisahkan vendor (Alpine) dari kode aplikasi agar
                // cache browser tetap valid saat kode aplikasi berubah.
                manualChunks(id) {
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
            },
        },
    },
});

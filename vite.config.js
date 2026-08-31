import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Core app
                'resources/css/app.css',
                'resources/js/app.js',

                // Landing page
                'resources/css/welcome.css',
                'resources/js/welcome.js',

                // Per-page CSS (hanya dimuat di halaman yang butuh via @push('head'))
                'resources/css/pages/dashboard.css',
                'resources/css/pages/schedule.css',
                'resources/css/pages/todo.css',
                'resources/css/pages/notes.css',
                'resources/css/pages/blog.css',
                'resources/css/pages/contact.css',
                'resources/css/pages/help.css',
                'resources/css/pages/profile.css',

                // Per-page JS
                'resources/js/pages/dashboard.js',
                'resources/js/pages/schedule.js',
                'resources/js/pages/todo.js',
                'resources/js/pages/notes.js',
                'resources/js/pages/contact.js',
                'resources/js/pages/help.js',
                'resources/js/pages/profile.js',
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
                // Pisahkan Alpine dari kode app agar cache browser tetap valid
                manualChunks(id) {
                    if (id.includes('node_modules/alpinejs')) {
                        return 'alpine';
                    }
                    if (id.includes('node_modules')) {
                        return 'vendor';
                    }
                },
                // Nama file stabil untuk caching lebih lama
                entryFileNames:  'assets/[name]-[hash].js',
                chunkFileNames:  'assets/[name]-[hash].js',
                assetFileNames:  'assets/[name]-[hash][extname]',
            },
        },
    },
    // Optimalkan dependency pre-bundling
    optimizeDeps: {
        include: ['alpinejs'],
    },
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/dark-mode.css',
                'resources/js/dark-mode.js',
                'resources/js/notifications.js',
                'resources/js/advanced-search.js',
                'resources/js/dashboard-analytics.js',
                'resources/js/ux-enhancements.js',
            ],
            refresh: true,
        }),
    ],
});

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS Principal
                'resources/css/app.css',
                
                // CSS Componentes
                'resources/css/components/dark-mode.css',
                'resources/css/components/executive-cards.css',
                'resources/css/components/navbar-fix.css',
                
                // JavaScript Principal
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                
                // JavaScript Módulos
                'resources/js/modules/dark-mode.js',
                'resources/js/modules/notifications.js',
                'resources/js/modules/advanced-search.js',
                'resources/js/modules/dashboard-analytics.js',
                'resources/js/modules/ux-enhancements.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separar módulos em chunks para melhor performance
                    'ui-modules': [
                        'resources/js/modules/dark-mode.js',
                        'resources/js/modules/ux-enhancements.js'
                    ],
                    'feature-modules': [
                        'resources/js/modules/notifications.js',
                        'resources/js/modules/advanced-search.js',
                        'resources/js/modules/dashboard-analytics.js'
                    ]
                }
            }
        },
        // Otimizações para produção
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true
            }
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});

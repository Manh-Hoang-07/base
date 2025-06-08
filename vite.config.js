import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Home assets
                'resources/css/app.css',
                'resources/js/app.js',
                // Admin assets
                'resources/css/admin.css',
                'resources/js/admin-actions.js',
                'resources/js/main.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: undefined, // Disable manual chunks for faster builds
            }
        },
        cssCodeSplit: true, // Enable CSS code splitting
        sourcemap: false,
        minify: process.env.NODE_ENV === 'production' ? 'terser' : false,
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    server: {
        hmr: {
            host: 'web.local',
        },
        host: 'web.local',
        port: 5173,
    },
    optimizeDeps: {
        include: ['bootstrap'],
        exclude: ['@fortawesome/fontawesome-free']
    }
});

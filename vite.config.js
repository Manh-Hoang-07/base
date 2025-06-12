import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Vue SPA entry point
                'resources/js/app-vue.js',
                // CSS files
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
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

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Vue SPA entry point
                'resources/js/app-vue.js',
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
                manualChunks: {
                    vendor: ['vue', 'vue-router'],
                    bootstrap: ['bootstrap']
                }
            }
        },
        cssCodeSplit: true, // Enable CSS code splitting
        sourcemap: false,
        minify: 'esbuild', // Use esbuild for faster minification
        target: 'es2015',
        chunkSizeWarningLimit: 1000,
    },
    server: {
        hmr: {
            host: 'web.local',
        },
        host: 'web.local',
        port: 5173,
    },
    optimizeDeps: {
        include: ['vue', 'vue-router', 'bootstrap'],
        exclude: ['@fortawesome/fontawesome-free']
    },
    esbuild: {
        drop: ['console', 'debugger'],
    }
});

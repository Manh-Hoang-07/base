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
                },
                // Optimize chunk names for better caching
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]'
            }
        },
        cssCodeSplit: false, // Disable CSS code splitting for fewer requests
        sourcemap: false,
        minify: 'terser', // Use terser for better compression
        target: 'es2015',
        chunkSizeWarningLimit: 2000,
        // Enable compression
        reportCompressedSize: false,
        // Optimize for production
        assetsInlineLimit: 4096,
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

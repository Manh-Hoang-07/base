/**
 * Font Optimizer - Optimize font loading and fallbacks
 * Handles font loading, fallbacks, and performance optimization
 */

class FontOptimizer {
    constructor() {
        this.loadedFonts = new Set();
        this.fontLoadPromises = new Map();
        this.systemFonts = [
            'system-ui',
            '-apple-system',
            'Segoe UI',
            'Roboto',
            'Helvetica Neue',
            'Arial',
            'sans-serif'
        ];
        this.init();
    }

    /**
     * Initialize font optimization
     */
    init() {
        this.setupSystemFontFallbacks();
        this.preloadCriticalFonts();
        this.setupFontLoadingOptimization();
        this.detectFontSupport();
    }

    /**
     * Setup system font fallbacks
     */
    setupSystemFontFallbacks() {
        const style = document.createElement('style');
        style.textContent = `
            /* System font stack for optimal performance */
            .font-system {
                font-family: ${this.systemFonts.join(', ')};
            }

            /* Fallback for missing fonts */
            .font-fallback {
                font-family: ${this.systemFonts.join(', ')};
                font-display: swap;
            }

            /* Optimize font rendering */
            body, html {
                font-family: ${this.systemFonts.join(', ')};
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-rendering: optimizeLegibility;
            }

            /* Font weight optimization */
            .font-light { font-weight: 300; }
            .font-normal { font-weight: 400; }
            .font-medium { font-weight: 500; }
            .font-semibold { font-weight: 600; }
            .font-bold { font-weight: 700; }
        `;
        document.head.appendChild(style);
    }

    /**
     * Preload critical fonts (only if they exist)
     */
    preloadCriticalFonts() {
        // Skip font preloading in development
        if (window.location.hostname === 'web.local' || window.enableFontPreloading === false) {
            return;
        }

        // Only preload fonts in admin area
        if (!window.location.pathname.includes('/admin')) {
            return;
        }

        const criticalFonts = [
            '/fonts/admin/Nunito-Regular.woff2',
            '/fonts/admin/Nunito-SemiBold.woff2',
            '/fonts/admin/Nunito-Bold.woff2'
        ];

        // Check if fonts exist before preloading
        criticalFonts.forEach(fontUrl => {
            this.checkAndPreloadFont(fontUrl);
        });
    }

    /**
     * Check if font exists and preload it
     */
    checkAndPreloadFont(fontUrl) {
        // Test if font exists first
        fetch(fontUrl, { method: 'HEAD' })
            .then(response => {
                if (response.ok) {
                    this.preloadFont(fontUrl);
                } else {
                    console.log(`⏭️ Font not found, skipping: ${fontUrl}`);
                }
            })
            .catch(() => {
                console.log(`⏭️ Font check failed, skipping: ${fontUrl}`);
            });
    }

    /**
     * Preload individual font
     */
    preloadFont(fontUrl) {
        if (this.loadedFonts.has(fontUrl)) {
            return Promise.resolve();
        }

        if (this.fontLoadPromises.has(fontUrl)) {
            return this.fontLoadPromises.get(fontUrl);
        }

        const promise = new Promise((resolve, reject) => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'font';
            link.type = 'font/woff2';
            link.crossOrigin = 'anonymous';
            link.href = fontUrl;

            link.onload = () => {
                this.loadedFonts.add(fontUrl);
                console.log(`✅ Font preloaded: ${fontUrl}`);
                resolve();
            };

            link.onerror = () => {
                console.warn(`❌ Failed to preload font: ${fontUrl}`);
                // Remove the link to prevent warnings
                if (link.parentNode) {
                    link.parentNode.removeChild(link);
                }
                reject(new Error(`Failed to load font: ${fontUrl}`));
            };

            document.head.appendChild(link);
        });

        this.fontLoadPromises.set(fontUrl, promise);
        return promise;
    }

    /**
     * Setup font loading optimization
     */
    setupFontLoadingOptimization() {
        // Use Font Loading API if available
        if ('fonts' in document) {
            this.setupFontLoadingAPI();
        } else {
            this.setupFallbackFontLoading();
        }
    }

    /**
     * Setup Font Loading API
     */
    setupFontLoadingAPI() {
        // Monitor font loading
        document.fonts.addEventListener('loadingdone', (event) => {
            console.log(`✅ Fonts loaded: ${event.fontfaces.length}`);
            this.onFontsLoaded(event.fontfaces);
        });

        document.fonts.addEventListener('loadingerror', (event) => {
            console.warn('❌ Font loading error:', event);
            this.onFontLoadError(event);
        });

        // Check if fonts are already loaded
        if (document.fonts.status === 'loaded') {
            this.onFontsLoaded([]);
        }
    }

    /**
     * Setup fallback font loading
     */
    setupFallbackFontLoading() {
        // Use timeout-based detection for older browsers
        setTimeout(() => {
            this.detectLoadedFonts();
        }, 3000);
    }

    /**
     * Detect font support
     */
    detectFontSupport() {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Test system font availability
        const testText = 'abcdefghijklmnopqrstuvwxyz0123456789';
        const baseFontSize = '72px';

        // Measure with fallback font
        context.font = `${baseFontSize} monospace`;
        const fallbackWidth = context.measureText(testText).width;

        // Test each system font
        this.systemFonts.forEach(font => {
            context.font = `${baseFontSize} ${font}, monospace`;
            const testWidth = context.measureText(testText).width;

            if (testWidth !== fallbackWidth) {
                console.log(`✅ System font available: ${font}`);
            }
        });
    }

    /**
     * Handle successful font loading
     */
    onFontsLoaded(fontfaces) {
        // Add loaded class to body
        document.body.classList.add('fonts-loaded');

        // Optimize font rendering
        this.optimizeFontRendering();

        // Update font metrics
        this.updateFontMetrics();
    }

    /**
     * Handle font loading errors
     */
    onFontLoadError(event) {
        // Fallback to system fonts
        document.body.classList.add('fonts-fallback');

        // Apply system font styles
        const style = document.createElement('style');
        style.textContent = `
            .fonts-fallback * {
                font-family: ${this.systemFonts.join(', ')} !important;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Optimize font rendering
     */
    optimizeFontRendering() {
        const style = document.createElement('style');
        style.textContent = `
            .fonts-loaded {
                font-display: swap;
            }

            .fonts-loaded h1, .fonts-loaded h2, .fonts-loaded h3,
            .fonts-loaded h4, .fonts-loaded h5, .fonts-loaded h6 {
                font-weight: 600;
                line-height: 1.2;
            }

            .fonts-loaded p, .fonts-loaded span, .fonts-loaded div {
                font-weight: 400;
                line-height: 1.6;
            }

            .fonts-loaded .btn {
                font-weight: 500;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Update font metrics for better layout
     */
    updateFontMetrics() {
        // Measure actual font metrics
        const testElement = document.createElement('div');
        testElement.style.cssText = `
            position: absolute;
            visibility: hidden;
            font-size: 100px;
            font-family: ${this.systemFonts.join(', ')};
        `;
        testElement.textContent = 'Ag';
        document.body.appendChild(testElement);

        const metrics = {
            height: testElement.offsetHeight,
            width: testElement.offsetWidth
        };

        document.body.removeChild(testElement);

        // Store metrics for layout calculations
        this.fontMetrics = metrics;
        console.log('📏 Font metrics:', metrics);
    }

    /**
     * Detect loaded fonts
     */
    detectLoadedFonts() {
        const loadedFonts = [];

        // Check for common admin fonts
        const adminFonts = ['Nunito', 'Roboto', 'Open Sans'];

        adminFonts.forEach(font => {
            if (this.isFontLoaded(font)) {
                loadedFonts.push(font);
            }
        });

        console.log('🔍 Detected loaded fonts:', loadedFonts);
        return loadedFonts;
    }

    /**
     * Check if specific font is loaded
     */
    isFontLoaded(fontName) {
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        const testText = 'abcdefghijklmnopqrstuvwxyz0123456789';
        const fontSize = '72px';

        // Measure with fallback
        context.font = `${fontSize} monospace`;
        const fallbackWidth = context.measureText(testText).width;

        // Measure with test font
        context.font = `${fontSize} ${fontName}, monospace`;
        const testWidth = context.measureText(testText).width;

        return testWidth !== fallbackWidth;
    }

    /**
     * Get font loading statistics
     */
    getStats() {
        return {
            loadedFonts: this.loadedFonts.size,
            fontPromises: this.fontLoadPromises.size,
            systemFonts: this.systemFonts.length,
            fontMetrics: this.fontMetrics,
            fontsReady: document.fonts ? document.fonts.status : 'unknown'
        };
    }

    /**
     * Optimize font loading for specific elements
     */
    optimizeElement(element) {
        // Apply system font stack
        element.style.fontFamily = this.systemFonts.join(', ');

        // Optimize rendering
        element.style.webkitFontSmoothing = 'antialiased';
        element.style.mozOsxFontSmoothing = 'grayscale';
        element.style.textRendering = 'optimizeLegibility';
    }

    /**
     * Log font optimization report
     */
    logReport() {
        const stats = this.getStats();

        console.group('🔤 Font Optimization Report');
        console.log('✅ Loaded fonts:', stats.loadedFonts);
        console.log('⏳ Font promises:', stats.fontPromises);
        console.log('🖥️ System fonts available:', stats.systemFonts);
        console.log('📏 Font metrics:', stats.fontMetrics);
        console.log('🎯 Fonts ready status:', stats.fontsReady);
        console.groupEnd();

        return stats;
    }
}

// Auto-initialize font optimizer
document.addEventListener('DOMContentLoaded', function() {
    window.fontOptimizer = new FontOptimizer();

    // Log report in development
    if (window.location.hostname === 'web.local') {
        setTimeout(() => {
            window.fontOptimizer.logReport();
        }, 3000);
    }
});

// Export for manual use
window.FontOptimizer = FontOptimizer;

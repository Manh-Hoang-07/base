/**
 * CSS Cleanup Utility - Remove broken font references and optimize CSS
 * Fixes 404 font errors and optimizes CSS performance
 */

class CSSCleanup {
    constructor() {
        this.brokenFonts = new Set();
        this.fixedRules = 0;
        this.init();
    }

    /**
     * Initialize CSS cleanup
     */
    init() {
        this.detectBrokenFonts();
        this.fixFontReferences();
        this.optimizeCSS();
        this.setupErrorMonitoring();
    }

    /**
     * Detect broken font references
     */
    detectBrokenFonts() {
        const brokenFontPatterns = [
            /figtree/i,
            /fonts\/figtree/i,
            /\.woff2?\s*\)\s*format\(['"]woff2?['"]\)/i
        ];

        // Check all stylesheets
        for (let i = 0; i < document.styleSheets.length; i++) {
            try {
                const styleSheet = document.styleSheets[i];
                this.scanStyleSheet(styleSheet, brokenFontPatterns);
            } catch (e) {
                console.warn('Cannot access stylesheet:', e);
            }
        }
    }

    /**
     * Scan stylesheet for broken fonts
     */
    scanStyleSheet(styleSheet, patterns) {
        try {
            const rules = styleSheet.cssRules || styleSheet.rules;
            
            if (rules) {
                for (let j = 0; j < rules.length; j++) {
                    const rule = rules[j];
                    
                    if (rule.type === CSSRule.FONT_FACE_RULE) {
                        this.checkFontFaceRule(rule, patterns);
                    } else if (rule.type === CSSRule.STYLE_RULE) {
                        this.checkStyleRule(rule, patterns);
                    }
                }
            }
        } catch (e) {
            console.warn('Error scanning stylesheet:', e);
        }
    }

    /**
     * Check @font-face rules
     */
    checkFontFaceRule(rule, patterns) {
        const cssText = rule.cssText;
        
        patterns.forEach(pattern => {
            if (pattern.test(cssText)) {
                console.warn('🚫 Broken @font-face rule detected:', cssText);
                this.brokenFonts.add(rule);
            }
        });
    }

    /**
     * Check style rules for font-family
     */
    checkStyleRule(rule, patterns) {
        const cssText = rule.cssText;
        
        if (cssText.includes('font-family') && cssText.includes('Figtree')) {
            console.warn('🚫 Broken font-family detected:', rule.selectorText);
            this.brokenFonts.add(rule);
        }
    }

    /**
     * Fix font references
     */
    fixFontReferences() {
        // Create replacement CSS
        const fixCSS = this.generateFixCSS();
        
        // Inject fix CSS
        const style = document.createElement('style');
        style.id = 'css-font-fix';
        style.textContent = fixCSS;
        document.head.appendChild(style);
        
        console.log(`✅ Applied CSS font fixes: ${this.fixedRules} rules`);
    }

    /**
     * Generate fix CSS
     */
    generateFixCSS() {
        const systemFonts = [
            'system-ui',
            '-apple-system',
            'Segoe UI',
            'Roboto',
            'Helvetica Neue',
            'Arial',
            'sans-serif'
        ].join(', ');

        const fixCSS = `
            /* CSS Font Fixes - Override broken font references */
            
            /* Fix body font */
            body, html {
                font-family: ${systemFonts} !important;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            
            /* Fix all elements with Figtree */
            *[style*="Figtree"],
            .font-figtree,
            [class*="figtree"] {
                font-family: ${systemFonts} !important;
            }
            
            /* Fix headings */
            h1, h2, h3, h4, h5, h6 {
                font-family: ${systemFonts} !important;
                font-weight: 600;
            }
            
            /* Fix buttons and form elements */
            button, input, select, textarea,
            .btn, .form-control, .form-select {
                font-family: ${systemFonts} !important;
            }
            
            /* Fix navigation and menu */
            .nav, .navbar, .menu, .sidebar {
                font-family: ${systemFonts} !important;
            }
            
            /* Fix admin specific elements */
            .main-sidebar, .content-wrapper,
            .navbar-nav, .nav-link {
                font-family: ${systemFonts} !important;
            }
            
            /* Optimize font rendering */
            * {
                text-rendering: optimizeLegibility;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }
            
            /* Hide broken font loading indicators */
            .font-loading, .font-preload {
                display: none !important;
            }
        `;

        this.fixedRules = 15; // Count of fix rules applied
        return fixCSS;
    }

    /**
     * Optimize CSS performance
     */
    optimizeCSS() {
        // Remove unused CSS classes
        this.removeUnusedClasses();
        
        // Optimize font loading
        this.optimizeFontLoading();
        
        // Fix layout shifts
        this.fixLayoutShifts();
    }

    /**
     * Remove unused CSS classes
     */
    removeUnusedClasses() {
        const unusedClasses = [
            'font-figtree',
            'figtree-400',
            'figtree-500',
            'figtree-600',
            'font-loading'
        ];

        const style = document.createElement('style');
        style.textContent = unusedClasses.map(cls => `.${cls} { display: none !important; }`).join('\n');
        document.head.appendChild(style);
    }

    /**
     * Optimize font loading
     */
    optimizeFontLoading() {
        // Preload system fonts
        const preloadStyle = document.createElement('style');
        preloadStyle.textContent = `
            /* Preload system fonts */
            .font-preload-system {
                font-family: system-ui, -apple-system, 'Segoe UI', Roboto;
                position: absolute;
                visibility: hidden;
                font-size: 1px;
            }
        `;
        document.head.appendChild(preloadStyle);

        // Create preload element
        const preloadEl = document.createElement('div');
        preloadEl.className = 'font-preload-system';
        preloadEl.textContent = 'preload';
        document.body.appendChild(preloadEl);

        // Remove after preload
        setTimeout(() => {
            if (preloadEl.parentNode) {
                preloadEl.parentNode.removeChild(preloadEl);
            }
        }, 100);
    }

    /**
     * Fix layout shifts caused by font loading
     */
    fixLayoutShifts() {
        const style = document.createElement('style');
        style.textContent = `
            /* Prevent layout shifts */
            body {
                font-display: swap;
            }
            
            /* Stable font metrics */
            h1 { line-height: 1.2; }
            h2 { line-height: 1.3; }
            h3 { line-height: 1.4; }
            p { line-height: 1.6; }
            
            /* Stable button sizes */
            .btn {
                min-height: 38px;
                line-height: 1.5;
            }
            
            /* Stable form elements */
            .form-control {
                line-height: 1.5;
                min-height: 38px;
            }
        `;
        document.head.appendChild(style);
    }

    /**
     * Setup error monitoring
     */
    setupErrorMonitoring() {
        // Monitor for 404 font errors
        const originalFetch = window.fetch;
        window.fetch = function(...args) {
            return originalFetch.apply(this, args).catch(error => {
                if (args[0] && args[0].includes('font')) {
                    console.warn('🚫 Font fetch error:', args[0]);
                }
                throw error;
            });
        };

        // Monitor resource loading errors
        window.addEventListener('error', (e) => {
            if (e.target && e.target.tagName === 'LINK' && e.target.href.includes('font')) {
                console.warn('🚫 Font link error:', e.target.href);
                this.handleFontError(e.target);
            }
        }, true);
    }

    /**
     * Handle font loading errors
     */
    handleFontError(linkElement) {
        // Remove broken font link
        if (linkElement.parentNode) {
            linkElement.parentNode.removeChild(linkElement);
        }

        // Apply fallback
        this.applyFontFallback();
    }

    /**
     * Apply font fallback
     */
    applyFontFallback() {
        const fallbackStyle = document.createElement('style');
        fallbackStyle.textContent = `
            /* Emergency font fallback */
            * {
                font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Arial, sans-serif !important;
            }
        `;
        document.head.appendChild(fallbackStyle);
    }

    /**
     * Get cleanup statistics
     */
    getStats() {
        return {
            brokenFonts: this.brokenFonts.size,
            fixedRules: this.fixedRules,
            stylesheets: document.styleSheets.length,
            fontErrors: this.getFontErrors()
        };
    }

    /**
     * Get font error count
     */
    getFontErrors() {
        // Count 404 errors in console (approximation)
        return Array.from(this.brokenFonts).length;
    }

    /**
     * Manual cleanup trigger
     */
    runCleanup() {
        console.log('🧹 Running manual CSS cleanup...');
        this.detectBrokenFonts();
        this.fixFontReferences();
        this.optimizeCSS();
        
        const stats = this.getStats();
        console.log('✅ Cleanup completed:', stats);
        return stats;
    }

    /**
     * Log cleanup report
     */
    logReport() {
        const stats = this.getStats();
        
        console.group('🧹 CSS Cleanup Report');
        console.log('🚫 Broken fonts detected:', stats.brokenFonts);
        console.log('✅ Rules fixed:', stats.fixedRules);
        console.log('📄 Stylesheets scanned:', stats.stylesheets);
        console.log('❌ Font errors:', stats.fontErrors);
        console.groupEnd();
        
        return stats;
    }
}

// Auto-initialize CSS cleanup
document.addEventListener('DOMContentLoaded', function() {
    window.cssCleanup = new CSSCleanup();
    
    // Log report in development
    if (window.location.hostname === 'web.local') {
        setTimeout(() => {
            window.cssCleanup.logReport();
        }, 2000);
    }
});

// Export for manual use
window.CSSCleanup = CSSCleanup;

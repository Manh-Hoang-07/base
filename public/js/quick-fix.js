/**
 * Quick Fix Script - Immediate fixes for common issues
 * Runs before other scripts to prevent errors
 */

(function() {
    'use strict';

    console.log('🔧 Quick Fix Script loading...');

    // 1. Disable WebSocket in development
    if (window.location.hostname === 'web.local') {
        window.enableWebSocket = false;
        console.log('🔌 WebSocket disabled in development');
    }

    // 2. Override font preloading to prevent 404s
    const originalCreateElement = document.createElement;
    document.createElement = function(tagName) {
        const element = originalCreateElement.call(this, tagName);

        if (tagName.toLowerCase() === 'link' && element.rel === 'preload' && element.as === 'font') {
            // Check if font URL contains problematic fonts
            const problematicFonts = ['figtree', 'missing-font'];
            const href = element.href || '';

            if (problematicFonts.some(font => href.toLowerCase().includes(font))) {
                console.log('🚫 Blocked problematic font preload:', href);
                // Return a dummy element that won't cause errors
                const dummy = originalCreateElement.call(this, 'span');
                dummy.style.display = 'none';
                return dummy;
            }
        }

        return element;
    };

    // 3. Catch and suppress font loading errors
    window.addEventListener('error', function(e) {
        if (e.target && e.target.tagName === 'LINK' && e.target.href) {
            const href = e.target.href;
            if (href.includes('font') || href.includes('.woff') || href.includes('.ttf')) {
                console.log('🚫 Suppressed font loading error:', href);
                e.preventDefault();
                e.stopPropagation();

                // Remove the problematic link
                if (e.target.parentNode) {
                    e.target.parentNode.removeChild(e.target);
                }

                return false;
            }
        }
    }, true);

    // 4. Override fetch to handle font requests gracefully
    const originalFetch = window.fetch;
    window.fetch = function(url, options) {
        // Check if it's a font request
        if (typeof url === 'string' && (url.includes('font') || url.includes('.woff') || url.includes('.ttf'))) {
            // Check if it's a problematic font
            const problematicFonts = ['figtree'];
            if (problematicFonts.some(font => url.toLowerCase().includes(font))) {
                console.log('🚫 Blocked problematic font fetch:', url);
                // Return a rejected promise
                return Promise.reject(new Error('Font blocked by quick fix'));
            }
        }

        return originalFetch.apply(this, arguments);
    };

    // 5. Prevent console spam from optimization tools
    const originalConsoleWarn = console.warn;
    const originalConsoleLog = console.log;
    const originalConsoleError = console.error;

    console.warn = function(...args) {
        const message = args.join(' ');

        // Suppress known warnings
        const suppressPatterns = [
            'Cannot access stylesheet',
            'Font loading error',
            'WebSocket connection',
            'Failed to preload font',
            'was preloaded using link preload but not used',
            'Service Worker not supported',
            'Error scanning stylesheet',
            'Font not found, skipping',
            'Font check failed, skipping'
        ];

        if (suppressPatterns.some(pattern => message.includes(pattern))) {
            return;
        }

        originalConsoleWarn.apply(console, args);
    };

    console.log = function(...args) {
        const message = args.join(' ');

        // Suppress debug logs in development
        if (window.location.hostname === 'web.local') {
            const suppressPatterns = [
                'Font preloaded:',
                'System font available:',
                'Module loaded:',
                'Cache hit for',
                'Cache miss for',
                'Cached API response:',
                'Cached user data:',
                'Cached session data:',
                'Cached form data:'
            ];

            if (suppressPatterns.some(pattern => message.includes(pattern))) {
                return;
            }
        }

        originalConsoleLog.apply(console, args);
    };

    // 6. Set up emergency font fallback
    const emergencyFontCSS = `
        /* Emergency font fallback */
        * {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }

        /* Hide broken font elements */
        link[href*="figtree"],
        link[href*="missing-font"] {
            display: none !important;
        }

        /* Prevent layout shifts */
        body {
            font-display: swap;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    `;

    const style = document.createElement('style');
    style.id = 'emergency-font-fix';
    style.textContent = emergencyFontCSS;

    // Insert as early as possible
    if (document.head) {
        document.head.insertBefore(style, document.head.firstChild);
    } else {
        document.addEventListener('DOMContentLoaded', function() {
            document.head.insertBefore(style, document.head.firstChild);
        });
    }

    // 7. Disable heavy features in development
    if (window.location.hostname === 'web.local') {
        window.disableHeavyFeatures = true;

        // Disable animations for faster development
        const devCSS = `
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-delay: 0.01ms !important;
                transition-duration: 0.01ms !important;
                transition-delay: 0.01ms !important;
            }
        `;

        const devStyle = document.createElement('style');
        devStyle.id = 'dev-performance-fix';
        devStyle.textContent = devCSS;

        if (document.head) {
            document.head.appendChild(devStyle);
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                document.head.appendChild(devStyle);
            });
        }
    }

    // 8. Set up global error handler
    window.addEventListener('unhandledrejection', function(e) {
        const reason = e.reason;

        // Suppress font-related promise rejections
        if (reason && reason.message && (
            reason.message.includes('font') ||
            reason.message.includes('Font blocked') ||
            reason.message.includes('WebSocket')
        )) {
            console.log('🚫 Suppressed promise rejection:', reason.message);
            e.preventDefault();
        }
    });

    // 9. Quick performance boost
    if (window.location.hostname === 'web.local') {
        // Disable resource hints that might cause issues
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.tagName === 'LINK' && (
                        node.rel === 'preload' ||
                        node.rel === 'prefetch' ||
                        node.rel === 'dns-prefetch'
                    )) {
                        const href = node.href || '';
                        if (href.includes('figtree') || href.includes('missing')) {
                            console.log('🚫 Removed problematic resource hint:', href);
                            node.remove();
                        }
                    }
                });
            });
        });

        if (document.head) {
            observer.observe(document.head, { childList: true });
        } else {
            document.addEventListener('DOMContentLoaded', function() {
                observer.observe(document.head, { childList: true });
            });
        }
    }

    // 10. Success message
    console.log('✅ Quick Fix Script loaded successfully');

    // Export for debugging
    window.quickFix = {
        version: '1.0.0',
        features: {
            fontErrorSuppression: true,
            webSocketDisabled: window.location.hostname === 'web.local',
            heavyFeaturesDisabled: window.location.hostname === 'web.local',
            emergencyFontFallback: true
        }
    };

})();

/**
 * Lightweight Development Mode - Minimal optimization for development
 * Provides essential functionality without heavy features
 */

(function() {
    'use strict';
    
    // Only run in development
    if (window.location.hostname !== 'web.local') {
        return;
    }
    
    // Create lightweight optimization objects
    window.cssOptimizer = {
        logOptimizationReport: () => ({
            usedClasses: 0,
            unusedRules: 0,
            potentialSavings: '0 KB'
        })
    };
    
    window.performanceMonitor = {
        generateReport: () => ({
            loadTime: performance.now(),
            resources: 0
        }),
        logCompleteReport: () => ({
            loadTime: performance.now(),
            coreWebVitals: { LCP: 'N/A', FID: 'N/A', CLS: 0 }
        })
    };
    
    window.imageOptimizer = {
        logReport: () => ({
            totalImages: 0,
            lazyLoaded: 0,
            successfullyLoaded: 0
        })
    };
    
    window.pwaManager = {
        getStatus: () => ({
            isOnline: navigator.onLine,
            serviceWorkerRegistered: false,
            installPromptAvailable: false,
            notificationsEnabled: false
        }),
        logReport: () => ({
            status: {
                isOnline: navigator.onLine,
                serviceWorkerRegistered: false
            }
        })
    };
    
    window.cacheManager = {
        getStats: () => ({
            memory: { items: 0, kb: '0.00' },
            storage: { items: 0 }
        }),
        logReport: () => ({
            memory: { items: 0, kb: '0.00' },
            storage: { items: 0 }
        }),
        clearAll: () => console.log('Cache cleared (dev mode)')
    };
    
    window.fontOptimizer = {
        getStats: () => ({
            loadedFonts: 0,
            fontPromises: 0,
            systemFonts: 7,
            fontMetrics: { height: 160, width: 123 },
            fontsReady: 'loaded'
        }),
        logReport: () => ({
            loadedFonts: 0,
            systemFonts: 7,
            fontMetrics: { height: 160, width: 123 }
        })
    };
    
    window.cssCleanup = {
        getStats: () => ({
            brokenFonts: 0,
            fixedRules: 15,
            stylesheets: document.styleSheets.length,
            fontErrors: 0
        }),
        logReport: () => ({
            brokenFonts: 0,
            fixedRules: 15,
            stylesheets: document.styleSheets.length
        })
    };
    
    // Lightweight loading utils
    if (!window.LoadingUtils) {
        window.LoadingUtils = {
            showToast: (message, type, duration) => {
                console.log(`Toast: ${message} (${type})`);
            },
            showGlobalLoading: (message) => {
                console.log(`Loading: ${message}`);
            },
            hideGlobalLoading: () => {
                console.log('Loading hidden');
            },
            setButtonLoading: (selector, loading) => {
                const btn = document.querySelector(selector);
                if (btn) {
                    btn.disabled = loading;
                    btn.textContent = loading ? 'Loading...' : btn.dataset.originalText || btn.textContent;
                }
            }
        };
    }
    
    // Simple performance tracking
    window.addEventListener('load', function() {
        const loadTime = performance.now();
        console.log(`⚡ Page loaded in ${loadTime.toFixed(2)}ms`);
    });
    
    // Development helpers
    window.dev = {
        clearCache: () => {
            localStorage.clear();
            sessionStorage.clear();
            console.log('🧹 Caches cleared');
        },
        reload: () => {
            window.location.reload();
        },
        toggleFeature: (feature, enabled) => {
            localStorage.setItem(`dev_${feature}`, enabled);
            console.log(`🎛️ Feature ${feature} ${enabled ? 'enabled' : 'disabled'}`);
        },
        status: () => ({
            mode: 'development',
            features: {
                heavyOptimization: false,
                moduleLoading: false,
                fontPreloading: false,
                webSocket: false
            }
        })
    };
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+Shift+R: Reload optimizations
        if (e.ctrlKey && e.shiftKey && e.key === 'R') {
            e.preventDefault();
            window.dev.reload();
        }
        
        // Ctrl+Shift+C: Clear caches
        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            window.dev.clearCache();
        }
        
        // Ctrl+Shift+S: Show status
        if (e.ctrlKey && e.shiftKey && e.key === 'S') {
            e.preventDefault();
            console.table(window.dev.status());
        }
    });
    
    console.log('🔧 Lightweight development mode loaded');
    console.log('⌨️ Shortcuts: Ctrl+Shift+R (reload), Ctrl+Shift+C (clear cache), Ctrl+Shift+S (status)');
    
})();

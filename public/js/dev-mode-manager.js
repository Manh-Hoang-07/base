/**
 * Development Mode Manager - Control features in development
 * Manages which optimization features to enable/disable in development
 */

class DevModeManager {
    constructor() {
        this.isDevelopment = this.detectDevelopmentMode();
        this.features = {
            websocket: false,
            heavyOptimization: false,
            fontPreloading: false,
            serviceWorker: false,
            realTimeMonitoring: true
        };
        this.init();
    }

    /**
     * Detect if we're in development mode
     */
    detectDevelopmentMode() {
        return window.location.hostname === 'web.local' ||
               window.location.hostname === 'localhost' ||
               window.location.hostname === '127.0.0.1' ||
               window.location.port === '8000';
    }

    /**
     * Initialize development mode settings
     */
    init() {
        if (this.isDevelopment) {
            this.setupDevelopmentMode();
            this.createDevControls();
        } else {
            this.setupProductionMode();
        }
    }

    /**
     * Setup development mode
     */
    setupDevelopmentMode() {
        // Load user preferences
        this.loadUserPreferences();

        // Apply feature flags
        this.applyFeatureFlags();

        // Setup development helpers
        this.setupDevHelpers();
    }

    /**
     * Setup development helpers
     */
    setupDevHelpers() {
        // Add development utilities
        window.dev = {
            clearCache: () => this.clearAllCaches(),
            reload: () => this.reloadOptimizations(),
            toggleFeature: (feature, enabled) => this.toggleFeature(feature, enabled),
            status: () => this.getStatus()
        };
    }

    /**
     * Setup production mode
     */
    setupProductionMode() {
        console.log('🚀 Production mode detected');

        // Enable all features in production
        Object.keys(this.features).forEach(feature => {
            this.features[feature] = true;
        });

        this.applyFeatureFlags();
    }

    /**
     * Load user preferences from localStorage
     */
    loadUserPreferences() {
        const saved = localStorage.getItem('devModePreferences');
        if (saved) {
            try {
                const preferences = JSON.parse(saved);
                this.features = { ...this.features, ...preferences };
            } catch (e) {
                console.warn('Failed to load dev preferences:', e);
            }
        }
    }

    /**
     * Save user preferences to localStorage
     */
    saveUserPreferences() {
        localStorage.setItem('devModePreferences', JSON.stringify(this.features));
    }

    /**
     * Apply feature flags globally
     */
    applyFeatureFlags() {
        // Set global flags
        window.enableWebSocket = this.features.websocket;
        window.enableHeavyOptimization = this.features.heavyOptimization;
        window.enableFontPreloading = this.features.fontPreloading;
        window.enableServiceWorker = this.features.serviceWorker;
        window.enableRealTimeMonitoring = this.features.realTimeMonitoring;

        // Apply CSS to disable features
        this.applyCSSFlags();

        console.log('🎛️ Feature flags applied:', this.features);
    }

    /**
     * Apply CSS flags to disable visual features
     */
    applyCSSFlags() {
        const style = document.createElement('style');
        style.id = 'dev-mode-flags';

        let css = '';

        if (!this.features.heavyOptimization) {
            css += `
                /* Disable heavy animations in development */
                *, *::before, *::after {
                    animation-duration: 0.01ms !important;
                    animation-delay: 0.01ms !important;
                    transition-duration: 0.01ms !important;
                    transition-delay: 0.01ms !important;
                }
            `;
        }

        if (!this.features.realTimeMonitoring) {
            css += `
                /* Hide monitoring indicators */
                #websocket-indicator,
                #offline-indicator,
                .loading-overlay {
                    display: none !important;
                }
            `;
        }

        style.textContent = css;
        document.head.appendChild(style);
    }

    /**
     * Create development controls
     */
    createDevControls() {
        if (!this.isDevelopment) return;

        const controls = document.createElement('div');
        controls.id = 'dev-controls';
        controls.style.cssText = `
            position: fixed;
            bottom: 10px;
            left: 10px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 10005;
            max-width: 300px;
            display: none;
        `;

        controls.innerHTML = `
            <div style="margin-bottom: 10px; font-weight: bold;">🔧 Dev Controls</div>
            ${this.createFeatureToggles()}
            <div style="margin-top: 10px;">
                <button onclick="window.devMode.clearAllCaches()" style="margin-right: 5px;">Clear Caches</button>
                <button onclick="window.devMode.reloadOptimizations()">Reload</button>
            </div>
        `;

        document.body.appendChild(controls);

        // Toggle controls with Ctrl+Shift+D
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey && e.shiftKey && e.key === 'D') {
                const display = controls.style.display === 'none' ? 'block' : 'none';
                controls.style.display = display;
            }
        });

        console.log('🎛️ Dev controls created. Press Ctrl+Shift+D to toggle.');
    }

    /**
     * Create feature toggle checkboxes
     */
    createFeatureToggles() {
        return Object.entries(this.features).map(([feature, enabled]) => {
            return `
                <label style="display: block; margin: 5px 0;">
                    <input type="checkbox" ${enabled ? 'checked' : ''}
                           onchange="window.devMode.toggleFeature('${feature}', this.checked)">
                    ${this.getFeatureLabel(feature)}
                </label>
            `;
        }).join('');
    }

    /**
     * Get human-readable feature label
     */
    getFeatureLabel(feature) {
        const labels = {
            websocket: 'WebSocket Connection',
            heavyOptimization: 'Heavy Optimizations',
            fontPreloading: 'Font Preloading',
            serviceWorker: 'Service Worker',
            realTimeMonitoring: 'Real-time Monitoring'
        };
        return labels[feature] || feature;
    }

    /**
     * Toggle feature on/off
     */
    toggleFeature(feature, enabled) {
        this.features[feature] = enabled;
        this.saveUserPreferences();
        this.applyFeatureFlags();

        console.log(`🎛️ Feature ${feature} ${enabled ? 'enabled' : 'disabled'}`);

        // Show reload notification for features that need it
        const needsReload = ['websocket', 'serviceWorker', 'fontPreloading'];
        if (needsReload.includes(feature)) {
            this.showReloadNotification();
        }
    }

    /**
     * Show reload notification
     */
    showReloadNotification() {
        if (window.LoadingUtils) {
            window.LoadingUtils.showToast('Reload page to apply changes', 'info', 5000);
        } else {
            console.log('🔄 Reload page to apply feature changes');
        }
    }

    /**
     * Clear all caches
     */
    clearAllCaches() {
        // Clear localStorage
        const devKeys = Object.keys(localStorage).filter(key =>
            key.startsWith('cache_') || key.startsWith('dev')
        );
        devKeys.forEach(key => localStorage.removeItem(key));

        // Clear sessionStorage
        sessionStorage.clear();

        // Clear browser caches if possible
        if ('caches' in window) {
            caches.keys().then(names => {
                names.forEach(name => caches.delete(name));
            });
        }

        console.log('🧹 All caches cleared');

        if (window.LoadingUtils) {
            window.LoadingUtils.showToast('Caches cleared', 'success');
        }
    }

    /**
     * Reload optimizations
     */
    reloadOptimizations() {
        // Reinitialize optimization modules
        if (window.cssOptimizer) {
            window.cssOptimizer.runCleanup();
        }

        if (window.performanceMonitor) {
            window.performanceMonitor.logCompleteReport();
        }

        if (window.cacheManager) {
            window.cacheManager.clearAll();
        }

        console.log('🔄 Optimizations reloaded');

        if (window.LoadingUtils) {
            window.LoadingUtils.showToast('Optimizations reloaded', 'success');
        }
    }

    /**
     * Get current status
     */
    getStatus() {
        return {
            isDevelopment: this.isDevelopment,
            features: this.features,
            globalFlags: {
                enableWebSocket: window.enableWebSocket,
                enableHeavyOptimization: window.enableHeavyOptimization,
                enableFontPreloading: window.enableFontPreloading,
                enableServiceWorker: window.enableServiceWorker,
                enableRealTimeMonitoring: window.enableRealTimeMonitoring
            }
        };
    }

    /**
     * Log development mode report
     */
    logReport() {
        const status = this.getStatus();

        // Only log in development if explicitly enabled
        if (this.isDevelopment && localStorage.getItem('enableDevReports') === 'true') {
            console.group('🔧 Development Mode Report');
            console.log('🏗️ Development mode:', status.isDevelopment);
            console.log('🎛️ Feature flags:', status.features);
            console.log('🌐 Global flags:', status.globalFlags);
            console.log('⌨️ Controls: Press Ctrl+Shift+D to toggle dev controls');
            console.groupEnd();
        }

        return status;
    }
}

// Auto-initialize development mode manager
document.addEventListener('DOMContentLoaded', function() {
    window.devMode = new DevModeManager();

    // Only log report if explicitly enabled
    if (localStorage.getItem('enableDevReports') === 'true') {
        setTimeout(() => {
            window.devMode.logReport();
        }, 1000);
    }
});

// Export for manual use
window.DevModeManager = DevModeManager;

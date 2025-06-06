/**
 * Dynamic Module Loader - Code Splitting Implementation
 * Loads JavaScript modules on-demand based on page routes and user interactions
 */

class ModuleLoader {
    constructor() {
        this.loadedModules = new Set();
        this.loadingModules = new Map();
        this.moduleCache = new Map();
        this.init();
    }

    /**
     * Initialize module loader
     */
    init() {
        this.detectCurrentRoute();
        this.setupLazyLoading();
        this.preloadCriticalModules();
    }

    /**
     * Detect current route and load appropriate modules
     */
    detectCurrentRoute() {
        const path = window.location.pathname;
        const routeModules = this.getRouteModules(path);

        routeModules.forEach(module => {
            this.loadModule(module, { priority: 'high' });
        });
    }

    /**
     * Get modules required for specific route
     */
    getRouteModules(path) {
        const routeMap = {
            // Admin routes
            '/admin/users': ['user-management', 'data-tables'],
            '/admin/roles': ['role-management', 'permissions', 'data-tables'],
            '/admin/permissions': ['permission-management', 'data-tables'],
            '/admin/dashboard': ['dashboard', 'charts'],

            // Forms
            '/admin/users/create': ['user-forms', 'form-validation'],
            '/admin/users/edit': ['user-forms', 'form-validation'],
            '/admin/roles/create': ['role-forms', 'form-validation'],
            '/admin/roles/edit': ['role-forms', 'form-validation'],

            // Default admin modules
            '/admin': ['admin-core']
        };

        // Find matching route
        for (const [route, modules] of Object.entries(routeMap)) {
            if (path.includes(route)) {
                return modules;
            }
        }

        // Default modules for admin area
        if (path.includes('/admin')) {
            return ['admin-core'];
        }

        return [];
    }

    /**
     * Load module dynamically
     */
    async loadModule(moduleName, options = {}) {
        const { priority = 'normal', timeout = 10000 } = options;

        // Check if already loaded
        if (this.loadedModules.has(moduleName)) {
            return this.moduleCache.get(moduleName);
        }

        // Check if currently loading
        if (this.loadingModules.has(moduleName)) {
            return this.loadingModules.get(moduleName);
        }

        // Start loading
        const loadPromise = this.performModuleLoad(moduleName, timeout);
        this.loadingModules.set(moduleName, loadPromise);

        try {
            const module = await loadPromise;
            this.loadedModules.add(moduleName);
            this.moduleCache.set(moduleName, module);
            this.loadingModules.delete(moduleName);

            console.log(`✅ Module loaded: ${moduleName}`);
            return module;
        } catch (error) {
            this.loadingModules.delete(moduleName);
            console.error(`❌ Failed to load module: ${moduleName}`, error);
            throw error;
        }
    }

    /**
     * Perform actual module loading
     */
    async performModuleLoad(moduleName, timeout) {
        const moduleUrl = this.getModuleUrl(moduleName);

        return new Promise((resolve, reject) => {
            const script = document.createElement('script');
            script.src = moduleUrl;
            script.async = true;

            const timeoutId = setTimeout(() => {
                reject(new Error(`Module load timeout: ${moduleName}`));
            }, timeout);

            script.onload = () => {
                clearTimeout(timeoutId);
                resolve(window[this.getModuleGlobalName(moduleName)] || {});
            };

            script.onerror = () => {
                clearTimeout(timeoutId);
                reject(new Error(`Failed to load script: ${moduleUrl}`));
            };

            document.head.appendChild(script);
        });
    }

    /**
     * Get module URL
     */
    getModuleUrl(moduleName) {
        const moduleMap = {
            'user-management': '/js/modules/user-management.js',
            'role-management': '/js/modules/role-management.js',
            'permission-management': '/js/modules/permission-management.js',
            'data-tables': '/js/modules/data-tables.js',
            'form-validation': '/js/modules/form-validation.js',
            'charts': '/js/modules/charts.js',
            'dashboard': '/js/modules/dashboard.js',
            'user-forms': '/js/modules/user-forms.js',
            'role-forms': '/js/modules/role-forms.js',
            'admin-core': '/js/modules/admin-core.js'
        };

        return moduleMap[moduleName] || `/js/modules/${moduleName}.js`;
    }

    /**
     * Get global variable name for module
     */
    getModuleGlobalName(moduleName) {
        const nameMap = {
            'user-management': 'UserManagement',
            'role-management': 'RoleManagement',
            'permission-management': 'PermissionManagement',
            'data-tables': 'DataTables',
            'form-validation': 'FormValidation',
            'charts': 'Charts',
            'dashboard': 'Dashboard'
        };

        return nameMap[moduleName] || moduleName.replace(/-([a-z])/g, (g) => g[1].toUpperCase());
    }

    /**
     * Setup lazy loading for interactive elements
     */
    setupLazyLoading() {
        // Load modules when user interacts with specific elements
        document.addEventListener('click', (e) => {
            const target = e.target.closest('[data-module]');
            if (target) {
                const moduleName = target.dataset.module;
                this.loadModule(moduleName, { priority: 'high' });
            }
        });

        // Load modules when hovering over navigation items
        document.addEventListener('mouseenter', (e) => {
            const target = e.target.closest('[data-preload-module]');
            if (target) {
                const moduleName = target.dataset.preloadModule;
                this.loadModule(moduleName, { priority: 'low' });
            }
        });
    }

    /**
     * Preload critical modules
     */
    preloadCriticalModules() {
        const criticalModules = ['admin-core'];

        criticalModules.forEach(module => {
            this.loadModule(module, { priority: 'high' });
        });
    }

    /**
     * Load modules based on user behavior prediction
     */
    predictiveLoad() {
        // Analyze user navigation patterns
        const currentPath = window.location.pathname;
        const likelyNextModules = this.predictNextModules(currentPath);

        likelyNextModules.forEach(module => {
            // Load with low priority during idle time
            requestIdleCallback(() => {
                this.loadModule(module, { priority: 'low' });
            });
        });
    }

    /**
     * Predict next modules based on current route
     */
    predictNextModules(currentPath) {
        const predictions = {
            '/admin/users': ['user-forms', 'role-management'],
            '/admin/roles': ['role-forms', 'permission-management'],
            '/admin/permissions': ['permission-management'],
            '/admin/dashboard': ['user-management', 'role-management']
        };

        return predictions[currentPath] || [];
    }

    /**
     * Get loading statistics
     */
    getStats() {
        return {
            loaded: this.loadedModules.size,
            loading: this.loadingModules.size,
            cached: this.moduleCache.size,
            loadedModules: Array.from(this.loadedModules),
            loadingModules: Array.from(this.loadingModules.keys())
        };
    }

    /**
     * Preload module for future use
     */
    preload(moduleName) {
        return this.loadModule(moduleName, { priority: 'low' });
    }

    /**
     * Unload module to free memory
     */
    unload(moduleName) {
        this.loadedModules.delete(moduleName);
        this.moduleCache.delete(moduleName);

        // Remove script tag if exists
        const script = document.querySelector(`script[src*="${moduleName}"]`);
        if (script) {
            script.remove();
        }

        console.log(`🗑️ Module unloaded: ${moduleName}`);
    }

    /**
     * Log module loading report
     */
    logReport() {
        const stats = this.getStats();

        console.group('📦 Module Loading Report');
        console.log('✅ Loaded modules:', stats.loaded);
        console.log('⏳ Loading modules:', stats.loading);
        console.log('💾 Cached modules:', stats.cached);
        console.log('📋 Module list:', stats.loadedModules);
        console.groupEnd();

        return stats;
    }
}

// Auto-initialize module loader
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if modules are enabled
    if (window.enableModuleLoader !== false && window.location.hostname !== 'web.local') {
        window.moduleLoader = new ModuleLoader();

        // Start predictive loading after page is settled
        setTimeout(() => {
            window.moduleLoader.predictiveLoad();
        }, 2000);

        // Log report
        setTimeout(() => {
            window.moduleLoader.logReport();
        }, 3000);
    } else {
        // Create dummy module loader for development
        window.moduleLoader = {
            loadModule: () => Promise.resolve(),
            preload: () => Promise.resolve(),
            getStats: () => ({ loaded: 0, loading: 0, cached: 0 }),
            logReport: () => ({ loaded: 0, loading: 0, cached: 0 })
        };
    }
});

// Export for manual use
window.ModuleLoader = ModuleLoader;

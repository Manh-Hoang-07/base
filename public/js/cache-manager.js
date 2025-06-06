/**
 * Cache Manager - Advanced browser caching strategies
 * Implements intelligent caching for API responses, static assets, and user data
 */

class CacheManager {
    constructor() {
        this.memoryCache = new Map();
        this.storageCache = new Map();
        this.cacheConfig = {
            // Cache durations in milliseconds
            api: 5 * 60 * 1000,        // 5 minutes
            static: 24 * 60 * 60 * 1000, // 24 hours
            user: 30 * 60 * 1000,      // 30 minutes
            session: 60 * 60 * 1000    // 1 hour
        };
        this.maxMemorySize = 50; // Maximum items in memory cache
        this.init();
    }

    /**
     * Initialize cache manager
     */
    init() {
        this.setupStorageEventListener();
        this.setupPeriodicCleanup();
        this.loadFromStorage();
    }

    /**
     * Cache API response
     */
    cacheAPI(url, data, options = {}) {
        const key = this.generateKey('api', url);
        const duration = options.duration || this.cacheConfig.api;
        
        const cacheItem = {
            data: data,
            timestamp: Date.now(),
            duration: duration,
            type: 'api',
            url: url
        };
        
        // Store in memory cache
        this.memoryCache.set(key, cacheItem);
        
        // Store in localStorage for persistence
        if (options.persist !== false) {
            this.setStorageItem(key, cacheItem);
        }
        
        this.enforceMemoryLimit();
        
        console.log(`💾 Cached API response: ${url}`);
    }

    /**
     * Get cached API response
     */
    getCachedAPI(url) {
        const key = this.generateKey('api', url);
        
        // Check memory cache first
        let item = this.memoryCache.get(key);
        
        // Check storage cache if not in memory
        if (!item) {
            item = this.getStorageItem(key);
            if (item) {
                // Move back to memory cache
                this.memoryCache.set(key, item);
            }
        }
        
        if (item && this.isValid(item)) {
            console.log(`✅ Cache hit for API: ${url}`);
            return item.data;
        }
        
        // Remove expired item
        if (item) {
            this.remove(key);
        }
        
        console.log(`❌ Cache miss for API: ${url}`);
        return null;
    }

    /**
     * Cache user data
     */
    cacheUser(userId, userData, options = {}) {
        const key = this.generateKey('user', userId);
        const duration = options.duration || this.cacheConfig.user;
        
        const cacheItem = {
            data: userData,
            timestamp: Date.now(),
            duration: duration,
            type: 'user',
            userId: userId
        };
        
        this.memoryCache.set(key, cacheItem);
        this.setStorageItem(key, cacheItem);
        
        console.log(`👤 Cached user data: ${userId}`);
    }

    /**
     * Get cached user data
     */
    getCachedUser(userId) {
        const key = this.generateKey('user', userId);
        return this.getCachedItem(key);
    }

    /**
     * Cache session data
     */
    cacheSession(sessionKey, data, options = {}) {
        const key = this.generateKey('session', sessionKey);
        const duration = options.duration || this.cacheConfig.session;
        
        const cacheItem = {
            data: data,
            timestamp: Date.now(),
            duration: duration,
            type: 'session'
        };
        
        // Session data only in sessionStorage
        sessionStorage.setItem(key, JSON.stringify(cacheItem));
        
        console.log(`🔑 Cached session data: ${sessionKey}`);
    }

    /**
     * Get cached session data
     */
    getCachedSession(sessionKey) {
        const key = this.generateKey('session', sessionKey);
        
        try {
            const item = JSON.parse(sessionStorage.getItem(key));
            if (item && this.isValid(item)) {
                return item.data;
            }
            
            if (item) {
                sessionStorage.removeItem(key);
            }
        } catch (error) {
            console.error('Error reading session cache:', error);
        }
        
        return null;
    }

    /**
     * Enhanced AJAX with caching
     */
    cachedAjax(options) {
        const { url, method = 'GET', cache = true, cacheKey } = options;
        
        // Only cache GET requests
        if (method.toUpperCase() !== 'GET' || !cache) {
            return $.ajax(options);
        }
        
        const key = cacheKey || url;
        const cachedData = this.getCachedAPI(key);
        
        if (cachedData) {
            // Return cached data as a resolved promise
            return Promise.resolve(cachedData);
        }
        
        // Make AJAX request and cache response
        return $.ajax(options).then((data) => {
            this.cacheAPI(key, data, options.cacheOptions);
            return data;
        });
    }

    /**
     * Preload and cache resources
     */
    preloadResources(urls, options = {}) {
        const promises = urls.map(url => {
            return this.cachedAjax({
                url: url,
                cache: true,
                cacheOptions: options
            }).catch(error => {
                console.warn(`Failed to preload: ${url}`, error);
                return null;
            });
        });
        
        return Promise.allSettled(promises);
    }

    /**
     * Cache form data for recovery
     */
    cacheFormData(formId, data) {
        const key = this.generateKey('form', formId);
        
        const cacheItem = {
            data: data,
            timestamp: Date.now(),
            duration: this.cacheConfig.session,
            type: 'form'
        };
        
        sessionStorage.setItem(key, JSON.stringify(cacheItem));
        console.log(`📝 Cached form data: ${formId}`);
    }

    /**
     * Get cached form data
     */
    getCachedFormData(formId) {
        const key = this.generateKey('form', formId);
        return this.getCachedSession(formId);
    }

    /**
     * Clear form cache
     */
    clearFormCache(formId) {
        const key = this.generateKey('form', formId);
        sessionStorage.removeItem(key);
    }

    /**
     * Utility methods
     */
    generateKey(type, identifier) {
        return `cache_${type}_${identifier}`;
    }

    isValid(item) {
        if (!item || !item.timestamp || !item.duration) {
            return false;
        }
        
        return (Date.now() - item.timestamp) < item.duration;
    }

    getCachedItem(key) {
        let item = this.memoryCache.get(key);
        
        if (!item) {
            item = this.getStorageItem(key);
            if (item) {
                this.memoryCache.set(key, item);
            }
        }
        
        if (item && this.isValid(item)) {
            return item.data;
        }
        
        if (item) {
            this.remove(key);
        }
        
        return null;
    }

    setStorageItem(key, item) {
        try {
            localStorage.setItem(key, JSON.stringify(item));
            this.storageCache.set(key, item);
        } catch (error) {
            console.warn('Failed to store in localStorage:', error);
            // Handle storage quota exceeded
            this.cleanupStorage();
        }
    }

    getStorageItem(key) {
        try {
            const stored = localStorage.getItem(key);
            if (stored) {
                const item = JSON.parse(stored);
                this.storageCache.set(key, item);
                return item;
            }
        } catch (error) {
            console.error('Error reading from localStorage:', error);
            localStorage.removeItem(key);
        }
        
        return null;
    }

    remove(key) {
        this.memoryCache.delete(key);
        this.storageCache.delete(key);
        localStorage.removeItem(key);
    }

    enforceMemoryLimit() {
        if (this.memoryCache.size > this.maxMemorySize) {
            // Remove oldest items
            const entries = Array.from(this.memoryCache.entries());
            entries.sort((a, b) => a[1].timestamp - b[1].timestamp);
            
            const toRemove = entries.slice(0, entries.length - this.maxMemorySize);
            toRemove.forEach(([key]) => {
                this.memoryCache.delete(key);
            });
            
            console.log(`🧹 Cleaned up ${toRemove.length} items from memory cache`);
        }
    }

    setupStorageEventListener() {
        window.addEventListener('storage', (e) => {
            if (e.key && e.key.startsWith('cache_')) {
                // Sync with other tabs
                if (e.newValue) {
                    try {
                        const item = JSON.parse(e.newValue);
                        this.storageCache.set(e.key, item);
                    } catch (error) {
                        console.error('Error syncing cache from storage event:', error);
                    }
                } else {
                    this.storageCache.delete(e.key);
                }
            }
        });
    }

    setupPeriodicCleanup() {
        // Clean up expired items every 5 minutes
        setInterval(() => {
            this.cleanupExpired();
        }, 5 * 60 * 1000);
    }

    cleanupExpired() {
        let cleaned = 0;
        
        // Clean memory cache
        for (const [key, item] of this.memoryCache.entries()) {
            if (!this.isValid(item)) {
                this.memoryCache.delete(key);
                cleaned++;
            }
        }
        
        // Clean storage cache
        for (const [key, item] of this.storageCache.entries()) {
            if (!this.isValid(item)) {
                this.storageCache.delete(key);
                localStorage.removeItem(key);
                cleaned++;
            }
        }
        
        if (cleaned > 0) {
            console.log(`🧹 Cleaned up ${cleaned} expired cache items`);
        }
    }

    cleanupStorage() {
        // Remove oldest items when storage is full
        const entries = Array.from(this.storageCache.entries());
        entries.sort((a, b) => a[1].timestamp - b[1].timestamp);
        
        const toRemove = entries.slice(0, Math.ceil(entries.length * 0.3));
        toRemove.forEach(([key]) => {
            this.remove(key);
        });
        
        console.log(`🧹 Cleaned up ${toRemove.length} items due to storage limit`);
    }

    loadFromStorage() {
        // Load existing cache items from localStorage
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('cache_')) {
                this.getStorageItem(key);
            }
        }
    }

    /**
     * Clear all cache
     */
    clearAll() {
        this.memoryCache.clear();
        
        // Clear localStorage cache items
        const keysToRemove = [];
        for (let i = 0; i < localStorage.length; i++) {
            const key = localStorage.key(i);
            if (key && key.startsWith('cache_')) {
                keysToRemove.push(key);
            }
        }
        
        keysToRemove.forEach(key => localStorage.removeItem(key));
        this.storageCache.clear();
        
        console.log('🧹 All cache cleared');
    }

    /**
     * Get cache statistics
     */
    getStats() {
        const memorySize = this.memoryCache.size;
        const storageSize = this.storageCache.size;
        
        let totalMemoryBytes = 0;
        for (const item of this.memoryCache.values()) {
            totalMemoryBytes += JSON.stringify(item).length;
        }
        
        return {
            memory: {
                items: memorySize,
                bytes: totalMemoryBytes,
                kb: (totalMemoryBytes / 1024).toFixed(2)
            },
            storage: {
                items: storageSize
            },
            config: this.cacheConfig
        };
    }

    /**
     * Log cache report
     */
    logReport() {
        const stats = this.getStats();
        
        console.group('💾 Cache Manager Report');
        console.log('🧠 Memory cache:', `${stats.memory.items} items (${stats.memory.kb} KB)`);
        console.log('💿 Storage cache:', `${stats.storage.items} items`);
        console.log('⚙️ Cache config:', stats.config);
        console.groupEnd();
        
        return stats;
    }
}

// Auto-initialize cache manager
document.addEventListener('DOMContentLoaded', function() {
    window.cacheManager = new CacheManager();
    
    // Log report in development
    if (window.location.hostname === 'web.local') {
        setTimeout(() => {
            window.cacheManager.logReport();
        }, 3000);
    }
});

// Export for manual use
window.CacheManager = CacheManager;

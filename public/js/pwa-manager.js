/**
 * PWA Manager - Progressive Web App functionality
 * Handles service worker registration, offline detection, and app installation
 */

class PWAManager {
    constructor() {
        this.isOnline = navigator.onLine;
        this.serviceWorker = null;
        this.deferredPrompt = null;
        this.init();
    }

    /**
     * Initialize PWA functionality
     */
    init() {
        this.registerServiceWorker();
        this.setupOfflineDetection();
        this.setupInstallPrompt();
        this.setupNotifications();
        this.createOfflineIndicator();
    }

    /**
     * Register service worker
     */
    async registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            try {
                const registration = await navigator.serviceWorker.register('/sw.js');
                this.serviceWorker = registration;
                
                console.log('✅ Service Worker registered successfully');
                
                // Handle updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {
                        if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                            this.showUpdateAvailable();
                        }
                    });
                });
                
                // Listen for messages from service worker
                navigator.serviceWorker.addEventListener('message', (event) => {
                    this.handleServiceWorkerMessage(event.data);
                });
                
            } catch (error) {
                console.error('❌ Service Worker registration failed:', error);
            }
        } else {
            console.warn('⚠️ Service Worker not supported');
        }
    }

    /**
     * Setup offline/online detection
     */
    setupOfflineDetection() {
        window.addEventListener('online', () => {
            this.isOnline = true;
            this.updateOfflineIndicator();
            this.syncWhenOnline();
            
            if (window.LoadingUtils) {
                window.LoadingUtils.showToast('Đã kết nối lại internet', 'success');
            }
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.updateOfflineIndicator();
            
            if (window.LoadingUtils) {
                window.LoadingUtils.showToast('Mất kết nối internet. Đang hoạt động offline.', 'info');
            }
        });
    }

    /**
     * Setup app installation prompt
     */
    setupInstallPrompt() {
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        window.addEventListener('appinstalled', () => {
            console.log('✅ PWA installed successfully');
            this.hideInstallButton();
            
            if (window.LoadingUtils) {
                window.LoadingUtils.showToast('Ứng dụng đã được cài đặt!', 'success');
            }
        });
    }

    /**
     * Setup push notifications
     */
    async setupNotifications() {
        if ('Notification' in window && 'serviceWorker' in navigator) {
            const permission = await Notification.requestPermission();
            
            if (permission === 'granted') {
                console.log('✅ Notification permission granted');
                this.subscribeToNotifications();
            } else {
                console.log('❌ Notification permission denied');
            }
        }
    }

    /**
     * Subscribe to push notifications
     */
    async subscribeToNotifications() {
        try {
            if (this.serviceWorker) {
                const subscription = await this.serviceWorker.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: this.urlBase64ToUint8Array(this.getVapidPublicKey())
                });
                
                // Send subscription to server
                await this.sendSubscriptionToServer(subscription);
                console.log('✅ Push notification subscription successful');
            }
        } catch (error) {
            console.error('❌ Push notification subscription failed:', error);
        }
    }

    /**
     * Create offline indicator
     */
    createOfflineIndicator() {
        const indicator = document.createElement('div');
        indicator.id = 'offline-indicator';
        indicator.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #f39c12;
            color: white;
            text-align: center;
            padding: 0.5rem;
            z-index: 10002;
            transform: translateY(-100%);
            transition: transform 0.3s ease;
            font-size: 0.875rem;
        `;
        indicator.innerHTML = '📡 Không có kết nối internet - Đang hoạt động offline';
        
        document.body.appendChild(indicator);
        this.updateOfflineIndicator();
    }

    /**
     * Update offline indicator visibility
     */
    updateOfflineIndicator() {
        const indicator = document.getElementById('offline-indicator');
        if (indicator) {
            if (this.isOnline) {
                indicator.style.transform = 'translateY(-100%)';
            } else {
                indicator.style.transform = 'translateY(0)';
            }
        }
    }

    /**
     * Show install button
     */
    showInstallButton() {
        let installButton = document.getElementById('pwa-install-button');
        
        if (!installButton) {
            installButton = document.createElement('button');
            installButton.id = 'pwa-install-button';
            installButton.innerHTML = '📱 Cài đặt ứng dụng';
            installButton.style.cssText = `
                position: fixed;
                bottom: 20px;
                right: 20px;
                background: var(--admin-primary);
                color: white;
                border: none;
                padding: 0.75rem 1rem;
                border-radius: 0.5rem;
                cursor: pointer;
                z-index: 10001;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                font-size: 0.875rem;
                transition: all 0.3s ease;
            `;
            
            installButton.addEventListener('click', () => {
                this.installApp();
            });
            
            installButton.addEventListener('mouseenter', () => {
                installButton.style.transform = 'scale(1.05)';
            });
            
            installButton.addEventListener('mouseleave', () => {
                installButton.style.transform = 'scale(1)';
            });
            
            document.body.appendChild(installButton);
        }
        
        installButton.style.display = 'block';
    }

    /**
     * Hide install button
     */
    hideInstallButton() {
        const installButton = document.getElementById('pwa-install-button');
        if (installButton) {
            installButton.style.display = 'none';
        }
    }

    /**
     * Install PWA
     */
    async installApp() {
        if (this.deferredPrompt) {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('✅ User accepted the install prompt');
            } else {
                console.log('❌ User dismissed the install prompt');
            }
            
            this.deferredPrompt = null;
            this.hideInstallButton();
        }
    }

    /**
     * Show update available notification
     */
    showUpdateAvailable() {
        const updateBanner = document.createElement('div');
        updateBanner.id = 'update-banner';
        updateBanner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #3498db;
            color: white;
            text-align: center;
            padding: 1rem;
            z-index: 10003;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
        `;
        
        updateBanner.innerHTML = `
            <span>🔄 Có phiên bản mới của ứng dụng</span>
            <button id="update-button" style="
                background: white;
                color: #3498db;
                border: none;
                padding: 0.5rem 1rem;
                border-radius: 0.25rem;
                cursor: pointer;
                font-weight: bold;
            ">Cập nhật ngay</button>
            <button id="dismiss-update" style="
                background: transparent;
                color: white;
                border: 1px solid white;
                padding: 0.5rem 1rem;
                border-radius: 0.25rem;
                cursor: pointer;
            ">Để sau</button>
        `;
        
        document.body.appendChild(updateBanner);
        
        document.getElementById('update-button').addEventListener('click', () => {
            this.updateApp();
        });
        
        document.getElementById('dismiss-update').addEventListener('click', () => {
            updateBanner.remove();
        });
    }

    /**
     * Update app
     */
    updateApp() {
        if (this.serviceWorker && this.serviceWorker.waiting) {
            this.serviceWorker.waiting.postMessage({ type: 'SKIP_WAITING' });
            window.location.reload();
        }
    }

    /**
     * Sync data when coming back online
     */
    async syncWhenOnline() {
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            try {
                await this.serviceWorker.sync.register('background-sync');
                console.log('🔄 Background sync registered');
            } catch (error) {
                console.error('❌ Background sync registration failed:', error);
            }
        }
    }

    /**
     * Handle messages from service worker
     */
    handleServiceWorkerMessage(data) {
        console.log('💬 Message from Service Worker:', data);
        
        if (data.type === 'CACHE_UPDATED') {
            if (window.LoadingUtils) {
                window.LoadingUtils.showToast('Cache đã được cập nhật', 'info');
            }
        }
    }

    /**
     * Get cache information
     */
    async getCacheInfo() {
        if (this.serviceWorker) {
            return new Promise((resolve) => {
                const messageChannel = new MessageChannel();
                messageChannel.port1.onmessage = (event) => {
                    resolve(event.data);
                };
                
                this.serviceWorker.active.postMessage(
                    { type: 'GET_CACHE_SIZE' },
                    [messageChannel.port2]
                );
            });
        }
        return null;
    }

    /**
     * Utility functions
     */
    urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');
        
        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);
        
        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    getVapidPublicKey() {
        // Replace with your actual VAPID public key
        return 'BEl62iUYgUivxIkv69yViEuiBIa40HI80NM9f8HnKJuOmLsOH8Gk0FIFj4gCh-WiHi9VOcjjQ3aTQBqq0TfVxAo';
    }

    async sendSubscriptionToServer(subscription) {
        // Send subscription to your server
        try {
            await fetch('/api/push-subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: JSON.stringify(subscription)
            });
        } catch (error) {
            console.error('Failed to send subscription to server:', error);
        }
    }

    /**
     * Get PWA status
     */
    getStatus() {
        return {
            isOnline: this.isOnline,
            serviceWorkerRegistered: !!this.serviceWorker,
            installPromptAvailable: !!this.deferredPrompt,
            notificationsEnabled: Notification.permission === 'granted'
        };
    }

    /**
     * Log PWA report
     */
    async logReport() {
        const status = this.getStatus();
        const cacheInfo = await this.getCacheInfo();
        
        console.group('📱 PWA Status Report');
        console.log('🌐 Online status:', status.isOnline ? 'Online' : 'Offline');
        console.log('⚙️ Service Worker:', status.serviceWorkerRegistered ? 'Registered' : 'Not registered');
        console.log('📱 Install prompt:', status.installPromptAvailable ? 'Available' : 'Not available');
        console.log('🔔 Notifications:', status.notificationsEnabled ? 'Enabled' : 'Disabled');
        
        if (cacheInfo) {
            console.log('💾 Cache size:', `${(cacheInfo.cacheSize / 1024 / 1024).toFixed(2)} MB`);
        }
        
        console.groupEnd();
        
        return { status, cacheInfo };
    }
}

// Auto-initialize PWA manager
document.addEventListener('DOMContentLoaded', function() {
    window.pwaManager = new PWAManager();
    
    // Log report in development
    if (window.location.hostname === 'web.local') {
        setTimeout(() => {
            window.pwaManager.logReport();
        }, 3000);
    }
});

// Export for manual use
window.PWAManager = PWAManager;

// Service Worker for Performance Optimization
const CACHE_NAME = 'laravel-app-v1';
const STATIC_CACHE = 'static-v1';
const DYNAMIC_CACHE = 'dynamic-v1';

// Assets to cache immediately
const STATIC_ASSETS = [
    '/',
    '/build/assets/app.css',
    '/build/assets/app.js',
    '/build/assets/admin.css',
    '/build/assets/admin.js',
    '/fonts/admin/Nunito-Regular.woff2',
    '/fonts/admin/Nunito-SemiBold.woff2',
    '/adminlte/css/adminlte.min.css',
    '/adminlte/js/adminlte.min.js',
    '/js/jquery-3.6.0.min.js',
    '/offline.html'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    console.log('Service Worker installing...');
    
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Caching static assets...');
                return cache.addAll(STATIC_ASSETS);
            })
            .catch((error) => {
                console.error('Failed to cache static assets:', error);
            })
    );
    
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('Service Worker activating...');
    
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    
    self.clients.claim();
});

// Fetch event - serve from cache with network fallback
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);
    
    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }
    
    // Skip external requests
    if (url.origin !== location.origin) {
        return;
    }
    
    // Handle different types of requests
    if (isStaticAsset(request.url)) {
        event.respondWith(cacheFirst(request));
    } else if (isAPIRequest(request.url)) {
        event.respondWith(networkFirst(request));
    } else if (isPageRequest(request)) {
        event.respondWith(staleWhileRevalidate(request));
    }
});

// Cache strategies
async function cacheFirst(request) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        
        return networkResponse;
    } catch (error) {
        console.error('Cache first strategy failed:', error);
        return new Response('Offline', { status: 503 });
    }
}

async function networkFirst(request) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Network first strategy failed:', error);
        const cachedResponse = await caches.match(request);
        return cachedResponse || new Response('Offline', { status: 503 });
    }
}

async function staleWhileRevalidate(request) {
    try {
        const cache = await caches.open(DYNAMIC_CACHE);
        const cachedResponse = await cache.match(request);
        
        const networkResponsePromise = fetch(request).then((networkResponse) => {
            if (networkResponse.ok) {
                cache.put(request, networkResponse.clone());
            }
            return networkResponse;
        });
        
        return cachedResponse || await networkResponsePromise;
    } catch (error) {
        console.error('Stale while revalidate strategy failed:', error);
        return await caches.match('/offline.html') || new Response('Offline', { status: 503 });
    }
}

// Helper functions
function isStaticAsset(url) {
    return /\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/i.test(url);
}

function isAPIRequest(url) {
    return url.includes('/api/') || url.includes('/admin/api/');
}

function isPageRequest(request) {
    return request.destination === 'document';
}

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    try {
        // Handle offline actions when back online
        const offlineActions = await getOfflineActions();
        for (const action of offlineActions) {
            await processOfflineAction(action);
        }
        await clearOfflineActions();
    } catch (error) {
        console.error('Background sync failed:', error);
    }
}

// Push notifications
self.addEventListener('push', (event) => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/images/icon-192x192.png',
            badge: '/images/badge-72x72.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            },
            actions: [
                {
                    action: 'explore',
                    title: 'Xem chi tiết',
                    icon: '/images/checkmark.png'
                },
                {
                    action: 'close',
                    title: 'Đóng',
                    icon: '/images/xmark.png'
                }
            ]
        };
        
        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handling
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    
    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/notifications/' + event.notification.data.primaryKey)
        );
    }
});

// Utility functions for offline storage
async function getOfflineActions() {
    // Implement offline action storage
    return [];
}

async function processOfflineAction(action) {
    // Process offline actions
    console.log('Processing offline action:', action);
}

async function clearOfflineActions() {
    // Clear processed offline actions
    console.log('Clearing offline actions');
}

// Performance monitoring
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'PERFORMANCE_METRICS') {
        console.log('Performance metrics received:', event.data.metrics);
        // Send to analytics service
    }
});

console.log('Service Worker loaded successfully');

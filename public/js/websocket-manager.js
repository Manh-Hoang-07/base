/**
 * WebSocket Manager - Real-time communication
 * Handles WebSocket connections, real-time updates, and live notifications
 */

class WebSocketManager {
    constructor() {
        this.socket = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectInterval = 1000;
        this.heartbeatInterval = null;
        this.listeners = new Map();
        this.isConnected = false;
        this.init();
    }

    /**
     * Initialize WebSocket connection
     */
    init() {
        this.connect();
        this.setupHeartbeat();
        this.setupPageVisibilityHandling();
    }

    /**
     * Connect to WebSocket server
     */
    connect() {
        try {
            // Use appropriate WebSocket URL based on environment
            const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
            const host = window.location.host;
            const wsUrl = `${protocol}//${host}/ws`;

            console.log('🔌 Connecting to WebSocket:', wsUrl);

            this.socket = new WebSocket(wsUrl);

            this.socket.onopen = (event) => {
                this.onOpen(event);
            };

            this.socket.onmessage = (event) => {
                this.onMessage(event);
            };

            this.socket.onclose = (event) => {
                this.onClose(event);
            };

            this.socket.onerror = (event) => {
                this.onError(event);
            };

        } catch (error) {
            console.error('❌ WebSocket connection failed:', error);
            this.scheduleReconnect();
        }
    }

    /**
     * Handle WebSocket open event
     */
    onOpen(event) {
        console.log('✅ WebSocket connected');
        this.isConnected = true;
        this.reconnectAttempts = 0;

        // Send authentication if needed
        this.authenticate();

        // Notify listeners
        this.emit('connected', { event });

        // Show connection status
        if (window.LoadingUtils) {
            window.LoadingUtils.showToast('Kết nối real-time thành công', 'success');
        }

        this.updateConnectionIndicator(true);
    }

    /**
     * Handle WebSocket message event
     */
    onMessage(event) {
        try {
            const data = JSON.parse(event.data);
            console.log('📨 WebSocket message received:', data);

            // Handle different message types
            switch (data.type) {
                case 'notification':
                    this.handleNotification(data);
                    break;
                case 'user_update':
                    this.handleUserUpdate(data);
                    break;
                case 'role_update':
                    this.handleRoleUpdate(data);
                    break;
                case 'permission_update':
                    this.handlePermissionUpdate(data);
                    break;
                case 'system_update':
                    this.handleSystemUpdate(data);
                    break;
                case 'heartbeat':
                    this.handleHeartbeat(data);
                    break;
                default:
                    this.emit('message', data);
            }

        } catch (error) {
            console.error('❌ Failed to parse WebSocket message:', error);
        }
    }

    /**
     * Handle WebSocket close event
     */
    onClose(event) {
        console.log('🔌 WebSocket disconnected:', event.code, event.reason);
        this.isConnected = false;

        this.emit('disconnected', { event });
        this.updateConnectionIndicator(false);

        // Attempt to reconnect unless it was a clean close
        if (event.code !== 1000) {
            this.scheduleReconnect();
        }
    }

    /**
     * Handle WebSocket error event
     */
    onError(event) {
        console.error('❌ WebSocket error:', event);
        this.emit('error', { event });
    }

    /**
     * Schedule reconnection attempt
     */
    scheduleReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            const delay = this.reconnectInterval * Math.pow(2, this.reconnectAttempts - 1);

            console.log(`🔄 Reconnecting in ${delay}ms (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);

            setTimeout(() => {
                this.connect();
            }, delay);
        } else {
            console.error('❌ Max reconnection attempts reached');
            if (window.LoadingUtils) {
                window.LoadingUtils.showToast('Mất kết nối real-time', 'error');
            }
        }
    }

    /**
     * Send authentication token
     */
    authenticate() {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (token) {
            this.send({
                type: 'auth',
                token: token,
                user_id: this.getCurrentUserId()
            });
        }
    }

    /**
     * Setup heartbeat to keep connection alive
     */
    setupHeartbeat() {
        this.heartbeatInterval = setInterval(() => {
            if (this.isConnected) {
                this.send({ type: 'ping' });
            }
        }, 30000); // Send ping every 30 seconds
    }

    /**
     * Setup page visibility handling
     */
    setupPageVisibilityHandling() {
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Page is hidden, reduce activity
                this.pauseHeartbeat();
            } else {
                // Page is visible, resume activity
                this.resumeHeartbeat();
                if (!this.isConnected) {
                    this.connect();
                }
            }
        });
    }

    /**
     * Send message through WebSocket
     */
    send(data) {
        if (this.isConnected && this.socket.readyState === WebSocket.OPEN) {
            this.socket.send(JSON.stringify(data));
            return true;
        } else {
            console.warn('⚠️ WebSocket not connected, message queued');
            // Could implement message queuing here
            return false;
        }
    }

    /**
     * Handle different types of real-time updates
     */
    handleNotification(data) {
        console.log('🔔 Real-time notification:', data);

        // Show browser notification if permission granted
        if (Notification.permission === 'granted') {
            new Notification(data.title || 'Thông báo mới', {
                body: data.message,
                icon: '/images/icon-192x192.png',
                tag: data.id || 'notification'
            });
        }

        // Show in-app notification
        if (window.LoadingUtils) {
            window.LoadingUtils.showToast(data.message, 'info');
        }

        this.emit('notification', data);
    }

    handleUserUpdate(data) {
        console.log('👤 User update:', data);

        // Update user list if on users page
        if (window.location.pathname.includes('/admin/users')) {
            this.refreshUserList();
        }

        this.emit('user_update', data);
    }

    handleRoleUpdate(data) {
        console.log('🎭 Role update:', data);

        // Update role list if on roles page
        if (window.location.pathname.includes('/admin/roles')) {
            this.refreshRoleList();
        }

        this.emit('role_update', data);
    }

    handlePermissionUpdate(data) {
        console.log('🔐 Permission update:', data);

        // Update permission list if on permissions page
        if (window.location.pathname.includes('/admin/permissions')) {
            this.refreshPermissionList();
        }

        this.emit('permission_update', data);
    }

    handleSystemUpdate(data) {
        console.log('⚙️ System update:', data);

        if (data.action === 'maintenance_mode') {
            this.showMaintenanceNotice(data);
        }

        this.emit('system_update', data);
    }

    handleHeartbeat(data) {
        // Respond to server heartbeat
        this.send({ type: 'pong' });
    }

    /**
     * Event listener management
     */
    on(event, callback) {
        if (!this.listeners.has(event)) {
            this.listeners.set(event, []);
        }
        this.listeners.get(event).push(callback);
    }

    off(event, callback) {
        if (this.listeners.has(event)) {
            const callbacks = this.listeners.get(event);
            const index = callbacks.indexOf(callback);
            if (index > -1) {
                callbacks.splice(index, 1);
            }
        }
    }

    emit(event, data) {
        if (this.listeners.has(event)) {
            this.listeners.get(event).forEach(callback => {
                try {
                    callback(data);
                } catch (error) {
                    console.error('❌ Error in WebSocket event listener:', error);
                }
            });
        }
    }

    /**
     * Utility methods
     */
    getCurrentUserId() {
        // Get current user ID from meta tag or global variable
        return document.querySelector('meta[name="user-id"]')?.getAttribute('content') || null;
    }

    pauseHeartbeat() {
        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
            this.heartbeatInterval = null;
        }
    }

    resumeHeartbeat() {
        if (!this.heartbeatInterval) {
            this.setupHeartbeat();
        }
    }

    updateConnectionIndicator(connected) {
        let indicator = document.getElementById('websocket-indicator');

        if (!indicator) {
            indicator = document.createElement('div');
            indicator.id = 'websocket-indicator';
            indicator.style.cssText = `
                position: fixed;
                top: 10px;
                right: 10px;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                z-index: 10004;
                transition: all 0.3s ease;
            `;
            document.body.appendChild(indicator);
        }

        if (connected) {
            indicator.style.backgroundColor = '#27ae60';
            indicator.title = 'Real-time kết nối';
        } else {
            indicator.style.backgroundColor = '#e74c3c';
            indicator.title = 'Real-time ngắt kết nối';
        }
    }

    refreshUserList() {
        // Implement user list refresh logic
        console.log('🔄 Refreshing user list...');
    }

    refreshRoleList() {
        // Implement role list refresh logic
        console.log('🔄 Refreshing role list...');
    }

    refreshPermissionList() {
        // Implement permission list refresh logic
        console.log('🔄 Refreshing permission list...');
    }

    showMaintenanceNotice(data) {
        if (window.LoadingUtils) {
            window.LoadingUtils.showToast(
                `Hệ thống sẽ bảo trì trong ${data.minutes} phút`,
                'warning'
            );
        }
    }

    /**
     * Disconnect WebSocket
     */
    disconnect() {
        if (this.socket) {
            this.socket.close(1000, 'Manual disconnect');
        }

        if (this.heartbeatInterval) {
            clearInterval(this.heartbeatInterval);
        }
    }

    /**
     * Get connection status
     */
    getStatus() {
        return {
            connected: this.isConnected,
            reconnectAttempts: this.reconnectAttempts,
            readyState: this.socket ? this.socket.readyState : null,
            listeners: this.listeners.size
        };
    }

    /**
     * Log WebSocket report
     */
    logReport() {
        const status = this.getStatus();

        console.group('🔌 WebSocket Status Report');
        console.log('🌐 Connected:', status.connected);
        console.log('🔄 Reconnect attempts:', status.reconnectAttempts);
        console.log('📡 Ready state:', status.readyState);
        console.log('👂 Event listeners:', status.listeners);
        console.groupEnd();

        return status;
    }
}

// Auto-initialize WebSocket manager
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if in admin area AND WebSocket is explicitly enabled
    if (window.location.pathname.includes('/admin') && window.enableWebSocket !== false) {
        // Check if WebSocket server is available before connecting
        if (window.location.hostname === 'web.local') {
            // In development, only connect if explicitly enabled
            if (localStorage.getItem('enableWebSocket') === 'true') {
                window.wsManager = new WebSocketManager();
                setTimeout(() => {
                    window.wsManager.logReport();
                }, 3000);
            } else {
                console.log('🔌 WebSocket disabled in development. Enable with: localStorage.setItem("enableWebSocket", "true")');
            }
        } else {
            // In production, always try to connect
            window.wsManager = new WebSocketManager();
        }
    }
});

// Export for manual use
window.WebSocketManager = WebSocketManager;

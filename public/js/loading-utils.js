/**
 * Loading States Utilities
 * Provides loading indicators and error handling for better UX
 */

class LoadingUtils {
    constructor() {
        this.activeLoaders = new Set();
    }

    /**
     * Show global loading overlay
     */
    showGlobalLoading(message = 'Đang tải...') {
        if (document.getElementById('global-loading')) return;

        const overlay = document.createElement('div');
        overlay.id = 'global-loading';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div style="text-align: center; color: white;">
                <div class="loading-spinner"></div>
                <div style="margin-top: 1rem;">${message}</div>
            </div>
        `;
        document.body.appendChild(overlay);
    }

    /**
     * Hide global loading overlay
     */
    hideGlobalLoading() {
        const overlay = document.getElementById('global-loading');
        if (overlay) {
            overlay.remove();
        }
    }

    /**
     * Add loading state to button
     */
    setButtonLoading(button, loading = true) {
        if (typeof button === 'string') {
            button = document.querySelector(button);
        }
        
        if (!button) return;

        if (loading) {
            button.classList.add('btn-loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = 'Đang xử lý...';
        } else {
            button.classList.remove('btn-loading');
            button.disabled = false;
            if (button.dataset.originalText) {
                button.textContent = button.dataset.originalText;
                delete button.dataset.originalText;
            }
        }
    }

    /**
     * Add loading state to Select2
     */
    setSelect2Loading(selector, loading = true) {
        const container = $(selector).next('.select2-container');
        
        if (loading) {
            container.addClass('select2-loading');
        } else {
            container.removeClass('select2-loading');
        }
    }

    /**
     * Show error message
     */
    showError(container, title, message, retryCallback = null) {
        if (typeof container === 'string') {
            container = document.querySelector(container);
        }

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = `
            <div class="error-title">${title}</div>
            <div class="error-details">${message}</div>
            ${retryCallback ? '<button class="retry-button" onclick="this.parentElement.retryCallback()">Thử lại</button>' : ''}
        `;

        if (retryCallback) {
            errorDiv.retryCallback = retryCallback;
        }

        // Remove existing error messages
        const existingErrors = container.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());

        container.insertBefore(errorDiv, container.firstChild);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (errorDiv.parentElement) {
                errorDiv.remove();
            }
        }, 5000);
    }

    /**
     * Create skeleton loading for tables
     */
    showTableSkeleton(tableSelector, rows = 5, cols = 4) {
        const table = document.querySelector(tableSelector);
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        tbody.innerHTML = '';
        
        for (let i = 0; i < rows; i++) {
            const row = document.createElement('tr');
            for (let j = 0; j < cols; j++) {
                const cell = document.createElement('td');
                cell.innerHTML = '<div class="skeleton skeleton-text"></div>';
                row.appendChild(cell);
            }
            tbody.appendChild(row);
        }
    }

    /**
     * Enhanced AJAX with loading states
     */
    ajaxWithLoading(options) {
        const defaults = {
            showGlobalLoading: false,
            loadingMessage: 'Đang tải...',
            errorContainer: null,
            retryCallback: null,
            onStart: null,
            onComplete: null,
            onError: null
        };

        const config = { ...defaults, ...options };

        // Show loading
        if (config.onStart) config.onStart();
        if (config.showGlobalLoading) {
            this.showGlobalLoading(config.loadingMessage);
        }

        // Setup error handling
        const originalError = config.error;
        config.error = (xhr, status, error) => {
            // Hide loading
            if (config.showGlobalLoading) {
                this.hideGlobalLoading();
            }

            // Show error message
            if (config.errorContainer) {
                let errorMessage = 'Đã xảy ra lỗi. Vui lòng thử lại.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMessage = 'Không thể kết nối đến server. Kiểm tra kết nối mạng.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Không tìm thấy tài nguyên yêu cầu.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Lỗi server nội bộ. Vui lòng thử lại sau.';
                }

                this.showError(
                    config.errorContainer,
                    'Lỗi',
                    errorMessage,
                    config.retryCallback
                );
            }

            if (config.onError) config.onError(xhr, status, error);
            if (originalError) originalError(xhr, status, error);
        };

        // Setup success handling
        const originalSuccess = config.success;
        config.success = (data, status, xhr) => {
            // Hide loading
            if (config.showGlobalLoading) {
                this.hideGlobalLoading();
            }

            if (originalSuccess) originalSuccess(data, status, xhr);
        };

        // Setup complete handling
        const originalComplete = config.complete;
        config.complete = (xhr, status) => {
            if (config.onComplete) config.onComplete();
            if (originalComplete) originalComplete(xhr, status);
        };

        return $.ajax(config);
    }

    /**
     * Show toast notification
     */
    showToast(message, type = 'info', duration = 3000) {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#d4edda' : type === 'error' ? '#f8d7da' : '#d1ecf1'};
            color: ${type === 'success' ? '#155724' : type === 'error' ? '#721c24' : '#0c5460'};
            padding: 1rem;
            border-radius: 0.375rem;
            border: 1px solid ${type === 'success' ? '#c3e6cb' : type === 'error' ? '#f5c6cb' : '#bee5eb'};
            z-index: 10001;
            max-width: 300px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            animation: slideInRight 0.3s ease-out;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }
}

// Create global instance
window.LoadingUtils = new LoadingUtils();

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);

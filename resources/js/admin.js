// Admin Panel Optimized JavaScript
import './bootstrap';

// Import required libraries
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

// Admin App Class
class AdminApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
        this.setupAjaxDefaults();
        this.handlePageLoad();
    }

    setupEventListeners() {
        // Sidebar toggle for mobile
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-lte-toggle="sidebar"]')) {
                e.preventDefault();
                this.toggleSidebar();
            }
        });

        // Form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.matches('.ajax-form')) {
                e.preventDefault();
                this.handleAjaxForm(e.target);
            }
        });

        // Delete confirmations
        document.addEventListener('click', (e) => {
            if (e.target.matches('.delete-btn, .delete-btn *')) {
                e.preventDefault();
                const btn = e.target.closest('.delete-btn');
                this.confirmDelete(btn);
            }
        });

        // Auto-hide alerts
        this.autoHideAlerts();
    }

    toggleSidebar() {
        document.body.classList.toggle('sidebar-open');
    }

    initializeComponents() {
        // Initialize tooltips
        this.initTooltips();
        
        // Initialize popovers
        this.initPopovers();
        
        // Initialize Select2 if available
        this.initSelect2();
        
        // Initialize DataTables if available
        this.initDataTables();
        
        // Initialize CKEditor if available
        this.initCKEditor();
    }

    initTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    initPopovers() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    initSelect2() {
        if (typeof $ !== 'undefined' && $.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    }

    initDataTables() {
        if (typeof $ !== 'undefined' && $.fn.DataTable) {
            $('.data-table').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    url: '/js/datatables-vi.json'
                }
            });
        }
    }

    initCKEditor() {
        if (typeof CKEDITOR !== 'undefined') {
            document.querySelectorAll('.ckeditor').forEach(element => {
                CKEDITOR.replace(element.id, {
                    height: 300,
                    filebrowserUploadUrl: '/admin/upload',
                    filebrowserUploadMethod: 'form'
                });
            });
        }
    }

    setupAjaxDefaults() {
        // Setup CSRF token for all AJAX requests
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token && typeof $ !== 'undefined') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token.getAttribute('content')
                }
            });
        }
    }

    handleAjaxForm(form) {
        const formData = new FormData(form);
        const url = form.action;
        const method = form.method || 'POST';

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showAlert('success', data.message || 'Thao tác thành công!');
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                }
            } else {
                this.showAlert('error', data.message || 'Có lỗi xảy ra!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.showAlert('error', 'Có lỗi xảy ra khi xử lý yêu cầu!');
        });
    }

    confirmDelete(btn) {
        const message = btn.dataset.message || 'Bạn có chắc chắn muốn xóa?';
        
        if (confirm(message)) {
            const url = btn.href || btn.dataset.url;
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showAlert('success', data.message || 'Xóa thành công!');
                    // Remove row from table
                    const row = btn.closest('tr');
                    if (row) {
                        row.remove();
                    }
                } else {
                    this.showAlert('error', data.message || 'Có lỗi xảy ra khi xóa!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showAlert('error', 'Có lỗi xảy ra khi xử lý yêu cầu!');
            });
        }
    }

    showAlert(type, message) {
        // Use Toastr if available
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
            return;
        }

        // Fallback to Bootstrap alert
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.app-content') || document.body;
        container.insertAdjacentHTML('afterbegin', alertHtml);
    }

    autoHideAlerts() {
        setTimeout(() => {
            document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    }

    handlePageLoad() {
        // Remove loading class
        document.body.classList.remove('loading');
        
        // Focus first input
        const firstInput = document.querySelector('input:not([type="hidden"]):not([readonly]), textarea:not([readonly]), select:not([readonly])');
        if (firstInput) {
            firstInput.focus();
        }
    }

    // Utility methods
    static formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(num);
    }

    static formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    }

    static formatDate(date) {
        return new Intl.DateTimeFormat('vi-VN').format(new Date(date));
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.AdminApp = new AdminApp();
});

// Export for global access
window.AdminApp = AdminApp;

/**
 * Admin Actions JavaScript Helper
 * Xử lý các thao tác CRUD trong admin panel
 */

// Xóa item với confirmation (API version)
async function deleteItem(id, url = null, message = 'Bạn có chắc chắn muốn xóa?', reloadCallback = null) {
    if (confirm(message)) {
        // Nếu không có URL, tự động tạo API URL từ current path
        if (!url) {
            const currentPath = window.location.pathname;
            // Convert web path to API path
            // /admin/users/index -> /api/v1/admin/users/delete/{id}
            const pathParts = currentPath.split('/');
            if (pathParts.includes('admin')) {
                const module = pathParts[pathParts.length - 2]; // users, roles, etc.
                url = `/api/v1/admin/${module}/delete/${id}`;
            }
        }

        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Xóa thành công!');
                } else {
                    alert(data.message || 'Xóa thành công!');
                }

                // Reload API table if callback provided
                if (reloadCallback && typeof reloadCallback === 'function') {
                    setTimeout(() => {
                        reloadCallback();
                    }, 500);
                } else {
                    // Reload page if no callback
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || 'Có lỗi xảy ra khi xóa!');
                } else {
                    alert(data.message || 'Có lỗi xảy ra khi xóa!');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
            } else {
                alert('Có lỗi xảy ra khi xử lý yêu cầu!');
            }
        }
    }
}

// Toggle status (block/unblock user) - API version
async function toggleStatus(id, currentStatus, url = null, reloadCallback = null) {
    const action = currentStatus ? 'mở khóa' : 'khóa';
    const message = `Bạn có chắc chắn muốn ${action} tài khoản này?`;

    if (confirm(message)) {
        if (!url) {
            // Create API URL for status change
            url = `/api/v1/admin/users/status/${id}`;
        }

        try {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const statusValue = currentStatus ? '0' : '1';

            // Use FormData with method spoofing
            const formData = new FormData();
            formData.append('status', statusValue);
            formData.append('_token', csrfToken);
            formData.append('_method', 'PATCH');

            // Debug log
            console.log('Toggle Status Debug:', {
                url: url,
                currentStatus: currentStatus,
                statusValue: statusValue,
                csrfToken: csrfToken
            });

            const response = await fetch(url, {
                method: 'POST', // Use POST with method spoofing
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                    // Don't set Content-Type for FormData, let browser set it
                }
            });

            const data = await response.json();

            if (data.success) {
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || `${action.charAt(0).toUpperCase() + action.slice(1)} tài khoản thành công!`);
                } else {
                    alert(data.message || `${action.charAt(0).toUpperCase() + action.slice(1)} tài khoản thành công!`);
                }

                // Reload API table if callback provided
                if (reloadCallback && typeof reloadCallback === 'function') {
                    setTimeout(() => {
                        reloadCallback();
                    }, 500);
                } else {
                    // Reload page if no callback
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || `Có lỗi xảy ra khi ${action} tài khoản!`);
                } else {
                    alert(data.message || `Có lỗi xảy ra khi ${action} tài khoản!`);
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
            } else {
                alert('Có lỗi xảy ra khi xử lý yêu cầu!');
            }
        }
    }
}

// AJAX form submission
function submitAjaxForm(formElement, successCallback = null) {
    const formData = new FormData(formElement);

    // Get CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    fetch(formElement.action, {
        method: formElement.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof toastr !== 'undefined') {
                toastr.success(data.message || 'Thành công');
            } else {
                alert(data.message || 'Thành công');
            }

            if (typeof successCallback === 'function') {
                successCallback(data);
            } else {
                // Reload page hoặc redirect
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            }
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error(data.message || 'Có lỗi xảy ra');
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }

            // Hiển thị validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = formElement.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');

                        // Tìm hoặc tạo error message element
                        let errorElement = input.parentNode.querySelector('.invalid-feedback');
                        if (!errorElement) {
                            errorElement = document.createElement('div');
                            errorElement.className = 'invalid-feedback';
                            input.parentNode.appendChild(errorElement);
                        }
                        errorElement.textContent = data.errors[field][0];
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof toastr !== 'undefined') {
            toastr.error('Có lỗi xảy ra khi xử lý yêu cầu');
        } else {
            alert('Có lỗi xảy ra khi xử lý yêu cầu');
        }
    });
}

// Bulk actions
function bulkAction(action, selectedIds, confirmMessage = null) {
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một mục');
        return;
    }

    if (confirmMessage && !confirm(confirmMessage)) {
        return;
    }

    const currentPath = window.location.pathname;
    const url = currentPath.replace('/index', '') + '/bulk-' + action;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = url;
    form.style.display = 'none';

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Selected IDs
    selectedIds.forEach(id => {
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'ids[]';
        idInput.value = id;
        form.appendChild(idInput);
    });

    document.body.appendChild(form);
    form.submit();
}

// Get selected checkboxes
function getSelectedIds(checkboxSelector = '.item-checkbox:checked') {
    const checkboxes = document.querySelectorAll(checkboxSelector);
    return Array.from(checkboxes).map(cb => cb.value);
}

// Select all checkboxes
function toggleSelectAll(selectAllCheckbox, itemCheckboxSelector = '.item-checkbox') {
    const itemCheckboxes = document.querySelectorAll(itemCheckboxSelector);
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    updateBulkActionButtons();
}

// Update bulk action buttons based on selection
function updateBulkActionButtons() {
    const selectedIds = getSelectedIds();
    const bulkActionButtons = document.querySelectorAll('.bulk-action-btn');

    bulkActionButtons.forEach(btn => {
        btn.disabled = selectedIds.length === 0;
    });

    // Update counter
    const counter = document.querySelector('.selected-count');
    if (counter) {
        counter.textContent = selectedIds.length;
    }
}

// Initialize bulk actions
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox
    const selectAllCheckbox = document.querySelector('#select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            toggleSelectAll(this);
        });
    }

    // Individual checkboxes
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox')) {
            updateBulkActionButtons();

            // Update select all checkbox
            const selectAllCheckbox = document.querySelector('#select-all');
            if (selectAllCheckbox) {
                const itemCheckboxes = document.querySelectorAll('.item-checkbox');
                const checkedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
                selectAllCheckbox.checked = itemCheckboxes.length === checkedCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < itemCheckboxes.length;
            }
        }
    });

    // AJAX forms
    document.addEventListener('submit', function(e) {
        if (e.target.classList.contains('ajax-form')) {
            e.preventDefault();
            submitAjaxForm(e.target);
        }
    });
});

// Export functions for global use
window.deleteItem = deleteItem;
window.toggleStatus = toggleStatus;
window.submitAjaxForm = submitAjaxForm;
window.bulkAction = bulkAction;
window.getSelectedIds = getSelectedIds;
window.toggleSelectAll = toggleSelectAll;

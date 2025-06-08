$(document).ready(function () {
    // Khởi tạo Select2
    function initializeSelect2() {
        $('.select2').each(function () {
            const $select = $(this);

            if ($select.hasClass('select2-hidden-accessible')) return;

            const config = {
                placeholder: 'Chọn mục',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap-5',
                minimumInputLength: 0, // Cho phép search ngay từ đầu
                minimumResultsForSearch: 0, // Luôn hiển thị search box
                tags: false, // Không cho phép tạo tag mới
                dropdownAutoWidth: false, // Tắt auto width để tránh overflow
                dropdownParent: $select.closest('.form-group, .mb-3, .col, .container, body'), // Tìm parent container phù hợp
                language: {
                    noResults: function() {
                        return "Không tìm thấy kết quả";
                    },
                    searching: function() {
                        return "Đang tìm kiếm...";
                    }
                }
            };

            const url = $select.data('url');
            if (url) {
                config.ajax = {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        search: params.term || '',
                        limit: 20
                    }),
                    processResults: function(response) {
                        // Handle different response formats
                        let data = response;
                        if (response.success && response.data) {
                            data = response.data;
                        } else if (Array.isArray(response)) {
                            data = response;
                        } else {
                            data = [];
                        }

                        // Lấy các selected options hiện tại
                        const selectedOptions = [];
                        $select.find('option:selected').each(function() {
                            const value = $(this).val();
                            const text = $(this).text();
                            if (value && value !== '') {
                                selectedOptions.push({id: value, text: text});
                            }
                        });

                        // Map data từ server - API đã trả về format chuẩn {id, name}
                        const serverResults = data.map(item => ({
                            id: item.id,
                            text: item.name
                        }));

                        // Merge selected options với server results, tránh duplicate
                        const allResults = [...selectedOptions];
                        serverResults.forEach(item => {
                            if (!allResults.find(existing => existing.id === item.id)) {
                                allResults.push(item);
                            }
                        });

                        return {results: allResults};
                    },
                    cache: true
                };
            }

            try {
                $select.select2(config);

                // Fix dropdown positioning after initialization
                $select.on('select2:open', function() {
                    const dropdown = $('.select2-dropdown');
                    if (dropdown.length) {
                        // Ensure dropdown is positioned correctly
                        dropdown.css({
                            'max-width': '100%',
                            'width': 'auto',
                            'min-width': $select.outerWidth() + 'px'
                        });
                    }
                });
            } catch (error) {
                // Silent fail
            }
        });
    }

    // Khởi tạo Select2
    setTimeout(initializeSelect2, 100);

    // Theo dõi DOM changes
    new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if ($(mutation.addedNodes).find('.select2').length > 0) {
                setTimeout(initializeSelect2, 100);
            }
        });
    }).observe(document.body, {childList: true, subtree: true});

    window.initializeSelect2 = initializeSelect2;
});

// AJAX helper function
function sendAjaxRequest(url, method, data, successCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function (response) {
            if (response.success) {
                toastr.success(response.messages || 'Thành công');
                if (typeof successCallback === 'function') {
                    successCallback(response);
                } else {
                    location.reload();
                }
            } else {
                toastr.error(response.messages || 'Có lỗi xảy ra');
            }
        },
        error: function (xhr) {
            const errorMessage = xhr.responseJSON?.messages || "Lỗi hệ thống!";
            toastr.error(errorMessage);
        }
    });
}

// CKEditor và File upload handler
document.addEventListener("DOMContentLoaded", function () {
    // Khởi tạo CKEditor cho textarea có data-editor="true"
    document.querySelectorAll('[data-editor="true"]').forEach(textarea => {
        if (typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace(textarea.name, {
                toolbar: [
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                    { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize'] }
                ],
                height: 300,
                filebrowserUploadUrl: '/admin/upload-image',
                filebrowserUploadMethod: 'form'
            });
        }
    });

    // File upload handler
    document.querySelectorAll('.upload-field').forEach(input => {
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (!file) return;

            const targetInput = document.getElementById(this.getAttribute('data-target'));
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(this.getAttribute('data-url'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    targetInput.value = data.url;
                    toastr.success('Upload thành công');
                } else {
                    toastr.error('Upload thất bại: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                toastr.error('Có lỗi xảy ra khi upload');
            });
        });
    });
});

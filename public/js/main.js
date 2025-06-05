$(document).ready(function () {
    // Đảm bảo jQuery và Select2 đã load
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        return;
    }

    if (typeof $.fn.select2 === 'undefined') {
        console.error('Select2 is not loaded!');
        return;
    }

    function initializeSelect2() {
        $('.select2').each(function () {
            const selectElement = $(this);

            // Kiểm tra xem đã khởi tạo Select2 chưa
            if (selectElement.hasClass('select2-hidden-accessible')) {
                return; // Đã khởi tạo rồi, bỏ qua
            }

            const url = selectElement.data('url'); // Lấy URL từ data-url
            const field = selectElement.data('field') || 'id'; // Trường lấy giá trị
            const displayField = selectElement.data('display-field') || 'name'; // Trường hiển thị
            const selectedData = selectElement.attr('data-selected'); // Dữ liệu đã chọn
            const isMultiple = selectElement.prop('multiple'); // Kiểm tra select multiple hay không
            let selectedValues = selectedData ? JSON.parse(selectedData) : (isMultiple ? [] : null);

            // Cấu hình Select2
            let select2Config = {
                placeholder: 'Chọn mục',
                allowClear: true,
                width: '100%',
                theme: 'default' // Sử dụng theme default thay vì bootstrap-5
            };

            // Nếu có URL thì sử dụng AJAX
            if (url) {
                select2Config.ajax = {
                    url: url,
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {term: params.term || ''};
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(function (item) {
                                return {id: item[field], text: item[displayField]};
                            })
                        };
                    },
                    cache: true,
                    beforeSend: function() {
                        if (window.LoadingUtils) {
                            window.LoadingUtils.setSelect2Loading(selectElement, true);
                        }
                    },
                    complete: function() {
                        if (window.LoadingUtils) {
                            window.LoadingUtils.setSelect2Loading(selectElement, false);
                        }
                    }
                };
            }

            // Khởi tạo Select2
            try {
                selectElement.select2(select2Config);
            } catch (error) {
                return;
            }

            // Nếu có dữ liệu đã chọn, tạo options cho selected values trước
            if (selectedValues) {
                if (isMultiple && Array.isArray(selectedValues)) {
                    selectedValues.forEach(value => {
                        // Tạo option với value, sẽ được update text sau khi load data
                        let option = new Option(value, value, true, true);
                        selectElement.append(option);
                    });
                } else if (!isMultiple && selectedValues) {
                    let option = new Option(selectedValues, selectedValues, true, true);
                    selectElement.append(option);
                }
                selectElement.trigger('change');

                // Sau đó load data để update text cho selected options
                if (url && window.LoadingUtils) {
                    window.LoadingUtils.ajaxWithLoading({
                        url: url,
                        dataType: 'json',
                        data: { term: '' },
                        success: function (data) {
                            // Update text cho selected options
                            selectElement.find('option:selected').each(function() {
                                let optionValue = $(this).val();
                                let item = data.find(item => item[field] == optionValue);
                                if (item) {
                                    $(this).text(item[displayField]);
                                }
                            });

                            selectElement.trigger('change');
                        }
                    });
                }
            }
        });
    }

    // Khởi tạo Select2 ban đầu
    setTimeout(function() {
        initializeSelect2();
    }, 100);

    // Sử dụng MutationObserver để theo dõi sự thay đổi trong DOM
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            // Kiểm tra xem có phần tử mới với class select2 được thêm vào không
            if ($(mutation.addedNodes).find('.select2').length > 0) {
                setTimeout(function() {
                    initializeSelect2();
                }, 100);
            }
        });
    });

    // Cấu hình MutationObserver
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Expose function globally for manual initialization
    window.initializeSelect2 = initializeSelect2;
});

function sendAjaxRequest(url, method, data, successCallback, options = {}) {
    const config = {
        url: url,
        method: method,
        data: data,
        showGlobalLoading: options.showLoading || false,
        loadingMessage: options.loadingMessage || 'Đang xử lý...',
        success: function (response) {
            if (response.success) {
                if (window.LoadingUtils) {
                    window.LoadingUtils.showToast(response.messages || 'Thành công', 'success');
                } else {
                    toastr.success(response.messages || 'Thành công');
                }
                if (typeof successCallback === 'function') {
                    successCallback(response);
                } else {
                    location.reload(); // Tự động tải lại trang nếu không có callback
                }
            } else {
                if (window.LoadingUtils) {
                    window.LoadingUtils.showToast(response.messages || 'Có lỗi xảy ra', 'error');
                } else {
                    toastr.error(response.messages || 'Có lỗi xảy ra');
                }
            }
        },
        error: function (xhr) {
            let errorMessage = "Lỗi hệ thống!";
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                errorMessage = xhr.responseJSON.messages;
            }

            if (window.LoadingUtils) {
                window.LoadingUtils.showToast(errorMessage, 'error');
            } else {
                toastr.error(errorMessage);
            }
        }
    };

    if (window.LoadingUtils) {
        return window.LoadingUtils.ajaxWithLoading(config);
    } else {
        return $.ajax(config);
    }
}

function handleFormSubmit(event) {
    event.preventDefault();

    const form = event.target;
    const url = form.action;
    const method = form.method;
    const formData = $(form).serialize();

    sendAjaxRequest(url, method, formData, function (response) {
        //form.reset(); // Reset form sau khi thêm thành công
    });
}

// Lắng nghe sự kiện submit cho tất cả các form
//$(document).on('submit', 'form', handleFormSubmit);


// Kích hoạt CKEditor cho các input có data-editor="true"
document.addEventListener("DOMContentLoaded", function () {
    // Khởi tạo CKEditor cho tất cả textarea có data-editor="true"
    document.querySelectorAll('[data-editor="true"]').forEach((textarea) => {
        if (typeof CKEDITOR !== "undefined") {
            CKEDITOR.replace(textarea.name, {
                extraPlugins: 'image2,uploadimage', // Bật plugin ảnh
                removePlugins: 'easyimage, cloudservices',
                fileTools_requestHeaders: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                toolbar: [
                    { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'Undo', 'Redo'] },
                    { name: 'editing', items: ['Find', 'Replace', 'SelectAll'] },
                    { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
                    { name: 'paragraph', items: ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote'] },
                    { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                    { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
                    { name: 'colors', items: ['TextColor', 'BGColor'] },
                    { name: 'tools', items: ['Maximize'] }
                ],
                imageUploadUrl: '', // Không cần API
            });
        } else {
            console.error("CKEDITOR is not loaded!");
        }
    });







    // Xử lý upload ảnh và cập nhật URL vào input tương ứng
    document.querySelectorAll('.upload-field').forEach((input) => {
        input.addEventListener('change', function (event) {
            let file = event.target.files[0]; // Lấy file đầu tiên
            if (!file) return; // Nếu không chọn file, thoát luôn, không chạy tiếp

            let targetInput = document.getElementById(this.getAttribute('data-target'));
            let formData = new FormData();
            let uploadUrl = this.getAttribute('data-url'); // Lấy URL từ data-attribute
            formData.append('file', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            fetch(uploadUrl, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        targetInput.value = data.url; // Cập nhật đường dẫn file vào input
                    } else {
                        alert('Upload thất bại: ' + data.message);
                    }
                })
                .catch(error => console.error('Lỗi:', error));
        });
    });

});

// tinymce.init({
//     selector: "textarea[data-editor='true']", // Hoặc "#content"
//     plugins: "lists link image table code",
//     toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | outdent indent | link image",
// });
//
// document.addEventListener("DOMContentLoaded", function() {
//     document.querySelectorAll('.upload-field').forEach(input => {
//         input.addEventListener('change', function(event) {
//             let file = event.target.files[0];
//             if (!file) return;
//
//             let targetInput = document.getElementById(input.dataset.target);
//             let formData = new FormData();
//             formData.append('file', file);
//
//             fetch(input.dataset.url, {
//                 method: 'POST',
//                 body: formData,
//                 headers: {
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
//                 }
//             })
//                 .then(response => response.json())
//                 .then(data => {
//                     if (data.success) {
//                         targetInput.value = data.url; // Cập nhật đường dẫn vào input hidden
//                     } else {
//                         alert('Tải file thất bại!');
//                     }
//                 })
//                 .catch(error => {
//                     console.error('Lỗi upload:', error);
//                     alert('Lỗi khi tải file!');
//                 });
//         });
//     });
// });



$(document).ready(function () {
    $('.select2').each(function () {
        const selectElement = $(this);
        const url = selectElement.data('url'); // Lấy URL từ data-url
        const field = selectElement.data('field') || 'id'; // Trường lấy giá trị
        const displayField = selectElement.data('display-field') || 'title'; // Trường hiển thị
        const selectedData = selectElement.attr('data-selected'); // Dữ liệu đã chọn
        const isMultiple = selectElement.prop('multiple'); // Kiểm tra select multiple hay không
        let selectedValues = selectedData ? JSON.parse(selectedData) : (isMultiple ? [] : null);

        // Khởi tạo Select2
        selectElement.select2({
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {term: params.term};
                },
                processResults: function (data) {
                    return {
                        results: data.map(function (item) {
                            return {id: item[field], text: item[displayField]};
                        })
                    };
                },
                cache: true
            },
            placeholder: 'Chọn mục',
            allowClear: true
        });

        // Nếu có dữ liệu đã chọn, thêm vào Select2
        if (selectedValues) {
            $.ajax({
                url: url,
                dataType: 'json',
                success: function (data) {
                    if (isMultiple) {
                        let selectedOptions = selectedValues.map(value => {
                            let item = data.find(item => item[field] == value);
                            let text = item ? item[displayField] : value; // Nếu không tìm thấy thì dùng chính giá trị
                            return new Option(text, value, true, true);
                        });
                        selectElement.append(selectedOptions).trigger('change');
                    } else {
                        let item = data.find(item => item[field] == selectedValues);
                        let text = item ? item[displayField] : selectedValues;
                        let option = new Option(text, selectedValues, true, true);
                        selectElement.append(option).trigger('change');
                    }
                }
            });
        }
    });
});

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
                    location.reload(); // Tự động tải lại trang nếu không có callback
                }
            } else {
                toastr.error(response.messages || 'Có lỗi xảy ra');
            }
        },
        error: function (xhr) {
            let errorMessage = "Lỗi hệ thống!";
            if (xhr.responseJSON && xhr.responseJSON.messages) {
                errorMessage = xhr.responseJSON.messages;
            }
            toastr.error(errorMessage);
        }
    });
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



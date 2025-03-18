function sendAjaxRequest(url, method, data, successCallback) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: function(response) {
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
        error: function(xhr) {
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

    sendAjaxRequest(url, method, formData, function(response) {
        //form.reset(); // Reset form sau khi thêm thành công
    });
}

// Lắng nghe sự kiện submit cho tất cả các form
//$(document).on('submit', 'form', handleFormSubmit);


$(document).ready(function() {
    $('.select2').each(function() {
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
                data: function(params) {
                    return { term: params.term };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(item) {
                            return { id: item[field], text: item[displayField] };
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
                success: function(data) {
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

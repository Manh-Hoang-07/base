<?php

namespace App\Http\Requests\Admin\Declarations\BookCopies;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Hàm check validate
     * @return string[]
     */
    public function rules(): array
    {
        $bookCopyId = $this->route('id'); // Lấy ID từ route khi cập nhật

        return [
            'book_id' => 'required|exists:books,id',
            'copy_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('book_copies', 'copy_number')->where('book_id', $this->book_id)->ignore($bookCopyId),
            ],
            'status' => ['required', Rule::in(['available', 'borrowed', 'lost'])],
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'book_id.required' => 'Vui lòng chọn sách.',
            'book_id.exists' => 'Sách không tồn tại.',
            'copy_number.required' => 'Số thứ tự bản sao không được để trống.',
            'copy_number.integer' => 'Số thứ tự bản sao phải là số nguyên.',
            'copy_number.min' => 'Số thứ tự bản sao phải lớn hơn 0.',
            'copy_number.unique' => 'Số thứ tự bản sao đã tồn tại cho cuốn sách này.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}

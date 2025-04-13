<?php

namespace App\Http\Requests\Admin\Books\BookBorrowTickets;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        return [
            'user_id' => 'required|exists:users,id',
            'borrowed_at' => 'required|date',
            'due_at' => 'required|date|after_or_equal:borrowed_at',
            'books' => 'required|array|min:1',
            'books.*.book_id' => 'required|exists:books,id',
            'books.*.quantity' => 'required|integer|min:1',
            'books.*.note' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:255',
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Vui lòng chọn người mượn.',
            'user_id.exists' => 'Người mượn không hợp lệ.',

            'borrowed_at.required' => 'Vui lòng chọn ngày mượn.',
            'borrowed_at.date' => 'Ngày mượn không đúng định dạng.',

            'due_at.required' => 'Vui lòng chọn hạn trả.',
            'due_at.date' => 'Hạn trả không đúng định dạng.',
            'due_at.after_or_equal' => 'Hạn trả phải lớn hơn hoặc bằng ngày mượn.',

            'books.required' => 'Vui lòng chọn ít nhất một sách để mượn.',
            'books.array' => 'Danh sách sách không hợp lệ.',
            'books.min' => 'Vui lòng chọn ít nhất một sách để mượn.',

            'books.*.book_id.required' => 'Vui lòng chọn sách.',
            'books.*.book_id.exists' => 'Sách đã chọn không tồn tại.',

            'books.*.quantity.required' => 'Vui lòng nhập số lượng sách.',
            'books.*.quantity.integer' => 'Số lượng sách phải là số nguyên.',
            'books.*.quantity.min' => 'Số lượng sách tối thiểu là 1.',

            'books.*.note.string' => 'Ghi chú sách phải là chuỗi.',
            'books.*.note.max' => 'Ghi chú sách không được vượt quá 255 ký tự.',

            'note.string' => 'Ghi chú phiếu mượn phải là chuỗi.',
            'note.max' => 'Ghi chú phiếu mượn không được vượt quá 255 ký tự.',
        ];
    }
}

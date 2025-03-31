<?php

namespace App\Http\Requests\Admin\Declarations\Books;

use App\Enums\BasicStatus;
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
        $bookId = $this->route('id'); // Lấy ID từ route khi cập nhật

        return [
            'series_id' => 'required|exists:series,id',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('books', 'code')->ignore($bookId),
            ],
            'name' => 'required|string|max:255',
            'volume' => 'nullable|integer|min:1',
            'isbn' => [
                'required',
                'string',
                'max:20',
                Rule::unique('books', 'isbn')->ignore($bookId),
            ],
            'published_at' => 'nullable|date',
            'publisher_id' => 'required|exists:publishers,id',
            'language' => 'nullable|string|max:50',
            'page_count' => 'nullable|integer|min:1',
            'summary' => 'nullable|string',
//            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => ['required', Rule::enum(BasicStatus::class)],
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'series_id.required' => 'Vui lòng chọn series cho sách.',
            'series_id.exists' => 'Series không tồn tại.',
            'code.required' => 'Mã sách không được để trống.',
            'code.unique' => 'Mã sách đã tồn tại.',
            'name.required' => 'Tiêu đề sách không được để trống.',
            'name.max' => 'Tiêu đề sách không được vượt quá 255 ký tự.',
            'volume.integer' => 'Số tập phải là số nguyên.',
            'volume.min' => 'Số tập phải lớn hơn 0.',
            'isbn.required' => 'Mã ISBN không được để trống.',
            'isbn.unique' => 'Mã ISBN đã tồn tại.',
            'published_at.date' => 'Ngày xuất bản phải là định dạng ngày hợp lệ.',
            'publisher_id.required' => 'Vui lòng chọn nhà xuất bản.',
            'publisher_id.exists' => 'Nhà xuất bản không tồn tại.',
            'language.max' => 'Ngôn ngữ không được vượt quá 50 ký tự.',
            'page_count.integer' => 'Số trang phải là số nguyên.',
            'page_count.min' => 'Số trang phải lớn hơn 0.',
            'summary.string' => 'Mô tả sách phải là chuỗi ký tự.',
            'image.image' => 'Ảnh bìa phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Ảnh bìa phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Ảnh bìa không được vượt quá 2MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}

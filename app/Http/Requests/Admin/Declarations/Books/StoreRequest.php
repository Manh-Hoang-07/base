<?php

namespace App\Http\Requests\Admin\Declarations\Books;

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
            'series_id' => 'required|exists:series,id',
            'code' => 'required|string|max:50|unique:books,code',
            'title' => 'required|string|max:255',
            'volume' => 'nullable|integer|min:1',
            'isbn' => 'required|string|max:20|unique:books,isbn',
            'published_at' => 'nullable|date',
            'publisher_id' => 'required|exists:publishers,id',
            'language' => 'nullable|string|max:100',
            'page_count' => 'nullable|integer|min:1',
            'summary' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'series_id.required' => 'Series không được để trống.',
            'series_id.exists' => 'Series không tồn tại.',
            'code.required' => 'Mã sách không được để trống.',
            'code.unique' => 'Mã sách đã tồn tại.',
            'title.required' => 'Tiêu đề không được để trống.',
            'isbn.required' => 'ISBN không được để trống.',
            'isbn.unique' => 'ISBN đã tồn tại.',
            'published_at.date' => 'Ngày xuất bản không hợp lệ.',
            'publisher_id.required' => 'Nhà xuất bản không được để trống.',
            'publisher_id.exists' => 'Nhà xuất bản không tồn tại.',
            'page_count.integer' => 'Số trang phải là số nguyên.',
            'page_count.min' => 'Số trang phải lớn hơn 0.',
            'cover_image.image' => 'Ảnh bìa phải là hình ảnh.',
            'cover_image.mimes' => 'Ảnh bìa phải có định dạng jpeg, png, jpg hoặc gif.',
            'cover_image.max' => 'Ảnh bìa không được vượt quá 2MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ];
    }
}

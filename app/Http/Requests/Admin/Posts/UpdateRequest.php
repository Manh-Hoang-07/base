<?php

namespace App\Http\Requests\Admin\Posts;

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
        return [
            'name' => 'required|string|max:255',
            'content' => 'required|string',
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
            'name.required' => 'Tiêu đề bài viết không được để trống.',
            'name.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'content.required' => 'Nội dung bài viết không được để trống.',
            'image.image' => 'Ảnh phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Ảnh phải có định dạng jpeg, png, jpg hoặc gif.',
            'image.max' => 'Ảnh không được vượt quá 2MB.',
            'status.required' => 'Trạng thái bài viết không được để trống.',
            'status.in' => 'Trạng thái bài viết không hợp lệ.',
        ];
    }
}

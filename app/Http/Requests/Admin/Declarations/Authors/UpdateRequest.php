<?php

namespace App\Http\Requests\Admin\Declarations\Authors;

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
        $authorId = $this->route('id');

        return [
            'name' => 'required|string|max:255',
            'pen_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('authors', 'email')->ignore($authorId),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('authors', 'phone')->ignore($authorId),
            ],
            'nationality' => 'nullable|string|max:100',
            'biography' => 'nullable|string',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'death_date' => 'nullable|date|after:birth_date',
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên không được để trống.',
            'pen_name.required' => 'Bút danh không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã tồn tại.',
            'phone.required' => 'Số điện thoại không được để trống.',
            'phone.unique' => 'Số điện thoại đã tồn tại.',
            'birth_date.before_or_equal' => 'Ngày sinh không thể ở tương lai.',
            'death_date.after' => 'Ngày mất phải sau ngày sinh.',
        ];
    }
}

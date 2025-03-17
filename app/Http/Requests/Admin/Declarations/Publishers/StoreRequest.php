<?php

namespace App\Http\Requests\Admin\Declarations\Publishers;

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
            'code' => 'required|string|unique:publishers,code|max:100',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'established_at' => 'nullable|date',
            'status' => 'boolean',
        ];
    }

    /**
     * Hàm trả ra thông báo validate
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Mã không được để trống.',
            'code.unique' => 'Mã đã tồn tại.',
            'name.required' => 'Tên không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'website.url' => 'Website không hợp lệ.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}

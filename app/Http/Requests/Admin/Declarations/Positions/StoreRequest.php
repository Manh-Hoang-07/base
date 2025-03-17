<?php

namespace App\Http\Requests\Admin\Declarations\Positions;

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
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:positions,code|max:100',
            'description' => 'nullable|string',
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
            'name.required' => 'Tên không được để trống.',
            'code.required' => 'Mã không được để trống.',
            'code.unique' => 'Mã đã tồn tại.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}

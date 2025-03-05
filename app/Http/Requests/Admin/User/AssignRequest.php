<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'roles' => 'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'roles.required' => 'Vai trò không được để trống.',
            'roles.array' => 'Định dạng không hợp lệ',
        ];
    }
}

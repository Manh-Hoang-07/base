<?php

namespace App\Http\Requests\Admin\Declarations\Areas;

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
        $areaId = $this->route('id'); // Lấy ID từ route khi cập nhật

        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('areas', 'code')->ignore($areaId),
            ],
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
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
            'name.required' => 'Tên khu vực không được để trống.',
            'code.required' => 'Mã khu vực không được để trống.',
            'code.unique' => 'Mã khu vực đã tồn tại.',
            'type.required' => 'Loại khu vực không được để trống.',
            'location.required' => 'Vị trí không được để trống.',
            'capacity.integer' => 'Sức chứa phải là số nguyên.',
            'capacity.min' => 'Sức chứa không được nhỏ hơn 0.',
        ];
    }
}

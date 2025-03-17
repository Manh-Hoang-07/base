<?php

namespace App\Http\Requests\Admin\Declarations\Shelves;

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
        $shelfId = $this->route('id');

        return [
            'area_id' => 'required|exists:areas,id',
            'code' => [
                'required',
                'string',
                Rule::unique('shelves', 'code')->ignore($shelfId),
                'max:100',
            ],
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
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
            'area_id.required' => 'Khu vực không được để trống.',
            'area_id.exists' => 'Khu vực không hợp lệ.',
            'code.required' => 'Mã kệ không được để trống.',
            'code.unique' => 'Mã kệ đã tồn tại.',
            'name.required' => 'Tên kệ không được để trống.',
            'capacity.required' => 'Sức chứa không được để trống.',
            'capacity.integer' => 'Sức chứa phải là số nguyên.',
            'capacity.min' => 'Sức chứa phải lớn hơn 0.',
            'status.boolean' => 'Trạng thái không hợp lệ.',
        ];
    }
}

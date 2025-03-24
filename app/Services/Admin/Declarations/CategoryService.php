<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class CategoryService
{
    public function __construct(CategoryRepository $categoryRepository) {
        $this->repository = $categoryRepository;
    }

    /**
     * Tạo mới danh mục
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới danh mục thất bại'
        ];
        $keys = ['name', 'code', 'description', 'slug', 'parent_id', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->repository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới danh mục thành công';
        }
        return $return;
    }

    /**
     * Cập nhật danh mục
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật danh mục thất bại'
        ];
        $keys = ['name', 'code', 'description', 'slug', 'parent_id', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->repository->findById($id))
            && $this->repository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật danh mục thành công';
        }
        return $return;
    }
}

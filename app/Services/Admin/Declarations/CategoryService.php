<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\CategoryRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class CategoryService extends BaseService
{
    public function __construct(CategoryRepository $categoryRepository) {
        $this->repository = $categoryRepository;
    }

    protected function getRepository(): CategoryRepository
    {
        return $this->repository;
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
            && $this->getRepository()->create($insertData)
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
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật danh mục thành công';
        }
        return $return;
    }
}

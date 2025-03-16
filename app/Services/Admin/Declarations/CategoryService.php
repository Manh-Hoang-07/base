<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class CategoryService
{
    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Lấy danh sách tất cả danh mục
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->categoryRepository->getList($filters, $options);
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
            && $this->categoryRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới danh mục thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin danh mục theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->categoryRepository->findById($id);
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
            && ($role = $this->categoryRepository->findById($id))
            && $this->categoryRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật danh mục thành công';
        }
        return $return;
    }

    /**
     * Xóa danh mục
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa danh mục thất bại'
        ];
        if (($position = $this->categoryRepository->findById($id))
            && ($this->categoryRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa danh mục thành công';
        }
        return $return;
    }
}

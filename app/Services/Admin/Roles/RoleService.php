<?php

namespace App\Services\Admin\Roles;

use App\Repositories\Admin\Roles\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class RoleService
{
    protected RoleRepository $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Hàm lấy danh sách vai trò
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->roleRepository->getList($filters, $options);
    }

    /**
     * Lấy thông tin vai trò theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        $options['relations'] = ['permissions'];
        return $this->roleRepository->findById($id, $options);
    }

    /**
     * Xử lý tạo vai trò
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới quyền thất bại'
        ];
        $keys = ['title', 'name', 'permissions'];
        if (($insertData = DataTable::getAllowData($keys, $data))
            && $this->roleRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới quyền thành công';
        }
        return $return;
    }

    /**
     * Xử lý cập nhật vai trò
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật vai trò thất bại'
        ];
        $keys = ['title', 'name', 'permissions'];
        $updateData = DataTable::getAllowData($keys, $data);
        if (!empty($updateData)
            && ($role = $this->roleRepository->findById($id))
            && $this->roleRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật vai trò thành công';
        }
        return $return;
    }

    /**
     * Xử lý xóa vai trò
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật vai trò thất bại'
        ];
        if (($role = $this->roleRepository->findById($id))
            && $this->roleRepository->delete($role)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật vai trò thành công';
        }
        return $return;
    }
}

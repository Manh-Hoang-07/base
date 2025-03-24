<?php

namespace App\Services\Admin\Permissions;

use App\Repositories\Admin\Permissions\PermissionRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PermissionService extends BaseService
{
    public function __construct(PermissionRepository $permissionRepository) {
        $this->repository = $permissionRepository;
    }

    /**
     * Tạo mới quyền
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới quyền thất bại'
        ];
        $keys = ['title', 'name', 'guard_name', 'parent_id', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->repository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới quyền thành công';
        }
        return $return;
    }

    /**
     * Cập nhật quyền
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật quyền thất bại'
        ];
        $keys = ['title', 'name', 'guard_name', 'parent_id', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($permission = $this->repository->findById($id))
        ) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể cập nhật quyền hệ thống';
            } elseif ($this->repository->update($permission, $updateData)) {
                $return['success'] = true;
                $return['messages'] = 'Cập nhật quyền thành công';
            }
        }
        return $return;
    }

    /**
     * Xóa quyền
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa quyền thất bại'
        ];
        if ($permission = $this->repository->findById($id)) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể xóa quyền hệ thống';
            } elseif ($this->repository->delete($permission)) {
                $return['success'] = true;
                $return['messages'] = 'Xóa quyền thành công';
            }
        }
        return $return;
    }
}

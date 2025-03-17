<?php

namespace App\Services\Admin\Permissions;

use App\Repositories\Admin\Permissions\PermissionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PermissionService
{
    protected PermissionRepository $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository) {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Lấy danh sách tất cả quyền
     * @param array $filters
     * @param array $options
     * @return Collection
     */
    public function getAll(array $filters = [], array $options = []): Collection
    {
        return $this->permissionRepository->getAll($filters, $options);
    }

    /**
     * Lấy danh sách tất cả quyền
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->permissionRepository->getList($filters, $options);
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
            && $this->permissionRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới quyền thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin quyền theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->permissionRepository->findById($id);
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
            && ($permission = $this->permissionRepository->findById($id))
        ) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể cập nhật quyền hệ thống';
            } elseif ($this->permissionRepository->update($permission, $updateData)) {
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
        if ($permission = $this->permissionRepository->findById($id)) {
            if (!empty($permission->is_default)) {
                $return['success'] = false;
                $return['messages'] = 'Không thể xóa quyền hệ thống';
            } elseif ($this->permissionRepository->delete($permission)) {
                $return['success'] = true;
                $return['messages'] = 'Xóa quyền thành công';
            }
        }
        return $return;
    }
}

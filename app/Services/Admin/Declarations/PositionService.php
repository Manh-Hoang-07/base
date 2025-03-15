<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\PositionRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PositionService
{
    protected PositionRepository $positionRepository;

    public function __construct(PositionRepository $positionRepository) {
        $this->positionRepository = $positionRepository;
    }

    /**
     * Lấy danh sách tất cả chức vụ
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->positionRepository->getList($filters, $options);
    }

    /**
     * Tạo mới chức vụ
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới chức vụ thất bại'
        ];
        $keys = ['name', 'code', 'description'];
        if (($insertData = DataTable::getAllowData($keys, $data))
            && $this->positionRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới chức vụ thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin chức vụ theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->positionRepository->findById($id);
    }

    /**
     * Cập nhật chức vụ
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật chức vụ thất bại'
        ];
        $keys = ['name', 'code', 'description'];
        $updateData = DataTable::getAllowData($keys, $data);
        if (!empty($updateData)
            && ($role = $this->positionRepository->findById($id))
            && $this->positionRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật chức vụ thành công';
        }
        return $return;
    }

    /**
     * Xóa chức vụ
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa chức vụ thất bại'
        ];
        if (($position = $this->positionRepository->findById($id))
            && ($this->positionRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa chức vụ thành công';
        }
        return $return;
    }
}

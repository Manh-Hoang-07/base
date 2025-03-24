<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\PositionRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PositionService extends BaseService
{
    public function __construct(PositionRepository $positionRepository) {
        $this->repository = $positionRepository;
    }

    protected function getRepository(): PositionRepository
    {
        return $this->repository;
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
        $keys = ['name', 'code', 'description', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới chức vụ thành công';
        }
        return $return;
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
        $keys = ['name', 'code', 'description', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật chức vụ thành công';
        }
        return $return;
    }
}

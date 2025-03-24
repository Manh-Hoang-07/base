<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\SeriesRepository;
use App\Services\BaseService;
use lib\DataTable;

class SeriesService extends BaseService
{
    public function __construct(SeriesRepository $seriesRepository) {
        $this->repository = $seriesRepository;
    }

    protected function getRepository(): SeriesRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới series
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới series thất bại'
        ];
        $keys = ['name', 'code', 'description', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới series thành công';
        }
        return $return;
    }

    /**
     * Cập nhật series
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật series thất bại'
        ];
        $keys = ['name', 'code', 'description', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật series thành công';
        }
        return $return;
    }
}

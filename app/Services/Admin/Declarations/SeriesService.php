<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\SeriesRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class SeriesService extends BaseService
{
    public function __construct(SeriesRepository $seriesRepository) {
        $this->repository = $seriesRepository;
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
            && $this->repository->create($insertData)
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
            && ($role = $this->repository->findById($id))
            && $this->repository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật series thành công';
        }
        return $return;
    }
}

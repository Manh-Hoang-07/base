<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\ShelfRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class ShelfService extends BaseService
{
    public function __construct(ShelfRepository $shelfRepository) {
        $this->repository = $shelfRepository;
    }

    protected function getRepository(): ShelfRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới kệ sách
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới kệ sách thất bại'
        ];
        $keys = ['area_id', 'code', 'name', 'capacity', 'description', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới kệ sách thành công';
        }
        return $return;
    }

    /**
     * Cập nhật kệ sách
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật kệ sách thất bại'
        ];
        $keys = ['area_id', 'code', 'name', 'capacity', 'description', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật kệ sách thành công';
        }
        return $return;
    }
}

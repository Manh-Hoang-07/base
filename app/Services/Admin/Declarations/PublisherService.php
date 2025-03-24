<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\PositionRepository;
use App\Repositories\Admin\Declarations\PublisherRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PublisherService extends BaseService
{
    public function __construct(PublisherRepository $publisherRepository) {
        $this->repository = $publisherRepository;
    }

    protected function getRepository(): PublisherRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới nhà xuất bản
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới nhà xuất bản thất bại'
        ];
        $keys = ['code', 'name', 'email', 'phone', 'address', 'website', 'established_at', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->repository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới nhà xuất bản thành công';
        }
        return $return;
    }

    /**
     * Cập nhật nhà xuất bản
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật nhà xuất bản thất bại'
        ];
        $keys = ['code', 'name', 'email', 'phone', 'address', 'website', 'established_at', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->repository->findById($id))
            && $this->repository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật nhà xuất bản thành công';
        }
        return $return;
    }
}

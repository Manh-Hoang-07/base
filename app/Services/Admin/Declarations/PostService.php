<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\PostRepository;
use App\Services\BaseService;
use lib\DataTable;

class PostService extends BaseService
{
    public function __construct(PostRepository $postRepository) {
        $this->repository = $postRepository;
    }

    protected function getRepository(): PostRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới khu vực
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới khu vực thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới khu vực thành công';
        }
        return $return;
    }

    /**
     * Cập nhật khu vực
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật khu vực thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật khu vực thành công';
        }
        return $return;
    }
}

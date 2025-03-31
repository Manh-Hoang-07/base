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
     * Tạo mới bài đăng
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới bài đăng thất bại'
        ];
        $keys = ['name', 'image', 'content', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới bài đăng thành công';
        }
        return $return;
    }

    /**
     * Cập nhật bài đăng
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật bài đăng thất bại'
        ];
        $keys = ['name', 'image', 'content', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật bài đăng thành công';
        }
        return $return;
    }
}

<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\BookRepository;
use App\Services\BaseService;
use lib\DataTable;

class BookService extends BaseService
{
    public function __construct(BookRepository $bookRepository) {
        $this->repository = $bookRepository;
    }

    protected function getRepository(): BookRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới sách
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới sách thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới sách thành công';
        }
        return $return;
    }

    /**
     * Cập nhật sách
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật sách thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật sách thành công';
        }
        return $return;
    }
}

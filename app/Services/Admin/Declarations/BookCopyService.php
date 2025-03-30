<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\BookCopyRepository;
use App\Services\BaseService;
use lib\DataTable;

class BookCopyService extends BaseService
{
    public function __construct(BookCopyRepository $bookCopyRepository) {
        $this->repository = $bookCopyRepository;
    }

    protected function getRepository(): BookCopyRepository
    {
        return $this->repository;
    }

    /**
     * Tạo mới bản sao sách
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới bản sao sách thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới bản sao sách thành công';
        }
        return $return;
    }

    /**
     * Cập nhật bản sao sách
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật bản sao sách thất bại'
        ];
        $keys = ['name', 'code', 'location', 'type', 'description', 'capacity', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật bản sao sách thành công';
        }
        return $return;
    }
}

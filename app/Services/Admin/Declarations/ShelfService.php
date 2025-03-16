<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\ShelfRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class ShelfService
{
    protected ShelfRepository $shelfRepository;

    public function __construct(ShelfRepository $shelfRepository) {
        $this->shelfRepository = $shelfRepository;
    }

    /**
     * Lấy danh sách tất cả kệ sách
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->shelfRepository->getList($filters, $options);
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
            && $this->shelfRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới kệ sách thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin kệ sách theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->shelfRepository->findById($id);
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
            && ($role = $this->shelfRepository->findById($id))
            && $this->shelfRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật kệ sách thành công';
        }
        return $return;
    }

    /**
     * Xóa kệ sách
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa kệ sách thất bại'
        ];
        if (($position = $this->shelfRepository->findById($id))
            && ($this->shelfRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa kệ sách thành công';
        }
        return $return;
    }
}

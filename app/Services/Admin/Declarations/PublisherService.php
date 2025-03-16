<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\PositionRepository;
use App\Repositories\Admin\Declarations\PublisherRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class PublisherService
{
    protected PublisherRepository $publisherRepository;

    public function __construct(PublisherRepository $publisherRepository) {
        $this->publisherRepository = $publisherRepository;
    }

    /**
     * Lấy danh sách tất cả nhà xuất bản
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->publisherRepository->getList($filters, $options);
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
            && $this->publisherRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới nhà xuất bản thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin nhà xuất bản theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->publisherRepository->findById($id);
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
            && ($role = $this->publisherRepository->findById($id))
            && $this->publisherRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật nhà xuất bản thành công';
        }
        return $return;
    }

    /**
     * Xóa nhà xuất bản
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa nhà xuất bản thất bại'
        ];
        if (($position = $this->publisherRepository->findById($id))
            && ($this->publisherRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa nhà xuất bản thành công';
        }
        return $return;
    }
}

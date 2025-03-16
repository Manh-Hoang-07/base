<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\AreaRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class AreaService
{
    protected AreaRepository $areaRepository;

    public function __construct(AreaRepository $areaRepository) {
        $this->areaRepository = $areaRepository;
    }

    /**
     * Lấy danh sách tất cả khu vực
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->areaRepository->getList($filters, $options);
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
        $keys = ['name', 'code', 'location', 'description', 'capacity', 'status'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->areaRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới khu vực thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin khu vực theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->areaRepository->findById($id);
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
        $keys = ['name', 'code', 'location', 'description', 'capacity', 'status'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->areaRepository->findById($id))
            && $this->areaRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật khu vực thành công';
        }
        return $return;
    }

    /**
     * Xóa khu vực
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa khu vực thất bại'
        ];
        if (($position = $this->areaRepository->findById($id))
            && ($this->areaRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa khu vực thành công';
        }
        return $return;
    }
}

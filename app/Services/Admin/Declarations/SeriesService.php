<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\SeriesRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class SeriesService
{
    protected SeriesRepository $seriesRepository;

    public function __construct(SeriesRepository $seriesRepository) {
        $this->seriesRepository = $seriesRepository;
    }

    /**
     * Lấy danh sách tất cả series
     * @param array $filters
     * @param array $options
     * @return Collection
     */
    public function getAll(array $filters = [], array $options = []): Collection
    {
        return $this->seriesRepository->getAll($filters, $options);
    }

    /**
     * Lấy danh sách tất cả series
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->seriesRepository->getList($filters, $options);
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
            && $this->seriesRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới series thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin series theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->seriesRepository->findById($id);
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
            && ($role = $this->seriesRepository->findById($id))
            && $this->seriesRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật series thành công';
        }
        return $return;
    }

    /**
     * Xóa series
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa series thất bại'
        ];
        if (($position = $this->seriesRepository->findById($id))
            && ($this->seriesRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa series thành công';
        }
        return $return;
    }
}

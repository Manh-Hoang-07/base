<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\AuthorRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class AuthorService
{
    protected AuthorRepository $authorRepository;

    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }

    /**
     * Lấy danh sách tất cả tác giả
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        return $this->authorRepository->getList($filters, $options);
    }

    /**
     * Tạo mới chức vụ
     * @param array $data
     * @return array
     */
    public function create(array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Thêm mới tác giả thất bại'
        ];
        $keys = ['name', 'pen_name', 'email', 'phone', 'nationality',
            'biography', 'birth_date', 'death_date'];
        if (($insertData = DataTable::getChangeData($data, $keys))
            && $this->authorRepository->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới tác giả thành công';
        }
        return $return;
    }

    /**
     * Lấy thông tin chức vụ theo ID
     * @param $id
     * @return Model|null
     */
    public function findById($id): ?Model
    {
        return $this->authorRepository->findById($id);
    }

    /**
     * Cập nhật chức vụ
     * @param $id
     * @param array $data
     * @return array
     */
    public function update($id, array $data): array
    {
        $return = [
            'success' => false,
            'messages' => 'Cập nhật tác giả thất bại'
        ];
        $keys = ['name', 'pen_name', 'email', 'phone', 'nationality',
            'biography', 'birth_date', 'death_date'];
        $updateData = DataTable::getChangeData($data, $keys);
        if (!empty($updateData)
            && ($role = $this->authorRepository->findById($id))
            && $this->authorRepository->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật tác giả thành công';
        }
        return $return;
    }

    /**
     * Xóa chức vụ
     * @param $id
     * @return array
     */
    public function delete($id): array
    {
        $return = [
            'success' => false,
            'messages' => 'Xóa tác giả thất bại'
        ];
        if (($position = $this->authorRepository->findById($id))
            && ($this->authorRepository->delete($position))
        ) {
            $return['success'] = true;
            $return['messages'] = 'Xóa tác giả thành công';
        }
        return $return;
    }
}

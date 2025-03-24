<?php

namespace App\Services\Admin\Declarations;

use App\Repositories\Admin\Declarations\AuthorRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use lib\DataTable;

class AuthorService extends BaseService
{
    public function __construct(AuthorRepository $authorRepository) {
        $this->repository = $authorRepository;
    }

    protected function getRepository(): AuthorRepository
    {
        return $this->repository;
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
            && $this->getRepository()->create($insertData)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Thêm mới tác giả thành công';
        }
        return $return;
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
            && ($role = $this->getRepository()->findById($id))
            && $this->getRepository()->update($role, $data)
        ) {
            $return['success'] = true;
            $return['messages'] = 'Cập nhật tác giả thành công';
        }
        return $return;
    }
}

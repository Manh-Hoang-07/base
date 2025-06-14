<?php

namespace App\Repositories\Admin\Users;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Override getList để luôn load relationship roles
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        // Thêm relationship roles vào options
        $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);

        return parent::getList($filters, $options);
    }

    /**
     * Override getAll để luôn load relationship roles
     */
    public function getAll(array $filters = [], array $options = []): Collection
    {
        // Thêm relationship roles vào options
        $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);

        return parent::getAll($filters, $options);
    }

    /**
     * Override findById để luôn load relationship roles
     */
    public function findById(int $id, array $options = []): ?Model
    {
        // Thêm relationship roles vào options
        $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);

        return parent::findById($id, $options);
    }

    /**
     * Override applyFilters để xử lý search đúng cho bảng users
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } elseif ($column === 'search') {
                    // Search chỉ trong email cho users (vì bảng users chỉ có email)
                    $query->where('email', 'like', '%' . $value . '%');
                } elseif (is_string($value)) {
                    $query->where($column, 'like', '%' . $value . '%');
                } else {
                    $query->where($column, $value);
                }
            } elseif (is_null($value)) {
                $query->whereNull($column);
            }
        }
    }
}

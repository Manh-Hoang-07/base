<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

abstract class BaseRepository
{
    protected Model $model;

    /**
     * Lấy danh sách có bộ lọc, phân trang & sắp xếp
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = [], array $options = []): LengthAwarePaginator
    {
        $relations = $options['relations'] ?? [];
        $perPage = $options['perPage'] ?? 10;
        $sortBy = $options['sortBy'] ?? 'id';
        $sortOrder = $options['sortOrder'] ?? 'asc';
        $columns = $options['columns'] ?? ['*'];
        $query = $this->model->select($columns);
        // Áp dụng quan hệ
        $this->applyRelations($query, $relations);
        // Áp dụng bộ lọc
        $this->applyFilters($query, $filters);
        // Sắp xếp và phân trang
        return $query->orderBy($sortBy, $sortOrder)->paginate($perPage);
    }

    /**
     * Thêm điều kiện bộ lọc vào truy vấn.
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } else {
                    $query->where($column, 'like', '%' . $value . '%');
                }
            }
        }
    }

    /**
     * Thêm quan hệ vào truy vấn.
     */
    protected function applyRelations($query, array $relations): void
    {
        if (!empty($relations)) {
            if ($query instanceof Model) {
                $query = $query->newQuery(); // Chuyển model thành Builder
            }
            $query->with($relations);
        }
    }


    /**
     * Tìm một bản ghi theo ID
     * @param int $id
     * @param array $options
     * @return Model|null
     */
    public function findById(int $id, array $options = []): ?Model
    {
        $relations = $options['relations'] ?? [];
        $query = $this->model->find($id);
        $this->applyRelations($query, $relations);
        return $query;
    }

    /**
     * Tìm một bản ghi theo ID, nếu không có thì báo lỗi
     * @param int $id
     * @return Model
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Tạo mới bản ghi
     * @param array $data
     * @return Model|null
     */
    public function create(array $data): ?Model
    {
        try {
            $create = $this->model->create($data);
            if ($create && $this->model->where('id', $create->id)->exists()) {
                return $create;
            } else {
                return null;
            }
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Cập nhật bản ghi
     * @param Model $model
     * @param array $data
     * @return bool
     */
    public function update(Model $model, array $data): bool
    {
        try {
            if ($model->update($data)) {
                return true;
            }
            return false;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Xóa bản ghi
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model): bool
    {
        try {
            if ($model->delete()) {
                return true;
            }
            return false;
        } catch (Throwable $e) {
            return false;
        }
    }
}

<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

abstract class BaseRepository
{
    protected Model $model;

    /**
     * Lấy tất cả danh sách
     * @param array $filters
     * @param array $options
     * @return Collection
     */
    public function getAll(array $filters = [], array $options = []): Collection
    {
        $query = $this->applyQueryDefaults($filters, $options);
        return $query->get();
    }

    /**
     * Lấy danh sách có bộ lọc, phân trang & sắp xếp
     * @param array $filters
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function getList(array $filters = [], array $options = []): LengthAwarePaginator
    {
        $query = $this->applyQueryDefaults($filters, $options);
        $perPage = $options['perPage'] ?? 10;
        // Phân trang
        return $query->paginate($perPage);
    }

    /**
     * Hàm chung để xử lý các phần tử cơ bản của query
     * @param array $filters
     * @param array $options
     * @return Builder
     */
    private function applyQueryDefaults(array $filters, array $options): Builder
    {
        $relations = $options['relations'] ?? [];
        $columns = $options['columns'] ?? ['*'];
        $sortBy = $options['sortBy'] ?? 'id';
        $sortOrder = $options['sortOrder'] ?? 'asc';
        $query = $this->model->select($columns);
        // Áp dụng quan hệ
        $this->applyRelations($query, $relations);
        // Áp dụng bộ lọc
        $this->applyFilters($query, $filters);
        // Sắp xếp
        return $query->orderBy($sortBy, $sortOrder);
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
            dd($data, $create);
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

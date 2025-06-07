<?php

namespace App\Http\Controllers\Api\Admin\Categories;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Categories\StoreRequest;
use App\Http\Requests\Admin\Categories\UpdateRequest;
use App\Services\Admin\Categories\CategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    public function getService(): CategoryService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách danh mục (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request->all());
        $options = $this->getOptions($request->all());

        $perPage = $request->get('per_page', 10);
        $options['per_page'] = min($perPage, 100);

        if ($request->has('search') && !empty($request->get('search'))) {
            $filters['search'] = $request->get('search');
        }

        $data = $this->getService()->getList($filters, $options);

        if (method_exists($data, 'toArray')) {
            return response()->json($data->toArray());
        }

        return response()->json($data);
    }

    /**
     * Lấy thông tin chi tiết danh mục (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->getService()->findById($id);
            return $this->successResponse($category, 'Lấy thông tin danh mục thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy danh mục', null, 404);
        }
    }

    /**
     * Tạo danh mục mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Tạo danh mục thành công', 
                201
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Tạo danh mục thất bại');
    }

    /**
     * Cập nhật danh mục (API)
     * @param UpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $result = $this->getService()->update($id, $request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Cập nhật danh mục thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Cập nhật danh mục thất bại');
    }

    /**
     * Xóa danh mục (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);
        
        if ($result['success']) {
            return $this->successResponse(
                null, 
                $result['message'] ?? 'Xóa danh mục thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Xóa danh mục thất bại');
    }
}

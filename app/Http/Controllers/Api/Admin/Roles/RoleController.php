<?php

namespace App\Http\Controllers\Api\Admin\Roles;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Roles\StoreRequest;
use App\Http\Requests\Admin\Roles\UpdateRequest;
use App\Services\Admin\Roles\RoleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(RoleService $roleService)
    {
        $this->service = $roleService;
    }

    public function getService(): RoleService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách vai trò (API)
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
     * Lấy thông tin chi tiết vai trò (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->getService()->findById($id);
            return $this->successResponse($role, 'Lấy thông tin vai trò thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy vai trò', null, 404);
        }
    }

    /**
     * Tạo vai trò mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Tạo vai trò thành công', 
                201
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Tạo vai trò thất bại');
    }

    /**
     * Cập nhật vai trò (API)
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
                $result['message'] ?? 'Cập nhật vai trò thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Cập nhật vai trò thất bại');
    }

    /**
     * Xóa vai trò (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);
        
        if ($result['success']) {
            return $this->successResponse(
                null, 
                $result['message'] ?? 'Xóa vai trò thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Xóa vai trò thất bại');
    }

    /**
     * Autocomplete vai trò (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);
        
        try {
            $roles = $this->getService()->autocomplete($search, $limit);
            return $this->successResponse($roles, 'Lấy danh sách autocomplete thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Lỗi khi lấy danh sách autocomplete: ' . $e->getMessage());
        }
    }
}

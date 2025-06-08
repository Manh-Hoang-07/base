<?php

namespace App\Http\Controllers\Api\Admin\Permissions;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Permissions\StoreRequest;
use App\Http\Requests\Admin\Permissions\UpdateRequest;
use App\Services\Admin\Permissions\PermissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(PermissionService $permissionService)
    {
        $this->service = $permissionService;
    }

    public function getService(): PermissionService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách quyền (API)
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
     * Lấy thông tin chi tiết quyền (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $permission = $this->getService()->findById($id);
            return $this->successResponse($permission, 'Lấy thông tin quyền thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy quyền', null, 404);
        }
    }

    /**
     * Tạo quyền mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());

        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null,
                $result['message'] ?? 'Tạo quyền thành công',
                201
            );
        }

        return $this->errorResponse($result['message'] ?? 'Tạo quyền thất bại');
    }

    /**
     * Cập nhật quyền (API)
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
                $result['message'] ?? 'Cập nhật quyền thành công'
            );
        }

        return $this->errorResponse($result['message'] ?? 'Cập nhật quyền thất bại');
    }

    /**
     * Xóa quyền (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);

        if ($result['success']) {
            return $this->successResponse(
                null,
                $result['message'] ?? 'Xóa quyền thành công'
            );
        }

        return $this->errorResponse($result['message'] ?? 'Xóa quyền thất bại');
    }

    /**
     * Autocomplete quyền (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $search = $request->get('search') ?? '';
        $limit = $request->get('limit') ?? 10;

        try {
            $permissions = $this->getService()->autocomplete($search, 'name', $limit);
            return $this->successResponse($permissions, 'Lấy danh sách autocomplete thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Lỗi khi lấy danh sách autocomplete: ' . $e->getMessage());
        }
    }
}

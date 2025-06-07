<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\Users\AssignRequest;
use App\Http\Requests\Admin\Users\Users\StoreRequest;
use App\Http\Requests\Admin\Users\Users\UpdateRequest;
use App\Services\Admin\Users\UserService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function getService(): UserService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách tài khoản (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request->all());
        $options = $this->getOptions($request->all());

        // Xử lý phân trang
        $perPage = $request->get('per_page', 10);
        $options['per_page'] = min($perPage, 100);

        // Xử lý tìm kiếm
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
     * Lấy thông tin chi tiết tài khoản (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->getService()->findById($id);
            return $this->successResponse($user, 'Lấy thông tin tài khoản thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy tài khoản', null, 404);
        }
    }

    /**
     * Tạo tài khoản mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());

        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null,
                $result['message'] ?? 'Tạo tài khoản thành công',
                201
            );
        }

        return $this->errorResponse($result['message'] ?? 'Tạo tài khoản thất bại');
    }

    /**
     * Cập nhật tài khoản (API)
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
                $result['message'] ?? 'Cập nhật tài khoản thành công'
            );
        }

        return $this->errorResponse($result['message'] ?? 'Cập nhật tài khoản thất bại');
    }

    /**
     * Xóa tài khoản (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);

        if ($result['success']) {
            return $this->successResponse(
                null,
                $result['message'] ?? 'Xóa tài khoản thành công'
            );
        }

        return $this->errorResponse($result['message'] ?? 'Xóa tài khoản thất bại');
    }

    /**
     * Thay đổi trạng thái tài khoản (API)
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function changeStatus(Request $request, int $id): JsonResponse
    {


        $request->validate([
            'status' => 'required|integer|in:0,1',
        ]);

        $result = $this->getService()->changeStatus($id, (int)$request->status);

        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null,
                $result['message'] ?? 'Thay đổi trạng thái tài khoản thành công'
            );
        }

        return $this->errorResponse($result['message'] ?? 'Thay đổi trạng thái tài khoản thất bại');
    }

    /**
     * Gán vai trò cho tài khoản (API)
     * @param AssignRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function assignRoles(AssignRequest $request, int $id): JsonResponse
    {
        try {
            $this->getService()->assignRoles($id, $request->roles ?? []);
            return $this->successResponse(null, 'Cập nhật vai trò thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Cập nhật vai trò thất bại: ' . $e->getMessage());
        }
    }

    /**
     * Autocomplete tài khoản (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);

        try {
            $users = $this->getService()->autocomplete($search, $limit);
            return $this->successResponse($users, 'Lấy danh sách autocomplete thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Lỗi khi lấy danh sách autocomplete: ' . $e->getMessage());
        }
    }
}

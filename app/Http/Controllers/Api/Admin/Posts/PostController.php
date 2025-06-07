<?php

namespace App\Http\Controllers\Api\Admin\Posts;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Posts\StoreRequest;
use App\Http\Requests\Admin\Posts\UpdateRequest;
use App\Services\Admin\Posts\PostService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(PostService $postService)
    {
        $this->service = $postService;
    }

    public function getService(): PostService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách bài đăng (API)
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
     * Lấy thông tin chi tiết bài đăng (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $post = $this->getService()->findById($id);
            return $this->successResponse($post, 'Lấy thông tin bài đăng thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy bài đăng', null, 404);
        }
    }

    /**
     * Tạo bài đăng mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Tạo bài đăng thành công', 
                201
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Tạo bài đăng thất bại');
    }

    /**
     * Cập nhật bài đăng (API)
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
                $result['message'] ?? 'Cập nhật bài đăng thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Cập nhật bài đăng thất bại');
    }

    /**
     * Xóa bài đăng (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);
        
        if ($result['success']) {
            return $this->successResponse(
                null, 
                $result['message'] ?? 'Xóa bài đăng thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Xóa bài đăng thất bại');
    }
}

<?php

namespace App\Http\Controllers\Api\Home\Posts;

use App\Http\Controllers\BaseController;
use App\Services\Home\Posts\PostService;
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
     * Lấy danh sách bài đăng công khai (API)
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

        // Chỉ lấy bài đăng công khai
        $filters['status'] = 1;

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
            
            // Kiểm tra bài đăng có công khai không
            if (!$post || $post->status != 1) {
                return $this->errorResponse('Bài đăng không tồn tại hoặc không công khai', null, 404);
            }
            
            return $this->successResponse($post, 'Lấy thông tin bài đăng thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy bài đăng', null, 404);
        }
    }
}

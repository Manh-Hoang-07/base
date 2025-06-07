<?php

namespace App\Http\Controllers\Api\Admin\Series;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Series\StoreRequest;
use App\Http\Requests\Admin\Series\UpdateRequest;
use App\Services\Admin\Series\SeriesService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SeriesController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(SeriesService $seriesService)
    {
        $this->service = $seriesService;
    }

    public function getService(): SeriesService
    {
        return $this->service;
    }

    /**
     * Lấy danh sách series (API)
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
     * Lấy thông tin chi tiết series (API)
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $series = $this->getService()->findById($id);
            return $this->successResponse($series, 'Lấy thông tin series thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy series', null, 404);
        }
    }

    /**
     * Tạo series mới (API)
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $result = $this->getService()->create($request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Tạo series thành công', 
                201
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Tạo series thất bại');
    }

    /**
     * Cập nhật series (API)
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
                $result['message'] ?? 'Cập nhật series thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Cập nhật series thất bại');
    }

    /**
     * Xóa series (API)
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $result = $this->getService()->delete($id);
        
        if ($result['success']) {
            return $this->successResponse(
                null, 
                $result['message'] ?? 'Xóa series thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Xóa series thất bại');
    }

    /**
     * Autocomplete series (API)
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10);
        
        try {
            $series = $this->getService()->autocomplete($search, $limit);
            return $this->successResponse($series, 'Lấy danh sách autocomplete thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Lỗi khi lấy danh sách autocomplete: ' . $e->getMessage());
        }
    }
}

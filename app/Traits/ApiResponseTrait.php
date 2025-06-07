<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

trait ApiResponseTrait
{
    /**
     * Trả về response JSON cho danh sách có phân trang
     *
     * @param Request $request
     * @param string $viewName - Tên view để render nếu không phải AJAX request
     * @param array $additionalData - Dữ liệu bổ sung cho view
     * @return JsonResponse|\Illuminate\Contracts\View\View
     */
    protected function apiOrViewResponse(Request $request, string $viewName, array $additionalData = [])
    {
        // Nếu là AJAX request hoặc có header Accept: application/json
        if ($request->wantsJson() || $request->ajax() || $request->has('api')) {
            return $this->getApiData($request);
        }

        // Nếu không phải, trả về view bình thường
        return view($viewName, $additionalData);
    }

    /**
     * Lấy dữ liệu API với phân trang và tìm kiếm
     *
     * @param Request $request
     * @return JsonResponse
     */
    protected function getApiData(Request $request): JsonResponse
    {
        $filters = $this->getFilters($request->all());
        $options = $this->getOptions($request->all());

        // Xử lý phân trang
        $perPage = $request->get('per_page', 10);
        $options['per_page'] = min($perPage, 100); // Giới hạn tối đa 100 records/page

        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->get('search'))) {
            $filters['search'] = $request->get('search');
        }

        $data = $this->getService()->getList($filters, $options);

        // Đảm bảo pagination data được format đúng
        if (method_exists($data, 'toArray')) {
            return response()->json($data->toArray());
        }

        return response()->json($data);
    }

    /**
     * Trả về response JSON thành công
     *
     * @param mixed $data
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Thành công', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Trả về response JSON lỗi
     *
     * @param string $message
     * @param mixed $errors
     * @param int $status
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Có lỗi xảy ra', $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }

    /**
     * Xử lý response cho các action CRUD
     *
     * @param Request $request
     * @param array $result
     * @param string $redirectRoute
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function handleCrudResponse(Request $request, array $result, string $redirectRoute)
    {
        if ($request->wantsJson() || $request->ajax()) {
            if ($result['success']) {
                return $this->successResponse(null, $result['message'] ?? 'Thành công');
            } else {
                return $this->errorResponse($result['message'] ?? 'Có lỗi xảy ra');
            }
        }

        // Redirect cho form submission thông thường
        if ($result['success']) {
            return redirect()->route($redirectRoute)->with('success', $result['message'] ?? 'Thành công');
        } else {
            return back()->with('error', $result['message'] ?? 'Có lỗi xảy ra')->withInput();
        }
    }

    /**
     * Kiểm tra xem request có phải là API request không
     *
     * @param Request $request
     * @return bool
     */
    protected function isApiRequest(Request $request): bool
    {
        return $request->wantsJson() ||
               $request->ajax() ||
               $request->has('api') ||
               str_contains($request->path(), '/api/');
    }

    /**
     * Format dữ liệu cho API response
     *
     * @param mixed $data
     * @param array $meta
     * @return array
     */
    protected function formatApiData($data, array $meta = []): array
    {
        $response = [
            'data' => $data
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return $response;
    }

    /**
     * Xử lý validation errors cho API
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return JsonResponse
     */
    protected function validationErrorResponse($validator): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Dữ liệu không hợp lệ',
            'errors' => $validator->errors()
        ], 422);
    }
}

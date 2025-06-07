<?php

namespace App\Http\Controllers\Api\Admin\Users;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\Profiles\UpdateRequest;
use App\Services\Admin\Users\ProfileService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class ProfileController extends BaseController
{
    use ApiResponseTrait;

    public function __construct(ProfileService $profileService)
    {
        $this->service = $profileService;
    }

    public function getService(): ProfileService
    {
        return $this->service;
    }

    /**
     * Lấy thông tin hồ sơ (API)
     * @param int $user_id
     * @return JsonResponse
     */
    public function show(int $user_id): JsonResponse
    {
        try {
            $profile = $this->getService()->findByUserId($user_id);
            return $this->successResponse($profile, 'Lấy thông tin hồ sơ thành công');
        } catch (\Exception $e) {
            return $this->errorResponse('Không tìm thấy hồ sơ', null, 404);
        }
    }

    /**
     * Cập nhật hồ sơ (API)
     * @param UpdateRequest $request
     * @param int $user_id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $user_id): JsonResponse
    {
        $result = $this->getService()->update($user_id, $request->all());
        
        if ($result['success']) {
            return $this->successResponse(
                $result['data'] ?? null, 
                $result['message'] ?? 'Cập nhật hồ sơ thành công'
            );
        }
        
        return $this->errorResponse($result['message'] ?? 'Cập nhật hồ sơ thất bại');
    }
}

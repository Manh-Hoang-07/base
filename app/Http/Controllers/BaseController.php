<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected BaseService $service;

    public function getService(): BaseService
    {
        return $this->service;
    }

    /**
     * Hàm dùng chung cho autocomplete
     * @param Request $request
     * @return JsonResponse
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $term = $request->input('term');
        return $this->getService()->autocomplete($term ?? '');
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class BaseController extends Controller
{
    protected BaseService $service;

    public function getService(): BaseService
    {
        return $this->service;
    }

    /**
     * Hàm lấy ra điều kiện lọc
     * @param Request $request
     * @param array $allowKeys
     * @return array
     */
    protected function getFilters(Request $request, array $allowKeys = []): array
    {
        $validColumns = $this->getService()->getColumns();
        $filters = DataTable::getFiltersData($request->all(), $allowKeys);
        // Lọc chỉ giữ lại những cột hợp lệ
        return collect($filters)->only($validColumns)->toArray();
    }

    /**
     * Hàm lấy ra các options lấy dữ liệu
     * @param Request $request
     * @return array
     */
    protected function getOptions(Request $request): array
    {
        return DataTable::getOptionsData($request->all());
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

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
     * @param array $filters
     * @param array $allowKeys
     * @return array
     */
    protected function getFilters(array $filters, array $allowKeys = []): array
    {
        $validColumns = $this->getService()->getColumns();
        $filters = DataTable::getFiltersData($filters, $allowKeys);
        // Lọc chỉ giữ lại những cột hợp lệ
        return collect($filters)->only($validColumns)->toArray();
    }

    /**
     * Hàm lấy ra các options lấy dữ liệu
     * @param array $options
     * @return array
     */
    protected function getOptions(array $options): array
    {
        return DataTable::getOptionsData($options);
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

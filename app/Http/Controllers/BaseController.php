<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    /**
     * Hàm dùng chung cho autocomplete
     *
     * @param Request $request
     * @param string|Model $modelClass
     * @param string $column
     * @param int $limit
     * @return JsonResponse
     */
    protected function baseAutocomplete(Request $request, string|Model $modelClass = '', string $column = 'title', int $limit = 10): JsonResponse
    {
        $term = $request->input('term');
        // Kiểm tra nếu class tồn tại và là một Model hợp lệ
        if (is_string($modelClass)
            && (!class_exists($modelClass) || !is_subclass_of($modelClass, 'Illuminate\Database\Eloquent\Model'))
        ) {
            return response()->json(['error' => 'Invalid model class'], 400);
        }
        $results = $modelClass::query()
            ->where($column, 'like', '%' . $term . '%')
            ->select('id', $column, 'name')
            ->limit($limit)
            ->get();
        return response()->json($results);
    }
}

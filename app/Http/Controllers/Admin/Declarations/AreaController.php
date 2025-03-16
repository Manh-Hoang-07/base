<?php

namespace App\Http\Controllers\Admin\Declarations;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use lib\DataTable;

class AreaController extends Controller
{
    /**
     * Hiển thị danh sách chức vụ
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $positions = $this->positionService->getList($filters, $options);
        return view('admin.declarations.positions.index', compact('positions'));
    }
}

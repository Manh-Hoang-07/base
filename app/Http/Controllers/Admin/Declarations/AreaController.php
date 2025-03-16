<?php

namespace App\Http\Controllers\Admin\Declarations;

use App\Http\Controllers\Controller;
use App\Services\Admin\Declarations\AreaService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class AreaController extends Controller
{
    protected AreaService $areaService;

    public function __construct(AreaService $areaService)
    {
        $this->areaService = $areaService;
    }

    /**
     * Hiển thị danh sách khu vực
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $areas = $this->areaService->getList($filters, $options);
        return view('admin.declarations.areas.index', compact('areas'));
    }

    /**
     * Hiển thị form tạo khu vực
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.areas.create');
    }

    /**
     * Xử lý tạo khu vực
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:areas,code',
            'description' => 'max:255',
        ]);
        $return = $this->areaService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.areas.index')
                ->with('success', $return['message'] ?? 'Thêm mới khu vực thành công.');
        }
        return redirect()->route('admin.declarations.areas.index')
            ->with('fail', $return['message'] ?? 'Thêm mới khu vực thất bại.');
    }

    /**
     * Hiển thị form sửa khu vực
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $area = $this->areaService->findById($id);
        return view('admin.declarations.areas.edit', compact('area'));
    }

    /**
     * Xử lý cập nhật khu vực
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:areas,code',
            'description' => 'max:255',
        ]);
        $return = $this->areaService->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.areas.index')
                ->with('success', $return['message'] ?? 'Cập nhật khu vực thành công.');
        }
        return redirect()->route('admin.declarations.areas.index')
            ->with('fail', $return['message'] ?? 'Cập nhật khu vực thất bại.');
    }

    /**
     * Xóa khu vực
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->areaService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.areas.index')
                ->with('success', $return['message'] ?? 'Xóa khu vực thành công.');
        }
        return redirect()->route('admin.declarations.areas.index')
            ->with('fail', $return['message'] ?? 'Xóa khu vực thất bại.');
    }
}

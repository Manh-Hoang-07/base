<?php

namespace App\Http\Controllers\Admin\Declarations\Areas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Areas\StoreRequest;
use App\Http\Requests\Admin\Declarations\Areas\UpdateRequest;
use App\Services\Admin\Declarations\AreaService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class AreaController extends BaseController
{
    public function __construct(AreaService $areaService)
    {
        $this->service = $areaService;
    }

    public function getService(): AreaService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách khu vực
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $this->getFilters($request, ['name', 'code']);
        $options = $this->getOptions($request);
        $areas = $this->getService()->getList($filters, $options);
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
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
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
        $area = $this->getService()->findById($id);
        return view('admin.declarations.areas.edit', compact('area'));
    }

    /**
     * Xử lý cập nhật khu vực
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->all());
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
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.areas.index')
                ->with('success', $return['message'] ?? 'Xóa khu vực thành công.');
        }
        return redirect()->route('admin.declarations.areas.index')
            ->with('fail', $return['message'] ?? 'Xóa khu vực thất bại.');
    }
}

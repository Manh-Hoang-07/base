<?php

namespace App\Http\Controllers\Admin\Declarations\shelves;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Declarations\AreaService;
use App\Services\Admin\Declarations\ShelfService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class ShelfController extends BaseController
{
    protected ShelfService $shelfService;
    protected AreaService $areaService;

    public function __construct(ShelfService $shelfService, AreaService $areaService)
    {
        $this->shelfService = $shelfService;
        $this->areaService = $areaService;
    }

    /**
     * Hiển thị danh sách kệ sách
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = DataTable::getFiltersData($request->all(), ['name', 'code']);
        $options = DataTable::getOptionsData($request->all());
        $shelves = $this->shelfService->getList($filters, $options);
        return view('admin.declarations.shelves.index', compact('shelves'));
    }

    /**
     * Hiển thị form tạo kệ sách
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $areas = $this->areaService->getAll();
        return view('admin.declarations.shelves.create', compact('areas'));
    }

    /**
     * Xử lý tạo kệ sách
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
        ]);
        $return = $this->shelfService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.shelves.index')
                ->with('success', $return['message'] ?? 'Thêm mới kệ sách thành công.');
        }
        return redirect()->route('admin.declarations.shelves.index')
            ->with('fail', $return['message'] ?? 'Thêm mới kệ sách thất bại.');
    }

    /**
     * Hiển thị form sửa kệ sách
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $shelf = $this->shelfService->findById($id);
        $areas = $this->areaService->getAll();
        return view('admin.declarations.shelves.edit', compact('shelf', 'areas'));
    }

    /**
     * Xử lý cập nhật kệ sách
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required'
        ]);
        $return = $this->shelfService->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.shelves.index')
                ->with('success', $return['message'] ?? 'Cập nhật kệ sách thành công.');
        }
        return redirect()->route('admin.declarations.shelves.index')
            ->with('fail', $return['message'] ?? 'Cập nhật kệ sách thất bại.');
    }

    /**
     * Xóa kệ sách
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->shelfService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.shelves.index')
                ->with('success', $return['message'] ?? 'Xóa kệ sách thành công.');
        }
        return redirect()->route('admin.declarations.shelves.index')
            ->with('fail', $return['message'] ?? 'Xóa kệ sách thất bại.');
    }
}

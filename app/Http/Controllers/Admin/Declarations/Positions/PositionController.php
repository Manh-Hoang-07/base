<?php

namespace App\Http\Controllers\Admin\Declarations\Positions;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Declarations\Positions\StoreRequest;
use App\Http\Requests\Admin\Declarations\Positions\UpdateRequest;
use App\Services\Admin\Declarations\PositionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use lib\DataTable;

class PositionController extends BaseController
{
    public function __construct(PositionService $positionService)
    {
        $this->service = $positionService;
    }

    public function getService(): PositionService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách chức vụ
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $this->getFilters($request, ['name', 'code']);
        $options = $this->getOptions($request);
        $positions = $this->getService()->getList($filters, $options);
        return view('admin.declarations.positions.index', compact('positions'));
    }

    /**
     * Hiển thị form tạo chức vụ
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.declarations.positions.create');
    }

    /**
     * Xử lý tạo chức vụ
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->getService()->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.positions.index')
                ->with('success', $return['message'] ?? 'Thêm mới chức vụ thành công.');
        }
        return redirect()->route('admin.declarations.positions.index')
            ->with('fail', $return['message'] ?? 'Thêm mới chức vụ thất bại.');
    }

    /**
     * Hiển thị form sửa chức vụ
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $permission = $this->getService()->findById($id);
        return view('admin.declarations.positions.edit', compact('permission'));
    }

    /**
     * Xử lý cập nhật chức vụ
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->getService()->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.positions.index')
                ->with('success', $return['message'] ?? 'Cập nhật chức vụ thành công.');
        }
        return redirect()->route('admin.declarations.positions.index')
            ->with('fail', $return['message'] ?? 'Cập nhật chức vụ thất bại.');
    }

    /**
     * Xóa chức vụ
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->getService()->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.positions.index')
                ->with('success', $return['message'] ?? 'Xóa chức vụ thành công.');
        }
        return redirect()->route('admin.declarations.positions.index')
            ->with('fail', $return['message'] ?? 'Xóa chức vụ thất bại.');
    }
}

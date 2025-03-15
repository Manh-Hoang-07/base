<?php

namespace App\Http\Controllers\Admin\Declarations;

use App\Http\Controllers\Controller;
use App\Services\Admin\Declarations\PositionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected PositionService $positionService;

    public function __construct(PositionService $positionService)
    {
        $this->positionService = $positionService;
    }

    /**
     * Hiển thị danh sách chức vụ
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $request->only(['name', 'title']);
        $options['sortBy'] = $request->get('sortBy', 'id');
        $options['sortOrder'] = $request->get('sortOrder', 'asc');
        $positions = $this->positionService->getList($filters, $options);
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
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:permissions,name'
        ]);
        $return = $this->positionService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.positions.index')
                ->with('success', $return['message'] ?? 'Tạo chức vụ thành công.');
        }
        return redirect()->route('admin.declarations.positions.index')
            ->with('fail', $return['message'] ?? 'Tạo chức vụ thất bại.');
    }

    /**
     * Hiển thị form sửa chức vụ
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $permission = $this->positionService->findById($id);
        return view('admin.declarations.positions.edit', compact('permission'));
    }

    /**
     * Xử lý cập nhật chức vụ
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:permissions,name,' . $id,
        ]);
        $return = $this->positionService->update($id, $request->all());
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
        $return = $this->positionService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.declarations.positions.index')
                ->with('success', $return['message'] ?? 'Xóa chức vụ thành công.');
        }
        return redirect()->route('admin.declarations.positions.index')
            ->with('fail', $return['message'] ?? 'Xóa chức vụ thất bại.');
    }
}

<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Services\Admin\Permission\PermissionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Hiển thị danh sách quyền
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $request->only(['name', 'title']);
        $options['sortBy'] = $request->get('sortBy', 'id');
        $options['sortOrder'] = $request->get('sortOrder', 'asc');
        $permissions = $this->permissionService->getAll($filters, $options);
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Hiển thị form tạo quyền
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.permissions.create');
    }

    /**
     * Xử lý tạo quyền
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:permissions,name'
        ]);
        $return = $this->permissionService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.permissions.index')
                ->with('success', $return['message'] ?? 'Tạo quyền thành công.');
        }
        return redirect()->route('admin.permissions.index')
            ->with('fail', $return['message'] ?? 'Tạo quyền thất bại.');
    }

    /**
     * Hiển thị form sửa quyền
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $permission = $this->permissionService->findById($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Xử lý cập nhật quyền
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
        $return = $this->permissionService->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.permissions.index')
                ->with('success', $return['message'] ?? 'Cập nhật quyền thành công.');
        }
        return redirect()->route('admin.permissions.index')
            ->with('fail', $return['message'] ?? 'Cập nhật quyền thất bại.');
    }

    /**
     * Xóa quyền
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->permissionService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.permissions.index')
                ->with('success', $return['message'] ?? 'Xóa quyền thành công.');
        }
        return redirect()->route('admin.permissions.index')
            ->with('fail', $return['message'] ?? 'Xóa quyền thất bại.');
    }
}

<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use App\Services\Admin\Permission\PermissionService;
use App\Services\Admin\Role\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $roleService;
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->roleService = $roleService;
        $this->permissionService = $permissionService;
    }

    /**
     * Hiển thị danh sách vai trò
     * @param Request $request
     * @return View|Application|Factory
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $request->only(['name', 'title', 'role']);
        $options['sortBy'] = $request->get('sortBy', 'id');
        $options['sortOrder'] = $request->get('sortOrder', 'asc');
        $options['relations'] = ['permissions'];
        $roles = $this->roleService->getAll($filters, $options);
        return view('admin.roles.index', compact('roles', 'filters', 'options'));
    }

    /**
     * Hiển thị form tạo vai trò
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $permissions = $this->permissionService->getAll();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Xử lý tạo vai trò
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);
        $return = $this->roleService->create($request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.roles.index')
                ->with('success', $return['message'] ?? 'Tạo vai trò thành công.');
        }
        return redirect()->route('admin.roles.index')
            ->with('fail', $return['message'] ?? 'Tạo vai trò thất bại.');
    }

    /**
     * Hiển thị form sửa vai trò
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $role = $this->roleService->findById($id);
        $permissions = $this->permissionService->getAll();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Xử lý cập nhật vai trò
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);
        $return = $this->roleService->update($id, $request->all());
        if (!empty($return['success'])) {
            return redirect()->route('admin.roles.index')
                ->with('success', $return['message'] ?? 'Cập nhật vai trò thành công.');
        }
        return redirect()->route('admin.roles.index')
            ->with('fail', $return['message'] ?? 'Cập nhật vai trò thất bại.');
    }

    /**
     * Xóa vai trò
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->roleService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.roles.index')
                ->with('success', $return['message'] ?? 'Xóa vai trò thành công.');
        }
        return redirect()->route('admin.roles.index')
            ->with('fail', $return['message'] ?? 'Xóa vai trò thất bại.');
    }
}

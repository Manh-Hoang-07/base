<?php

namespace App\Http\Controllers\Admin\Role;

use App\Http\Controllers\Controller;
use App\Services\Admin\Role\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected RoleService $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Hiển thị danh sách vai trò
     */
    public function index(): View|Application|Factory
    {
        $roles = $this->roleService->getAllRoles();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Hiển thị form tạo vai trò
     */
    public function create(): View|Application|Factory
    {
        $permissions = $this->roleService->getAllPermissions();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Xử lý tạo vai trò
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $this->roleService->createRole($request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Tạo vai trò thành công!');
    }

    /**
     * Hiển thị form sửa vai trò
     */
    public function edit($id): View|Application|Factory
    {
        $role = $this->roleService->getRoleById($id);
        $permissions = $this->roleService->getAllPermissions();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Xử lý cập nhật vai trò
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $this->roleService->updateRole($id, $request->all());
        return redirect()->route('admin.roles.index')->with('success', 'Cập nhật vai trò thành công!');
    }

    /**
     * Xóa vai trò
     */
    public function destroy($id): RedirectResponse
    {
        $this->roleService->deleteRole($id);
        return redirect()->route('admin.roles.index')->with('success', 'Xóa vai trò thành công!');
    }
}

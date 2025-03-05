<?php

namespace App\Http\Controllers\Admin\Permission;

use App\Http\Controllers\Controller;
use App\Services\Admin\Permission\PermissionService;
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
     */
    public function index()
    {
        $permissions = $this->permissionService->getAllPermissions();
        return view('admin.permissions.index', compact('permissions'));
    }

    /**
     * Hiển thị form tạo quyền
     */
    public function create()
    {
        return view('admin.permissions.create');
    }

    /**
     * Xử lý tạo quyền
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        $this->permissionService->createPermission($request->all());
        return redirect()->route('admin.permissions.index')->with('success', 'Tạo quyền thành công!');
    }

    /**
     * Hiển thị form sửa quyền
     */
    public function edit($id)
    {
        $permission = $this->permissionService->getPermissionById($id);
        return view('admin.permissions.edit', compact('permission'));
    }

    /**
     * Xử lý cập nhật quyền
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $id
        ]);

        $this->permissionService->updatePermission($id, $request->all());
        return redirect()->route('admin.permissions.index')->with('success', 'Cập nhật quyền thành công!');
    }

    /**
     * Xóa quyền
     */
    public function destroy($id)
    {
        $this->permissionService->deletePermission($id);
        return redirect()->route('admin.permissions.index')->with('success', 'Xóa quyền thành công!');
    }
}

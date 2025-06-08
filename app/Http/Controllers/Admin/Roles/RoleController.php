<?php

namespace App\Http\Controllers\Admin\Roles;

use App\Http\Controllers\BaseController;


use App\Services\Admin\Permissions\PermissionService;
use App\Services\Admin\Roles\RoleService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Http\Request;


class RoleController extends BaseController
{
    protected PermissionService $permissionService;

    public function __construct(RoleService $roleService, PermissionService $permissionService)
    {
        $this->service = $roleService;
        $this->permissionService = $permissionService;
    }

    public function getService(): RoleService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách vai trò
     * @param Request $request
     * @return View|Application|Factory
     */
    public function index(Request $request): View|Application|Factory
    {
        return view('admin.roles.index', [
            'filters' => $this->getFilters($request->all()),
            'options' => $this->getOptions($request->all())
        ]);
    }

    /**
     * Hiển thị form tạo vai trò
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $permissions = $this->permissionService->getList();
        return view('admin.roles.create', compact('permissions'));
    }



    /**
     * Hiển thị form sửa vai trò
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $role = $this->getService()->findById($id);
        $permissions = $this->permissionService->getList();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }





    /**
     * Autocomplete vai trò (cho AJAX)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = $request->get('search') ?? '';
        $limit = $request->get('limit') ?? 10;

        try {
            $roles = $this->getService()->autocomplete($search, 'title', $limit);
            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Lấy danh sách autocomplete thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách autocomplete: ' . $e->getMessage()
            ], 500);
        }
    }
}

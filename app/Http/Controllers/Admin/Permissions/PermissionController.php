<?php

namespace App\Http\Controllers\Admin\Permissions;

use App\Http\Controllers\BaseController;

use App\Services\Admin\Permissions\PermissionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

use Illuminate\Http\Request;

class PermissionController extends BaseController
{

    public function __construct(PermissionService $permissionService)
    {
        $this->service = $permissionService;
    }

    public function getService(): PermissionService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách quyền
     * @param Request $request
     * @return Factory|Application|View
     */
    public function index(Request $request): View|Application|Factory
    {
        return view('admin.permissions.index', [
            'filters' => $this->getFilters($request->all()),
            'options' => $this->getOptions($request->all())
        ]);
    }

    /**
     * Hiển thị form tạo quyền
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        $permissions = $this->getService()->getAll();
        return view('admin.permissions.create', compact('permissions'));
    }



    /**
     * Hiển thị form sửa quyền
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $permission = $this->getService()->findById($id);
        $permissions = $this->getService()->getAll();
        return view('admin.permissions.edit', compact('permission', 'permissions'));
    }





    /**
     * Autocomplete quyền (cho AJAX)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = $request->get('search') ?? '';
        $limit = $request->get('limit') ?? 10;

        try {
            $permissions = $this->getService()->autocomplete($search, 'name', $limit);
            return response()->json([
                'success' => true,
                'data' => $permissions,
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

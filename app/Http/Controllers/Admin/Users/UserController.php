<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\BaseController;
use App\Services\Admin\Users\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function getService(): UserService
    {
        return $this->service;
    }

    /**
     * Hiển thị danh sách tài khoản
     * @param Request $request
     * @return View|Application|Factory
     */
    public function index(Request $request): View|Application|Factory
    {
        return view('admin.users.index', [
            'filters' => $this->getFilters($request->all()),
            'options' => $this->getOptions($request->all())
        ]);
    }

    /**
     * Hiển thị form tạo tài khoản
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.users.create', [
            'roles' => Role::all()
        ]);
    }



    /**
     * Hiển thị form chỉnh sửa tài khoản
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $user = $this->getService()->findById($id);
        return view('admin.users.edit', compact('user'));
    }





    /**
     * Hiển thị form phân vai trò
     * @param $id
     * @return View|Application|Factory
     */
    public function showAssignRolesForm($id): View|Application|Factory
    {
        $user = $this->getService()->findById($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.assign-roles', compact('user', 'roles', 'userRoles'));
    }





    /**
     * Autocomplete tài khoản (cho AJAX)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function autocomplete(Request $request): \Illuminate\Http\JsonResponse
    {
        $search = $request->get('search') ?? '';
        $limit = $request->get('limit') ?? 10;

        try {
            $users = $this->getService()->autocomplete($search, 'email', $limit);
            return response()->json([
                'success' => true,
                'data' => $users,
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

<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\AssignRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Services\Admin\User\UserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Hiển thị danh sách tài khoản
     * @param Request $request
     * @return View|Application|Factory
     */
    public function index(Request $request): View|Application|Factory
    {
        $filters = $request->only(['name', 'email', 'role']);
        $options['sortBy'] = $request->get('sortBy', 'id');
        $options['sortOrder'] = $request->get('sortOrder', 'asc');
        $users = $this->userService->getAll($filters, $options);
        return view('admin.users.index', compact('users', 'filters', 'options'));
    }

    /**
     * Hiển thị form tạo tài khoản
     * @return View|Application|Factory
     */
    public function create(): View|Application|Factory
    {
        return view('admin.users.create');
    }

    /**
     * Xử lý tạo tài khoản
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $return = $this->userService->create($request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.users.index')
                ->with('success', $return['message'] ?? 'Tạo tài khoản thành công.');
        }
        return redirect()->route('admin.users.index')
            ->with('fail', $return['message'] ?? 'Tạo tài khoản thất bại.');
    }

    /**
     * Hiển thị form chỉnh sửa tài khoản
     * @param $id
     * @return View|Application|Factory
     */
    public function edit($id): View|Application|Factory
    {
        $user = $this->userService->findById($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Xử lý chỉnh sửa tài khoản
     * @param UpdateRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $return = $this->userService->update($id, $request->validated());
        if (!empty($return['success'])) {
            return redirect()->route('admin.users.index')
                ->with('success', $return['message'] ?? 'Cập nhật tài khoản thành công.');
        }
        return redirect()->route('admin.users.index')
            ->with('fail', $return['message'] ?? 'Cập nhật tài khoản thất bại.');
    }

    /**
     * Xử lý xóa tài khoản
     * @param $id
     * @return RedirectResponse
     */
    public function delete($id): RedirectResponse
    {
        $return = $this->userService->delete($id);
        if (!empty($return['success'])) {
            return redirect()->route('admin.users.index')
                ->with('success', $return['message'] ?? 'Xóa tài khoản thành công.');
        }
        return redirect()->route('admin.users.index')
            ->with('fail', $return['message'] ?? 'Xóa tài khoản thất bại.');
    }

    /**
     * Hiển thị form phân vai trò
     * @param $id
     * @return View|Application|Factory
     */
    public function showAssignRolesForm($id): View|Application|Factory
    {
        $user = $this->userService->findById($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();
        return view('admin.users.assign-roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Xử lý phân vai trò
     * @param AssignRequest $request
     * @param $id
     * @return RedirectResponse
     */
    public function assignRoles(AssignRequest $request, $id): RedirectResponse
    {
        $this->userService->assignRoles($id, $request->roles ?? []);
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật vai trò thành công.');
    }

}

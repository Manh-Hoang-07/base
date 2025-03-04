<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\Admin\UserService;
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

    public function index(Request $request): View|Application|Factory
    {
        $filters = $request->only(['name', 'email', 'role']);
        $sortBy = $request->get('sortBy', 'id');
        $sortOrder = $request->get('sortOrder', 'asc');
        $users = $this->userService->list($filters, 10, $sortBy, $sortOrder);
        return view('admin.users.index', compact('users', 'filters', 'sortBy', 'sortOrder'));
    }

    public function create(): View|Application|Factory
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());
        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản thành công.');
    }

    public function edit($id): View|Application|Factory
    {
        $user = User::findOrFail($id);
        $roles = \Spatie\Permission\Models\Role::all(); // Lấy tất cả vai trò
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $this->userService->update($user, $request->validated());
        return redirect()->route('admin.users.index')->with('success', 'Cập nhật thành công.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $this->userService->delete($user);
        return redirect()->route('admin.users.index')->with('success', 'Xóa tài khoản thành công.');
    }

    public function assignRoles(Request $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $request->validate([
            'roles' => 'array',
        ]);

        $user->syncRoles($request->roles);


        return redirect()->back()->with('success', 'Cập nhật vai trò thành công.');
    }

    public function showAssignRolesForm($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.assign-roles', compact('user', 'roles', 'userRoles'));
    }


}

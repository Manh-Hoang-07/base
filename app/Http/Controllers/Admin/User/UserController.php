<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\AssignRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
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

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->userService->create($request->validated());
        return redirect()->route('admin.users.index')->with('success', 'Tạo tài khoản thành công.');
    }

    public function edit($id): View|Application|Factory
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Lấy tất cả vai trò
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateRequest $request, $id): RedirectResponse
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

    public function assignRoles(AssignRequest $request, $id): RedirectResponse
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

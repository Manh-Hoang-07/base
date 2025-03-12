@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Danh Sách Vai Trò</h2>
        @canany(['manage_roles', 'create_roles'])
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Thêm Vai Trò</a>
        @endcanany
        <!-- Form lọc -->
        <form action="{{ route('admin.roles.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <input type="text" name="title" class="form-control" placeholder="Nhập ý nghĩa"
                           value="{{ request('title') }}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                           value="{{ request('name') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>Ý nghĩa vai trò</th>
                <th>Tên Vai Trò</th>
                <th>Quyền</th>
                <th>Hành Động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{ $role->title ?? '' }}</td>
                    <td>{{ $role->name ?? '' }}</td>
                    <td>
                        @foreach($role->permissions ?? [] as $permission)
                            <span class="badge bg-success">{{ $permission->name ?? '' }}</span>
                        @endforeach
                    </td>
                    <td>
                        @canany(['manage_roles', 'edit_roles'])
                        <a href="{{ route('admin.roles.edit', $role->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        @endcanany
                        @canany(['manage_roles', 'delete_roles'])
                        <form action="{{ route('admin.roles.delete', $role->id ?? '') }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                        @endcanany
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Hiển thị phân trang -->
    <div class="d-flex justify-content-center">
        {{ $roles->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection

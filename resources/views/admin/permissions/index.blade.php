@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Quản lý quyền</h2>
        @canany(['manage_permissions', 'create_permissions'])
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mb-3">Thêm quyền</a>
        @endcanany
        <!-- Form lọc -->
        <form action="{{ route('admin.permissions.index') }}" method="GET" class="mb-3">
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
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>ID</th>
                <th>Ý nghĩa quyền</th>
                <th>Tên quyền</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions ?? [] as $permission)
                <tr>
                    <td>{{ $permission->id ?? '' }}</td>
                    <td>{{ $permission->title ?? '' }}</td>
                    <td>{{ $permission->name ?? '' }}</td>
                    <td>
                        @canany(['manage_permissions', 'edit_permissions'])
                        <a href="{{ route('admin.permissions.edit', $permission->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        @endcanany
                        @canany(['manage_permissions', 'delete_permissions'])
                        <form action="{{ route('admin.permissions.delete', $permission->id ?? '') }}" method="POST" style="display:inline-block;">
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
        {{ $permissions->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection

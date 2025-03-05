@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Quản lý quyền</h2>
        @canany(['manage_permissions', 'create_permissions'])
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary mb-3">Thêm quyền</a>
        @endcanany

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
@endsection

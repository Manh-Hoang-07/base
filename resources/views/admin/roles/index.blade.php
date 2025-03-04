@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Danh Sách Vai Trò</h2>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Thêm Vai Trò</a>
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
                        <a href="{{ route('admin.roles.edit', $role->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.roles.delete', $role->id ?? '') }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

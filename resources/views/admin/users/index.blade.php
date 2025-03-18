@extends('admin.index')

@section('content')
    <h2>Danh sách tài khoản</h2>
    @canany(['create_users'])
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Thêm Tài khoản</a>
    @endcanany

    <!-- Form lọc -->
    <form action="{{ route('admin.users.index') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                       value="{{ request('name') }}">
            </div>
            <div class="col-md-4">
                <input type="email" name="email" class="form-control" placeholder="Nhập email"
                       value="{{ request('email') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">Lọc</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Bảng danh sách tài khoản -->
    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
            <th>Trạng thái</th>
            <th>Vai Trò</th>
            <th>Hành Động</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id ?? '' }}</td>
                <td>{{ $user->name ?? '' }}</td>
                <td>{{ $user->email ?? '' }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>{{ !empty($user->is_blocked) ? 'Khóa' : 'Không khóa' }}</td>
                <td>
                    @foreach($user->roles as $role)
                        <span class="badge bg-primary">{{ $role->name ?? '' }}</span>
                    @endforeach
                </td>
                <td>
                    @canany(['assign_users'])
                        <a href="{{ route('admin.users.showAssignRolesForm', $user->id ?? '') }}" class="btn btn-sm btn-warning">Gán Vai Trò</a>
                    @endcanany
                    @canany(['edit_users'])
                        <a href="{{ route('admin.profiles.edit', $user->id ?? '') }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('admin.users.toggleBlock', $user->id ?? '') }}" method="POST"
                                  style="display:inline;">
                                @csrf
                                <input type="hidden" name="status" value="{{ !empty($user->is_blocked) ? 0 : 1 }}">
                                <button type="submit" class="btn btn-sm btn-warning">Đổi trạng thái</button>
                            </form>
                    @endcanany
                    @canany(['delete_users'])
                        <form action="{{ route('admin.users.delete', $user->id ?? '') }}" method="POST"
                              style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    @endcanany
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Hiển thị phân trang -->
    <div class="d-flex justify-content-center">
        {{ $users->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection

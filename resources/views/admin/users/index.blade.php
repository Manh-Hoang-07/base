@extends('admin.home.dashboard')

@section('title', 'Danh sách tài khoản')

@section('content')
    <h2>Danh sách tài khoản</h2>

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
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Ngày tạo</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Hiển thị phân trang -->
    <div class="d-flex justify-content-center">
        {{ $users->links() }}
    </div>
@endsection

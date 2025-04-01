@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý bản sao sách</h2>
        @canany(['manage_declarations', 'create_declarations'])
            <a href="{{ route('admin.declarations.book_copies.create') }}" class="btn btn-primary mb-3">Thêm bài đăng</a>
        @endcanany

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.book_copies.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                           value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.book_copies.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Mã Sách</th>
                <th>Số Thứ Tự</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($bookCopies as $copy)
                <tr>
                    <td>{{ $copy->id }}</td>
                    <td>{{ $copy->book->code }}</td>
                    <td>{{ $copy->copy_number }}</td>
                    <td>{{ $copy->status }}</td>
                    <td>
                        <a href="{{ route('admin.book_copies.edit', $copy->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.book_copies.destroy', $copy->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Hiển thị phân trang -->
    @include('vendor.pagination.pagination', ['paginator' => $bookCopies])
@endsection

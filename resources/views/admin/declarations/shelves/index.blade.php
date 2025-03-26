@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý Kệ Sách</h2>
        <a href="{{ route('admin.declarations.shelves.create') }}" class="btn btn-primary mb-3">Thêm Kệ Sách</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.shelves.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên kệ sách"
                           value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã kệ sách"
                           value="{{ request('code') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.shelves.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên Kệ</th>
                <th>Mã Kệ</th>
                <th>Sức Chứa</th>
                <th>Khu Vực</th>
                <th>Trạng Thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($shelves ?? [] as $shelf)
                <tr>
                    <td>{{ $shelf->id ?? '' }}</td>
                    <td>{{ $shelf->name ?? '' }}</td>
                    <td>{{ $shelf->code ?? '' }}</td>
                    <td>{{ $shelf->capacity ?? '' }}</td>
                    <td>{{ $shelf->area->name ?? '' }}</td>
                    <td>{{ $shelf->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.shelves.edit', $shelf->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.shelves.delete', $shelf->id ?? '') }}" method="POST" style="display:inline-block;">
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

    <!-- Hiển thị phân trang -->
    @include('vendor.pagination.pagination', ['paginator' => $shelves])
@endsection

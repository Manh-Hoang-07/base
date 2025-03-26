@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý Danh Mục</h2>
        <a href="{{ route('admin.declarations.categories.create') }}" class="btn btn-primary mb-3">Thêm Danh Mục</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.categories.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã" value="{{ request('code') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.categories.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên Danh Mục</th>
                <th>Mã</th>
                <th>Slug</th>
                <th>Danh Mục Cha</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories ?? [] as $category)
                <tr>
                    <td>{{ $category->id ?? '' }}</td>
                    <td>{{ $category->name ?? '' }}</td>
                    <td>{{ $category->code ?? '' }}</td>
                    <td>{{ $category->slug ?? '' }}</td>
                    <td>{{ $category->parent->name ?? 'N/A' }}</td>
                    <td>{{ $category->status ? 'Hiển thị' : 'Ẩn' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.categories.edit', $category->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.categories.delete', $category->id ?? '') }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Hiển thị phân trang -->
    @include('vendor.pagination.pagination', ['paginator' => $categories])
@endsection

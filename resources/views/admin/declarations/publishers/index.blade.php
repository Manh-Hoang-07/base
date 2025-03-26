@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý Nhà Xuất Bản</h2>
        <a href="{{ route('admin.declarations.publishers.create') }}" class="btn btn-primary mb-3">Thêm Nhà Xuất Bản</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.publishers.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã" value="{{ request('code') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.publishers.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên Nhà Xuất Bản</th>
                <th>Mã</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Địa chỉ</th>
                <th>Website</th>
                <th>Ngày thành lập</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($publishers ?? [] as $publisher)
                <tr>
                    <td>{{ $publisher->id ?? '' }}</td>
                    <td>{{ $publisher->name ?? '' }}</td>
                    <td>{{ $publisher->code ?? '' }}</td>
                    <td>{{ $publisher->email ?? '' }}</td>
                    <td>{{ $publisher->phone ?? '' }}</td>
                    <td>{{ $publisher->address ?? '' }}</td>
                    <td>{{ $publisher->website ?? '' }}</td>
                    <td>{{ $publisher->established_at ?? '' }}</td>
                    <td>{{ $publisher->status ? 'Hoạt động' : 'Không hoạt động' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.publishers.edit', $publisher->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.publishers.delete', $publisher->id ?? '') }}" method="POST" style="display:inline-block;">
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
    @include('vendor.pagination.pagination', ['paginator' => $publishers])
@endsection

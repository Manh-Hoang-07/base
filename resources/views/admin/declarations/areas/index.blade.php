@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý khu vực</h2>
        <a href="{{ route('admin.declarations.areas.create') }}" class="btn btn-primary mb-3">Thêm khu vực</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.areas.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                           value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="code" class="form-control" placeholder="Nhập mã"
                           value="{{ request('code') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.areas.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên khu vực</th>
                <th>Mã khu vực</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($areas ?? [] as $area)
                <tr>
                    <td>{{ $area->id ?? '' }}</td>
                    <td>{{ $area->name ?? '' }}</td>
                    <td>{{ $area->code ?? '' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.areas.edit', $area->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.areas.delete', $area->id ?? '') }}" method="POST" style="display:inline-block;">
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
    @include('vendor.pagination.pagination', ['paginator' => $areas])
@endsection

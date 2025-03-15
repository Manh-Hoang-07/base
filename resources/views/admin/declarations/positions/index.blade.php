@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý chức vụ</h2>
        <a href="{{ route('admin.declarations.positions.create') }}" class="btn btn-primary mb-3">Thêm chức vụ</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.positions.index') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('admin.declarations.positions.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên chức vụ</th>
                <th>Mã chức vụ</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($positions ?? [] as $position)
                <tr>
                    <td>{{ $position->id ?? '' }}</td>
                    <td>{{ $position->name ?? '' }}</td>
                    <td>{{ $position->code ?? '' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.positions.edit', $position->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.positions.delete', $position->id ?? '') }}" method="POST" style="display:inline-block;">
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
    <div class="d-flex justify-content-center">
        {{ $positions->links('vendor.pagination.bootstrap-5') }}
    </div>
@endsection

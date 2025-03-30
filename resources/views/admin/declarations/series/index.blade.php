@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý series</h2>
        <a href="{{ route('admin.declarations.series.create') }}" class="btn btn-primary mb-3">Thêm series</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.series.index') }}" method="GET" class="mb-3">
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
                    <a href="{{ route('admin.declarations.series.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên series</th>
                <th>Mã series</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($series ?? [] as $each)
                <tr>
                    <td>{{ $each->id ?? '' }}</td>
                    <td>{{ $each->name ?? '' }}</td>
                    <td>{{ $each->code ?? '' }}</td>
                    <td>{{ $each->status ?? '' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.series.edit', $each->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.series.delete', $each->id ?? '') }}" method="POST" style="display:inline-block;">
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
    @include('vendor.pagination.pagination', ['paginator' => $series])
@endsection

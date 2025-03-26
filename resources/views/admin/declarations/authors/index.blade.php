@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý Tác Giả</h2>
        <a href="{{ route('admin.declarations.authors.create') }}" class="btn btn-primary mb-3">Thêm Tác Giả</a>

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.authors.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="pen_name" class="form-control" placeholder="Nhập bút danh" value="{{ request('pen_name') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.authors.index') }}" class="btn btn-secondary">Reset</a>
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
                <th>Tên Tác Giả</th>
                <th>Bút Danh</th>
                <th>Email</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($authors ?? [] as $author)
                <tr>
                    <td>{{ $author->id ?? '' }}</td>
                    <td>{{ $author->name ?? '' }}</td>
                    <td>{{ $author->pen_name ?? '' }}</td>
                    <td>{{ $author->email ?? '' }}</td>
                    <td>
                        <a href="{{ route('admin.declarations.authors.edit', $author->id ?? '') }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.declarations.authors.delete', $author->id ?? '') }}" method="POST" style="display:inline-block;">
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
    @include('vendor.pagination.pagination', ['paginator' => $authors])
@endsection

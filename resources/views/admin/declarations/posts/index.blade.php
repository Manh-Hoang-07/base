@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Quản lý bài đăng</h2>
        @canany(['manage_declarations', 'create_declarations'])
            <a href="{{ route('admin.declarations.posts.create') }}" class="btn btn-primary mb-3">Thêm bài đăng</a>
        @endcanany

        <!-- Form lọc -->
        <form action="{{ route('admin.declarations.posts.index') }}" method="GET" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                           value="{{ request('name') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                    <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Hình ảnh</th>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" width="80" height="50">
                        @else
                            Không có ảnh
                        @endif
                    </td>
                    <td>{{ $post->name }}</td>
                    <td>{{ ucfirst($post->status) }}</td>
                    <td>
                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.posts.delete', $post->id) }}" method="POST" style="display:inline-block;">
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
    @include('vendor.pagination.pagination', ['paginator' => $posts])
@endsection

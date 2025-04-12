@extends('admin.index')

@section('page_title', 'Danh sách Bài Đăng')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Bài Đăng</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <form action="{{ route('admin.declarations.posts.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="name" class="form-control" placeholder="Nhập tiêu đề"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['manage_declarations', 'create_declarations'])
                                    <a href="{{ route('admin.declarations.posts.create') }}" class="btn btn-primary ms-auto">Thêm Bài Đăng</a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Hình Ảnh</th>
                                    <th>Tiêu Đề</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($posts as $index => $post)
                                    <tr>
                                        <td>{{ $posts->firstItem() + $index }}</td>
                                        <td>
                                            @if($post->image)
                                                <img src="{{ asset($post->image) }}" width="80" height="50" style="object-fit: cover">
                                            @else
                                                <span class="text-muted">Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $post->name }}</td>
                                        <td>
                                            @if($post->status === 'active')
                                                <span class="badge bg-success">Hiển thị</span>
                                            @else
                                                <span class="badge bg-secondary">Ẩn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.declarations.posts.edit', $post->id) }}"
                                               class="btn btn-sm btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.declarations.posts.delete', $post->id) }}"
                                                  method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Hiển thị phân trang -->
                        @include('vendor.pagination.pagination', ['paginator' => $posts])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.index')

@section('page_title', 'Danh sách Sách')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Sách</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <form action="{{ route('admin.declarations.books.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="name" class="form-control" placeholder="Nhập tiêu đề sách"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="code" class="form-control" placeholder="Nhập mã sách"
                                                   value="{{ request('code') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.books.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.declarations.books.create') }}" class="btn btn-success ms-auto">Thêm Sách</a>
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
                                    <th>Mã</th>
                                    <th>Tiêu đề</th>
                                    <th>Nhà xuất bản</th>
                                    <th>Ngôn ngữ</th>
                                    <th>Ngày xuất bản</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($books as $index => $book)
                                    <tr>
                                        <td>{{ $books->firstItem() + $index }}</td>
                                        <td>{{ $book->code }}</td>
                                        <td>{{ $book->name }}</td>
                                        <td>{{ $book->publisher->name ?? 'N/A' }}</td>
                                        <td>{{ $book->language }}</td>
                                        <td>{{ \Carbon\Carbon::parse($book->published_at)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge {{ $book->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $book->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.declarations.books.edit', $book->id) }}"
                                               class="btn btn-sm btn-primary" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.declarations.books.delete', $book->id) }}"
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
                        @include('vendor.pagination.pagination', ['paginator' => $books])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('admin.index')

@section('page_title', 'Danh sách Kệ Sách')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Kệ Sách</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <form action="{{ route('admin.declarations.shelves.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="name" class="form-control" placeholder="Nhập tên kệ"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="code" class="form-control" placeholder="Nhập mã kệ"
                                                   value="{{ request('code') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.shelves.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.declarations.shelves.create') }}" class="btn btn-primary ms-auto">Thêm Kệ Sách</a>
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
                                    <th>Tên Kệ</th>
                                    <th>Mã Kệ</th>
                                    <th>Sức Chứa</th>
                                    <th>Khu Vực</th>
                                    <th>Trạng Thái</th>
                                    <th>Hành Động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shelves as $index => $shelf)
                                    <tr>
                                        <td>{{ $shelves->firstItem() + $index }}</td>
                                        <td>{{ $shelf->name ?? '' }}</td>
                                        <td>{{ $shelf->code ?? '' }}</td>
                                        <td>{{ $shelf->capacity ?? '' }}</td>
                                        <td>{{ $shelf->area->name ?? 'N/A' }}</td>
                                        <td>
                                            @if($shelf->status === 'active')
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.declarations.shelves.edit', $shelf->id) }}"
                                               class="btn btn-sm btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.declarations.shelves.delete', $shelf->id) }}"
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
                        @include('vendor.pagination.pagination', ['paginator' => $shelves])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

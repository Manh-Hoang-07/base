@extends('admin.index')

@section('page_title', 'Danh sách Tác Giả')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Tác Giả</li>
@endsection

@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <!-- Form lọc -->
                                <form action="{{ route('admin.declarations.authors.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="pen_name" class="form-control" placeholder="Nhập bút danh"
                                                   value="{{ request('pen_name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.authors.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.declarations.authors.create') }}" class="btn btn-primary ms-auto">Thêm Tác Giả</a>
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

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên Tác Giả</th>
                                <th>Bút Danh</th>
                                <th>Email</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($authors as $index => $author)
                                <tr>
                                    <td>{{ $authors->firstItem() + $index }}</td>
                                    <td>{{ $author->name ?? '' }}</td>
                                    <td>{{ $author->pen_name ?? '' }}</td>
                                    <td>{{ $author->email ?? '' }}</td>
                                    <td>
                                        <a href="{{ route('admin.declarations.authors.edit', $author->id ?? '') }}"
                                           class="btn btn-sm btn-warning" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.declarations.authors.delete', $author->id ?? '') }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                    onclick="return confirm('Bạn có chắc muốn xóa tác giả này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- Hiển thị phân trang -->
                        @include('vendor.pagination.pagination', ['paginator' => $authors])
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

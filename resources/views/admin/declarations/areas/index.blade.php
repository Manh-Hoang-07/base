@extends('admin.index')

@section('page_title', 'Danh sách khu vực')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách khu vực</li>
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
                                <form action="{{ route('admin.declarations.areas.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="name" class="form-control" placeholder="Nhập tên"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="code" class="form-control" placeholder="Nhập mã"
                                                   value="{{ request('code') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.areas.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.declarations.areas.create') }}" class="btn btn-primary ms-auto">Thêm khu vực</a>
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
                                <th>Tên khu vực</th>
                                <th>Mã khu vực</th>
                                <th>Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($areas as $index => $area)
                                <tr>
                                    <td>{{ $areas->firstItem() + $index }}</td>
                                    <td>{{ $area->name }}</td>
                                    <td>{{ $area->code }}</td>
                                    <td>
                                        <a href="{{ route('admin.declarations.areas.edit', $area->id) }}"
                                           class="btn btn-sm btn-warning" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.declarations.areas.delete', $area->id) }}"
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                    onclick="return confirm('Bạn có chắc muốn xóa khu vực này?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <!-- Hiển thị phân trang -->
                        @include('vendor.pagination.pagination', ['paginator' => $areas])
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

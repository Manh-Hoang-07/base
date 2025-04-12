@extends('admin.index')

@section('page_title', 'Danh sách Nhà Xuất Bản')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Nhà Xuất Bản</li>
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
                                <form action="{{ route('admin.declarations.publishers.index') }}" method="GET">
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
                                            <a href="{{ route('admin.declarations.publishers.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.declarations.publishers.create') }}" class="btn btn-primary ms-auto">Thêm Nhà Xuất Bản</a>
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
                                    <th>Tên Nhà Xuất Bản</th>
                                    <th>Mã</th>
                                    <th>Email</th>
                                    <th>SĐT</th>
                                    <th>Địa chỉ</th>
                                    <th>Website</th>
                                    <th>Ngày thành lập</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($publishers as $index => $publisher)
                                    <tr>
                                        <td>{{ $publishers->firstItem() + $index }}</td>
                                        <td>{{ $publisher->name ?? '' }}</td>
                                        <td>{{ $publisher->code ?? '' }}</td>
                                        <td>{{ $publisher->email ?? '' }}</td>
                                        <td>{{ $publisher->phone ?? '' }}</td>
                                        <td>{{ $publisher->address ?? '' }}</td>
                                        <td>{{ $publisher->website ?? '' }}</td>
                                        <td>{{ $publisher->established_at ?? '' }}</td>
                                        <td>
                                            @if($publisher->status)
                                                <span class="badge bg-success">Hoạt động</span>
                                            @else
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.declarations.publishers.edit', $publisher->id) }}"
                                               class="btn btn-sm btn-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.declarations.publishers.delete', $publisher->id) }}"
                                                  method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                        onclick="return confirm('Bạn có chắc muốn xóa nhà xuất bản này?')">
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
                        @include('vendor.pagination.pagination', ['paginator' => $publishers])
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

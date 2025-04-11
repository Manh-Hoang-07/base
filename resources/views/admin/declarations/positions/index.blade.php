@extends('admin.index')

@section('title', 'Quản lý chức vụ')

@section('page_title', 'Quản lý chức vụ')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Chức vụ</li>
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
                        <div class="row align-items-center">
                            <div class="col-md-9">
                                <form action="{{ route('admin.declarations.positions.index') }}" method="GET" class="row g-2">
                                    <div class="col-md-4">
                                        <input type="text" name="name" class="form-control" placeholder="Nhập tên chức vụ"
                                               value="{{ request('name') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="code" class="form-control" placeholder="Nhập mã chức vụ"
                                               value="{{ request('code') }}">
                                    </div>
                                    <div class="col-md-4 d-flex">
                                        <button type="submit" class="btn btn-primary me-2">Lọc</button>
                                        <a href="{{ route('admin.declarations.positions.index') }}" class="btn btn-secondary">Reset</a>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3 text-end">
                                <a href="{{ route('admin.declarations.positions.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm chức vụ
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên chức vụ</th>
                                <th>Mã chức vụ</th>
                                <th width="150">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($positions as $position)
                                <tr>
                                    <td>{{ $position->id }}</td>
                                    <td>{{ $position->name }}</td>
                                    <td>{{ $position->code }}</td>
                                    <td>
                                        <a href="{{ route('admin.declarations.positions.edit', $position->id) }}"
                                           class="btn btn-sm btn-warning" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.declarations.positions.delete', $position->id) }}"
                                              method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Phân trang -->
                        <div class="mt-3">
                            @include('vendor.pagination.pagination', ['paginator' => $positions])
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

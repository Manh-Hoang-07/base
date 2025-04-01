@extends('admin.index')

@section('content')
    <div class="">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Quản lý quyền</li>
            </ol>
        </nav>

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h5 class="mb-0">Danh sách quyền</h5>
                @canany(['manage_permissions', 'create_permissions'])
                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-light btn-sm">+ Thêm quyền</a>
                @endcanany
            </div>
            <div class="card-body">
                <!-- Form lọc -->
                <form action="{{ route('admin.permissions.index') }}" method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input type="text" name="title" class="form-control" placeholder="Nhập ý nghĩa" value="{{ request('title') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="name" class="form-control" placeholder="Nhập tên" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="parent" class="form-control" placeholder="Quyền cha" value="{{ request('parent') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Bảng danh sách quyền -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Ý nghĩa quyền</th>
                            <th>Tên quyền</th>
                            <th>Quyền cha</th>
                            <th>Mặc định</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions ?? [] as $permission)
                            <tr>
                                <td>{{ $permission->id ?? '' }}</td>
                                <td>{{ $permission->title ?? '' }}</td>
                                <td>{{ $permission->name ?? '' }}</td>
                                <td>{{ $permission->parent->name ?? 'N/A' }}</td>
                                <td>{{ $permission->is_default ? 'Có' : 'Không' }}</td>
                                <td class="text-center">
                                    @canany(['manage_permissions', 'edit_permissions'])
                                        <a href="{{ route('admin.permissions.edit', $permission->id ?? '') }}" class="text-warning mx-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcanany
                                    @canany(['manage_permissions', 'delete_permissions'])
                                        <form action="{{ route('admin.permissions.delete', $permission->id ?? '') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="border-0 bg-transparent text-danger mx-1">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcanany
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Hiển thị phân trang -->
        @include('vendor.pagination.pagination', ['paginator' => $permissions])
    </div>
@endsection

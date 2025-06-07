@extends('admin.index')

@section('page_title', 'Danh sách tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách tài khoản</li>
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
                                <form id="filter-form" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="email" name="email" id="filter-email" class="form-control"
                                                   placeholder="Nhập email" value="{{ request('email') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <select name="status" id="filter-status" class="form-select">
                                                <option value="">-- Trạng thái --</option>
                                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Hoạt động</option>
                                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Khóa</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="filter-btn" class="btn btn-primary">Lọc</button>
                                            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['create_users'])
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary ms-auto">
                                        <i class="fas fa-plus"></i> Thêm Tài khoản
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{-- Sử dụng API Table Component với bộ lọc --}}
                        @include('components.api-table', [
                            'id' => 'users',
                            'url' => '/api/v1/admin/users/list',
                            'fields' => ['id', 'email', 'created_at', 'is_blocked', 'roles_count'],
                            'columns' => ['ID', 'Email', 'Ngày tạo', 'Trạng thái', 'Số vai trò'],
                            'searchable' => false, // Tắt search box vì đã có filter form
                            'actions' => true,
                            'actionButtons' => [
                                [
                                    'url' => route('admin.users.edit', ':id'),
                                    'class' => 'btn-warning',
                                    'icon' => 'fas fa-edit',
                                    'title' => 'Sửa'
                                ],
                                [
                                    'url' => route('admin.users.showAssignRolesForm', ':id'),
                                    'class' => 'btn-info',
                                    'icon' => 'fas fa-user-tag',
                                    'title' => 'Gán vai trò'
                                ],
                                [
                                    'action' => 'toggle-status',
                                    'class' => 'btn-secondary',
                                    'icon' => 'fas fa-ban',
                                    'title' => 'Thay đổi trạng thái'
                                ],
                                [
                                    'action' => 'delete',
                                    'class' => 'btn-danger',
                                    'icon' => 'fas fa-trash',
                                    'title' => 'Xóa'
                                ]
                            ],
                            'perPage' => 10
                        ])
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Đợi API table được khởi tạo
    setTimeout(function() {
        // Kết nối filter form với API table
        const filterBtn = document.getElementById('filter-btn');
        const resetBtn = document.getElementById('reset-btn');

        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                const filters = {};

                const emailInput = document.getElementById('filter-email');
                const statusSelect = document.getElementById('filter-status');

                if (emailInput && emailInput.value) {
                    filters.email = emailInput.value;
                }

                if (statusSelect && statusSelect.value !== '') {
                    filters.is_blocked = statusSelect.value;
                }

                console.log('Applying filters:', filters);

                // Tìm API table instance và gọi applyFilters
                if (window.apiTableInstances && window.apiTableInstances['users']) {
                    window.apiTableInstances['users'].applyFilters(filters);
                } else if (typeof loadData === 'function') {
                    loadData(filters);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                // Clear form
                const emailInput = document.getElementById('filter-email');
                const statusSelect = document.getElementById('filter-status');

                if (emailInput) emailInput.value = '';
                if (statusSelect) statusSelect.value = '';

                // Reload without filters
                if (window.apiTableInstances && window.apiTableInstances['users']) {
                    window.apiTableInstances['users'].applyFilters({});
                } else if (typeof loadData === 'function') {
                    loadData();
                }
            });
        }
    }, 500);
});
</script>
@endsection

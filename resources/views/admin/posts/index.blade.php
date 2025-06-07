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
                                <form id="filter-form" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="name" id="filter-name" class="form-control"
                                                   placeholder="Tìm theo tiêu đề" value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <select name="status" id="filter-status" class="form-select">
                                                <option value="">-- Trạng thái --</option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="require_login" id="filter-require-login" class="form-select">
                                                <option value="">-- Yêu cầu đăng nhập --</option>
                                                <option value="1" {{ request('require_login') == '1' ? 'selected' : '' }}>Yêu cầu</option>
                                                <option value="0" {{ request('require_login') == '0' ? 'selected' : '' }}>Không yêu cầu</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" id="filter-btn" class="btn btn-primary">Lọc</button>
                                            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['manage_declarations', 'create_declarations'])
                                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary ms-auto">
                                        <i class="fas fa-plus"></i> Thêm Bài Đăng
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Sử dụng API Table Component --}}
                        @include('components.api-table', [
                            'id' => 'posts',
                            'url' => route('admin.posts.index') . '?api=1',
                            'fields' => ['id', 'name', 'status', 'require_login', 'created_at'],
                            'columns' => ['ID', 'Tiêu đề', 'Trạng thái', 'Yêu cầu đăng nhập', 'Ngày tạo'],
                            'searchable' => false,
                            'actions' => true,
                            'actionButtons' => [
                                [
                                    'url' => route('admin.posts.edit', ':id'),
                                    'class' => 'btn-warning',
                                    'icon' => 'fas fa-edit',
                                    'title' => 'Sửa'
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const filterBtn = document.getElementById('filter-btn');
        const resetBtn = document.getElementById('reset-btn');

        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                const filters = {};

                const nameInput = document.getElementById('filter-name');
                const statusSelect = document.getElementById('filter-status');
                const requireLoginSelect = document.getElementById('filter-require-login');

                if (nameInput && nameInput.value) {
                    filters.name = nameInput.value;
                }

                if (statusSelect && statusSelect.value) {
                    filters.status = statusSelect.value;
                }

                if (requireLoginSelect && requireLoginSelect.value !== '') {
                    filters.require_login = requireLoginSelect.value;
                }

                if (window.apiTableInstances && window.apiTableInstances['posts']) {
                    window.apiTableInstances['posts'].applyFilters(filters);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const nameInput = document.getElementById('filter-name');
                const statusSelect = document.getElementById('filter-status');
                const requireLoginSelect = document.getElementById('filter-require-login');

                if (nameInput) nameInput.value = '';
                if (statusSelect) statusSelect.value = '';
                if (requireLoginSelect) requireLoginSelect.value = '';

                if (window.apiTableInstances && window.apiTableInstances['posts']) {
                    window.apiTableInstances['posts'].applyFilters({});
                }
            });
        }
    }, 500);
});
</script>
@endsection

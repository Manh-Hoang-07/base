@extends('admin.index')

@section('page_title', 'Danh sách vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách vai trò</li>
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
                                            <input type="text" name="title" id="filter-title" class="form-control"
                                                   placeholder="Nhập ý nghĩa" value="{{ request('title') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="name" id="filter-name" class="form-control"
                                                   placeholder="Nhập tên" value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="filter-btn" class="btn btn-primary">Lọc</button>
                                            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['manage_roles', 'create_roles'])
                                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary ms-auto">
                                        <i class="fas fa-plus"></i> Thêm Vai Trò
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Sử dụng API Table Component --}}
                        @include('components.api-table', [
                            'id' => 'roles',
                            'url' => '/api/v1/admin/roles',
                            'fields' => ['id', 'title', 'name', 'permissions_count'],
                            'columns' => ['ID', 'Ý nghĩa vai trò', 'Tên vai trò', 'Số quyền'],
                            'searchable' => false,
                            'actions' => true,
                            'actionButtons' => [
                                [
                                    'url' => route('admin.roles.edit', ':id'),
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

                const titleInput = document.getElementById('filter-title');
                const nameInput = document.getElementById('filter-name');

                if (titleInput && titleInput.value) {
                    filters.title = titleInput.value;
                }

                if (nameInput && nameInput.value) {
                    filters.name = nameInput.value;
                }

                if (window.apiTableInstances && window.apiTableInstances['roles']) {
                    window.apiTableInstances['roles'].applyFilters(filters);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const titleInput = document.getElementById('filter-title');
                const nameInput = document.getElementById('filter-name');

                if (titleInput) titleInput.value = '';
                if (nameInput) nameInput.value = '';

                if (window.apiTableInstances && window.apiTableInstances['roles']) {
                    window.apiTableInstances['roles'].applyFilters({});
                }
            });
        }
    }, 500);
});
</script>
@endsection

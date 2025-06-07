@extends('admin.index')

@section('page_title', 'Danh sách quyền')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách quyền</li>
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
                                        <div class="col-md-3">
                                            <input type="text" name="title" id="filter-title" class="form-control"
                                                   placeholder="Ý nghĩa quyền" value="{{ request('title') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="name" id="filter-name" class="form-control"
                                                   placeholder="Tên quyền" value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="parent" id="filter-parent" class="form-control"
                                                   placeholder="Quyền cha" value="{{ request('parent') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" id="filter-btn" class="btn btn-primary">Lọc</button>
                                            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['manage_permissions', 'create_permissions'])
                                    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary ms-auto">
                                        <i class="fas fa-plus"></i> Thêm quyền
                                    </a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Sử dụng API Table Component --}}
                        @include('components.api-table', [
                            'id' => 'permissions',
                            'url' => '/api/v1/admin/permissions/list',
                            'fields' => ['id', 'title', 'name', 'parent_title', 'is_default'],
                            'columns' => ['ID', 'Ý nghĩa quyền', 'Tên quyền', 'Quyền cha', 'Mặc định'],
                            'searchable' => false,
                            'actions' => true,
                            'actionButtons' => [
                                [
                                    'url' => route('admin.permissions.edit', ':id'),
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
    setTimeout(function() {
        const filterBtn = document.getElementById('filter-btn');
        const resetBtn = document.getElementById('reset-btn');

        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                const filters = {};

                const titleInput = document.getElementById('filter-title');
                const nameInput = document.getElementById('filter-name');
                const parentInput = document.getElementById('filter-parent');

                if (titleInput && titleInput.value) {
                    filters.title = titleInput.value;
                }

                if (nameInput && nameInput.value) {
                    filters.name = nameInput.value;
                }

                if (parentInput && parentInput.value) {
                    filters.parent = parentInput.value;
                }

                if (window.apiTableInstances && window.apiTableInstances['permissions']) {
                    window.apiTableInstances['permissions'].applyFilters(filters);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const titleInput = document.getElementById('filter-title');
                const nameInput = document.getElementById('filter-name');
                const parentInput = document.getElementById('filter-parent');

                if (titleInput) titleInput.value = '';
                if (nameInput) nameInput.value = '';
                if (parentInput) parentInput.value = '';

                if (window.apiTableInstances && window.apiTableInstances['permissions']) {
                    window.apiTableInstances['permissions'].applyFilters({});
                }
            });
        }
    }, 500);
});
</script>
@endsection

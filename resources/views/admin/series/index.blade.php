@extends('admin.index')

@section('page_title', 'Danh sách Series')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách Series</li>
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
                                                   placeholder="Nhập tên series" value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" name="code" id="filter-code" class="form-control"
                                                   placeholder="Nhập mã series" value="{{ request('code') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="filter-btn" class="btn btn-primary">Lọc</button>
                                            <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('admin.series.create') }}" class="btn btn-primary ms-auto">
                                    <i class="fas fa-plus"></i> Thêm Series
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Sử dụng API Table Component --}}
                        @include('components.api-table', [
                            'id' => 'series',
                            'url' => '/api/v1/admin/series/list',
                            'fields' => ['id', 'name', 'code', 'status', 'created_at'],
                            'columns' => ['ID', 'Tên Series', 'Mã Series', 'Trạng thái', 'Ngày tạo'],
                            'searchable' => false,
                            'actions' => true,
                            'actionButtons' => [
                                [
                                    'url' => route('admin.series.edit', ':id'),
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
                const codeInput = document.getElementById('filter-code');

                if (nameInput && nameInput.value) {
                    filters.name = nameInput.value;
                }

                if (codeInput && codeInput.value) {
                    filters.code = codeInput.value;
                }

                if (window.apiTableInstances && window.apiTableInstances['series']) {
                    window.apiTableInstances['series'].applyFilters(filters);
                }
            });
        }

        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                const nameInput = document.getElementById('filter-name');
                const codeInput = document.getElementById('filter-code');

                if (nameInput) nameInput.value = '';
                if (codeInput) codeInput.value = '';

                if (window.apiTableInstances && window.apiTableInstances['series']) {
                    window.apiTableInstances['series'].applyFilters({});
                }
            });
        }
    }, 500);
});
</script>
@endsection

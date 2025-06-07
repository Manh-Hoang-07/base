@extends('admin.index')

@section('page_title', 'Test API Table')

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Test API Table Component</h5>
            </div>
            <div class="card-body">
                {{-- Test với dữ liệu tĩnh trước --}}
                <div class="api-table-container" id="test-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Ngày tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="test-tbody">
                                <tr>
                                    <td>1</td>
                                    <td>admin@example.com</td>
                                    <td>01/01/2024</td>
                                    <td><span class="badge bg-success">Hoạt động</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning me-1" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="alert('Delete clicked')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>user@example.com</td>
                                    <td>02/01/2024</td>
                                    <td><span class="badge bg-danger">Khóa</span></td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-warning me-1" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger" onclick="alert('Delete clicked')" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr>

                <h6>Test API Table Component (Real)</h6>
                {{-- Test với API thật --}}
                @include('components.api-table', [
                    'id' => 'users-test',
                    'url' => route('admin.users.index') . '?api=1',
                    'fields' => ['id', 'email', 'created_at', 'is_blocked'],
                    'columns' => ['ID', 'Email', 'Ngày tạo', 'Trạng thái'],
                    'searchable' => true,
                    'actions' => true,
                    'actionButtons' => [
                        [
                            'url' => '#edit/:id',
                            'class' => 'btn-warning',
                            'icon' => 'fas fa-edit',
                            'title' => 'Sửa'
                        ]
                    ],
                    'perPage' => 5
                ])
            </div>
        </div>
    </div>
</div>
@endsection

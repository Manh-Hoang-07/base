# Hướng dẫn sử dụng API Table System

## Tổng quan

Hệ thống API Table cho phép bạn dễ dàng chuyển từ view truyền thống sang API-first approach mà không cần viết lại nhiều code.

## Cách hoạt động

1. **Controller** sử dụng `ApiResponseTrait` để tự động xử lý request API hoặc View
2. **View** sử dụng component `api-table` để render dữ liệu từ API
3. **JavaScript** tự động xử lý phân trang, tìm kiếm, và các action

## Bước 1: Cập nhật Controller

```php
<?php

namespace App\Http\Controllers\Admin\Example;

use App\Http\Controllers\BaseController;
use App\Traits\ApiResponseTrait;

class ExampleController extends BaseController
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        // Tự động trả về JSON nếu là API request, hoặc View nếu không
        return $this->apiOrViewResponse(
            $request, 
            'admin.example.index', 
            [
                'filters' => $this->getFilters($request->all()),
                'options' => $this->getOptions($request->all())
            ]
        );
    }
}
```

## Bước 2: Cập nhật View

```blade
@extends('admin.index')

@section('content')
<div class="app-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Danh sách</h5>
                <a href="{{ route('admin.example.create') }}" class="btn btn-primary">
                    Thêm mới
                </a>
            </div>
            <div class="card-body">
                @include('components.api-table', [
                    'id' => 'example',
                    'url' => route('admin.example.index') . '?api=1',
                    'fields' => ['id', 'name', 'email', 'created_at'],
                    'columns' => ['ID', 'Tên', 'Email', 'Ngày tạo'],
                    'searchable' => true,
                    'actions' => true,
                    'actionButtons' => [
                        [
                            'url' => route('admin.example.edit', ':id'),
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
                    'perPage' => 15
                ])
            </div>
        </div>
    </div>
</div>
@endsection
```

## Bước 3: Cấu hình API Table

### Tham số cơ bản

- `id`: ID duy nhất cho table (bắt buộc)
- `url`: URL API để lấy dữ liệu (bắt buộc)
- `fields`: Mảng các field cần hiển thị (bắt buộc)
- `columns`: Mảng tên cột hiển thị (bắt buộc)

### Tham số tùy chọn

- `searchable`: Bật/tắt tìm kiếm (mặc định: false)
- `actions`: Bật/tắt cột action (mặc định: false)
- `actionButtons`: Mảng các nút action tùy chỉnh
- `perPage`: Số bản ghi mỗi trang (mặc định: 10)

### Action Buttons

```php
'actionButtons' => [
    // Link thông thường
    [
        'url' => route('admin.example.edit', ':id'),
        'class' => 'btn-warning',
        'icon' => 'fas fa-edit',
        'title' => 'Sửa'
    ],
    
    // Action đặc biệt
    [
        'action' => 'delete',
        'class' => 'btn-danger',
        'icon' => 'fas fa-trash',
        'title' => 'Xóa'
    ],
    
    [
        'action' => 'toggle-status',
        'class' => 'btn-secondary',
        'icon' => 'fas fa-toggle-on',
        'title' => 'Đổi trạng thái'
    ]
]
```

## Bước 4: Xử lý dữ liệu trong Service (tùy chọn)

```php
public function getList(array $filters = [], array $options = []): LengthAwarePaginator
{
    // Thêm relations nếu cần
    $options['relations'] = array_merge($options['relations'] ?? [], ['category']);
    
    $result = parent::getList($filters, $options);
    
    // Transform dữ liệu nếu cần
    $result->getCollection()->transform(function ($item) {
        $item->category_name = $item->category->name ?? '';
        return $item;
    });
    
    return $result;
}
```

## Tính năng

### ✅ Đã có sẵn
- Phân trang tự động
- Tìm kiếm
- Loading states
- Error handling
- Responsive design
- Action buttons (edit, delete, toggle status)
- Format dữ liệu tự động (date, status)

### 🔄 Có thể mở rộng
- Bulk actions
- Export data
- Advanced filters
- Sorting
- Custom formatters

## Ví dụ hoàn chỉnh

Xem file `resources/views/admin/users/index.blade.php` và `app/Http/Controllers/Admin/Users/UserController.php` để tham khảo implementation hoàn chỉnh.

## Lợi ích

1. **Tái sử dụng**: Một component cho tất cả các bảng
2. **Performance**: Chỉ load dữ liệu cần thiết
3. **UX**: Không reload trang khi phân trang/tìm kiếm
4. **Maintainable**: Code gọn gàng, dễ bảo trì
5. **Flexible**: Dễ dàng tùy chỉnh theo nhu cầu

## Troubleshooting

### Lỗi thường gặp

1. **Không load được dữ liệu**: Kiểm tra URL API và permissions
2. **Action buttons không hoạt động**: Đảm bảo đã include `admin-actions.js`
3. **Phân trang không hoạt động**: Kiểm tra Service có trả về LengthAwarePaginator không

### Debug

Mở Developer Tools > Console để xem lỗi JavaScript nếu có.

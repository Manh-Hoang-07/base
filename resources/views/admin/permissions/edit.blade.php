@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa quyền</h2>

        <form action="{{ route('admin.permissions.update', $permission->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Ý nghĩa quyền</label>
                <input type="text" class="form-control" name="title" value="{{ $permission->title ?? '' }}" required>

                <label for="name" class="form-label">Tên quyền</label>
                <input type="text" class="form-control" name="name" value="{{ $permission->name ?? '' }}" required>

                <label for="parent_id" class="form-label">Quyền cha</label>
                <select class="form-control select2" name="parent_id">
                    <option value="">Chọn quyền cha</option>
                    @foreach($permissions as $parentPermission)
                        <option value="{{ $parentPermission->id }}" {{ (isset($permission) && $permission->parent_id == $parentPermission->id) ? 'selected' : '' }}>
                            {{ $parentPermission->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>

    <!-- Khởi tạo Select2 -->
    <script>
        $(document).ready(function() {
            $('.select2').select2();  // Chỉ định select2 cho các select có class "select2"
        });
    </script>
@endsection

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
                <select class="form-control select2" name="parent_id" data-selected={{ $permission->parent_id ?? '' }}
                        data-url="{{ route('admin.permissions.autocomplete') }}">
                    <option value="">Chọn quyền cha</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

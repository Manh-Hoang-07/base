@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa khu vực</h2>
        <form action="{{ route('admin.declarations.areas.update', $area->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên khu vực</label>
                <input type="text" class="form-control" name="name" value="{{ $area->name ?? '' }}" required>

                <label for="code" class="form-label">Mã khu vực</label>
                <input type="text" class="form-control" name="code" value="{{ $area->code ?? '' }}" required>

                <label for="type" class="form-label">Loại khu vực</label>
                <input type="text" class="form-control" name="type" value="{{ $area->type ?? '' }}" required>

                <label for="location" class="form-label">Vị trí</label>
                <input type="text" class="form-control" name="location" value="{{ $area->location ?? '' }}">

                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3">{{ $area->description ?? '' }}</textarea>

                <label for="capacity" class="form-label">Sức chứa</label>
                <input type="number" class="form-control" name="capacity" value="{{ $area->capacity ?? 0 }}" min="0">

                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="1" {{ $area->status ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ !$area->status ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.declarations.areas.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection

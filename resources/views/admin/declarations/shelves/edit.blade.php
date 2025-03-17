@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa Kệ Sách</h2>

        <form action="{{ route('admin.declarations.shelves.update', $shelf->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="area_id" class="form-label">Khu Vực</label>
                <select class="form-control" name="area_id" required>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}" {{ (isset($shelf) && $shelf->area_id == $area->id) ? 'selected' : '' }}>{{ $area->name }}</option>
                    @endforeach
                </select>

                <label for="code" class="form-label">Mã Kệ Sách</label>
                <input type="text" class="form-control" name="code" value="{{ $shelf->code ?? '' }}" required>

                <label for="name" class="form-label">Tên Kệ Sách</label>
                <input type="text" class="form-control" name="name" value="{{ $shelf->name ?? '' }}" required>

                <label for="capacity" class="form-label">Sức Chứa</label>
                <input type="number" class="form-control" name="capacity" value="{{ $shelf->capacity ?? '' }}" required>

                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" name="description">{{ $shelf->description ?? '' }}</textarea>

                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" name="status">
                    <option value="1" {{ !empty($shelf->status) ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ empty($shelf->status) ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

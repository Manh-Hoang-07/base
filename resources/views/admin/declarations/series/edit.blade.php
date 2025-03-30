@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa series</h2>
        <form action="{{ route('admin.declarations.series.update', $series->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên series</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $series->name) }}" required>

                <label for="code" class="form-label">Mã series</label>
                <input type="text" class="form-control" name="code" value="{{ old('code', $series->code) }}" required>

                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3">{{ old('description', $series->description) }}</textarea>

                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="active" {{ old('status', $series->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status', $series->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa Danh Mục</h2>

        <form action="{{ route('admin.declarations.categories.update', $category->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên Danh Mục</label>
                <input type="text" class="form-control" name="name" value="{{ $category->name ?? '' }}" required>

                <label for="code" class="form-label">Mã Danh Mục</label>
                <input type="text" class="form-control" name="code" value="{{ $category->code ?? '' }}" required>

                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" value="{{ $category->slug ?? '' }}" required>

                <label for="parent_id" class="form-label">Danh Mục Cha</label>
                <select class="form-control" name="parent_id">
                    <option value="">Không có</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" name="description">{{ $category->description ?? '' }}</textarea>

                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" name="status">
                    <option value="1" {{ ($category->status ?? 1) == 1 ? 'selected' : '' }}>Hiển Thị</option>
                    <option value="0" {{ ($category->status ?? 1) == 0 ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection

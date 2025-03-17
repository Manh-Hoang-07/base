@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm danh mục</h2>
        <form action="{{ route('admin.declarations.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" name="name" required>

                <label for="code" class="form-label">Mã danh mục</label>
                <input type="text" class="form-control" name="code" required>

                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" name="slug" required>

                <label for="parent_id" class="form-label">Danh mục cha</label>
                <select class="form-control" name="parent_id">
                    <option value="">Chọn danh mục cha (nếu có)</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" name="description"></textarea>

                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="1" selected>Hiển thị</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

@extends('admin.index')

@section('content')
    <div class="container">
        <h2 class="mb-4">Thêm bài đăng</h2>

        <form action="{{ route('admin.declarations.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="title">Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="title">Hình ảnh</label>
                <x-uploads.file-upload name="image" required />
            </div>

            <div class="mb-3">
                <label for="content">Nội dung</label>
                <textarea name="content" id="content" class="form-control" data-editor="true"></textarea>
            </div>

            <div class="mb-3">
                <label for="image">Ảnh bài đăng</label>
                <input type="file" name="image" id="image" class="form-control">
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="active" selected>Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection


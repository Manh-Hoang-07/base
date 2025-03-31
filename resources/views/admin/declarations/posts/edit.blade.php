@extends('admin.index')

@section('content')
    <div class="container">
        <h2 class="mb-4">Chỉnh sửa bài đăng</h2>

        <form action="{{ route('admin.declarations.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title">Tiêu đề</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $post->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="image">Hình ảnh</label>
                @if ($post->image)
                    <div class="mb-2">
                        <img src="{{ asset($post->image) }}" alt="Ảnh bài đăng" width="200">
                    </div>
                @endif
                <x-uploads.file-upload name="image" />
            </div>

            <div class="mb-3">
                <label for="content">Nội dung</label>
                <textarea name="content" id="content" class="form-control" data-editor="true">{{ old('content', $post->content) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="active" {{ $post->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ $post->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection

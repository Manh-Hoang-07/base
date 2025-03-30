@extends('admin.index')

@section('content')
    <div class="container">
        <h2 class="mb-4">Chỉnh sửa bài đăng</h2>

        <form action="{{ route('admin.declarations.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title">Tiêu đề</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="image">Hình ảnh</label>
                @if ($post->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Ảnh bài đăng" width="200">
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
                    <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>Xuất bản</option>
                    <option value="archived" {{ $post->status == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection

@extends('admin.index')

@section('page_title', 'Chỉnh sửa bài đăng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Bài đăng</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chỉnh sửa bài đăng</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Tiêu đề</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $post->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="image" class="col-sm-2 col-form-label">Hình ảnh</label>
                        <div class="col-sm-10">
                            @if ($post->image)
                                <div class="mb-2">
                                    <img src="{{ asset($post->image) }}" alt="Ảnh bài đăng" width="200">
                                </div>
                            @endif
                            <x-uploads.file-upload name="image" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="content" class="col-sm-2 col-form-label">Nội dung</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" data-editor="true" rows="6">{{ old('content', $post->content) }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $post->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status', $post->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

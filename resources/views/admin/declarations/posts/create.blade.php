@extends('admin.index')

@section('page_title', 'Thêm Bài Đăng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.posts.index') }}">Bài Đăng</a></li>
    <li class="breadcrumb-item active">Thêm</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Bài Đăng</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tiêu Đề</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Hình Ảnh</label>
                        <div class="col-sm-10">
                            <x-uploads.file-upload name="image" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nội Dung</label>
                        <div class="col-sm-10">
                            <textarea name="content" id="content" class="form-control" rows="5" data-editor="true"></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label">Trạng Thái</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="status">
                                <option value="active" selected>Hoạt Động</option>
                                <option value="inactive">Không Hoạt Động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Lưu</button>
                        <a href="{{ route('admin.declarations.posts.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

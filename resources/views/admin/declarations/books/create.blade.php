@extends('admin.index')

@section('page_title', 'Thêm Sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.books.index') }}">Sách</a></li>
    <li class="breadcrumb-item active">Thêm</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Sách Mới</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Mã sách</label>
                            <input type="text" class="form-control" name="code" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số tập</label>
                            <input type="number" class="form-control" name="volume">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Series truyện</label>
                            <select class="form-control select2" name="series_id"
                                    data-url="{{ route('admin.declarations.series.autocomplete') }}"
                                    data-display-field="name">
                                <option value="">Chọn series</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngày xuất bản</label>
                            <input type="date" class="form-control" name="published_at">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nhà xuất bản</label>
                            <select class="form-control select2" name="publisher_id"
                                    data-url="{{ route('admin.declarations.publishers.autocomplete') }}"
                                    data-display-field="name">
                                <option value="">Chọn nhà xuất bản</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ngôn ngữ</label>
                            <input type="text" class="form-control" name="language">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số trang</label>
                            <input type="number" class="form-control" name="page_count">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="summary" rows="3"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Ảnh bìa</label>
                            <x-uploads.file-upload name="image" required />
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-control">
                                @foreach(\App\Enums\BasicStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">Thêm</button>
                        <a href="{{ route('admin.declarations.books.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

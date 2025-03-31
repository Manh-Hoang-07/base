@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Sách</h2>
        <form action="{{ route('admin.declarations.books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Mã sách</label>
                <input type="text" class="form-control" name="code" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số tập</label>
                <input type="number" class="form-control" name="volume">
            </div>

            <div class="mb-3">
                <label class="form-label">Series truyện</label>
                <select class="form-control select2" name="series_id" data-url="{{ route('admin.declarations.series.autocomplete') }}" data-display-field="name">
                    <option value="">Chọn nhà xuất bản</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" class="form-control" name="isbn" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày xuất bản</label>
                <input type="date" class="form-control" name="published_at">
            </div>

            <div class="mb-3">
                <label class="form-label">Nhà xuất bản</label>
                <select class="form-control select2" name="publisher_id" data-url="{{ route('admin.declarations.publishers.autocomplete') }}" data-display-field="name">
                    <option value="">Chọn nhà xuất bản</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngôn ngữ</label>
                <input type="text" class="form-control" name="language">
            </div>

            <div class="mb-3">
                <label class="form-label">Số trang</label>
                <input type="number" class="form-control" name="page_count">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="summary" rows="3"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh bìa</label>
                <x-uploads.file-upload name="image" required />
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    @foreach(\App\Enums\BasicStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection


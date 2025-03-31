@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chỉnh Sửa Sách</h2>
        <form action="{{ route('admin.declarations.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Mã sách</label>
                <input type="text" class="form-control" name="code" value="{{ $book->code }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="name" value="{{ $book->name }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số tập</label>
                <input type="number" class="form-control" name="volume" value="{{ $book->volume }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Series truyện</label>
                <select class="form-control select2" name="series_id" data-url="{{ route('admin.declarations.series.autocomplete') }}" data-display-field="name" data-selected={{ $book->series_id ?? '' }}>
                    <option value="">Chọn series</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">ISBN</label>
                <input type="text" class="form-control" name="isbn" value="{{ $book->isbn }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày xuất bản</label>
                <input type="date" class="form-control" name="published_at" value="{{ $book->published_at }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Nhà xuất bản</label>
                <select class="form-control select2" name="publisher_id" data-url="{{ route('admin.declarations.publishers.autocomplete') }}" data-display-field="name" data-selected={{ $book->publisher_id ?? '' }}>
                    <option value="">Chọn nhà xuất bản</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngôn ngữ</label>
                <input type="text" class="form-control" name="language" value="{{ $book->language }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Số trang</label>
                <input type="number" class="form-control" name="page_count" value="{{ $book->page_count }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea class="form-control" name="summary" rows="3">{{ $book->summary }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh bìa</label>
                <x-uploads.file-upload name="image" :value="$book->image" />
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    @foreach(\App\Enums\BasicStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $book->status == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection

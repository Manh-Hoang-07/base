@extends('admin.index')

@section('page_title', 'Chỉnh sửa sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.books.index') }}">Sách</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chỉnh sửa sách</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mã sách</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="code" value="{{ $book->code }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tiêu đề</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" value="{{ $book->name }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Số tập</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="volume" value="{{ $book->volume }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Series truyện</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="series_id"
                                    data-url="{{ route('admin.declarations.series.autocomplete') }}"
                                    data-display-field="name"
                                    data-selected="{{ $book->series_id ?? '' }}">
                                <option value="">Chọn series</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">ISBN</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="isbn" value="{{ $book->isbn }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ngày xuất bản</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" name="published_at" value="{{ $book->published_at }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Nhà xuất bản</label>
                        <div class="col-sm-10">
                            <select class="form-control select2" name="publisher_id"
                                    data-url="{{ route('admin.declarations.publishers.autocomplete') }}"
                                    data-display-field="name"
                                    data-selected="{{ $book->publisher_id ?? '' }}">
                                <option value="">Chọn nhà xuất bản</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ngôn ngữ</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="language" value="{{ $book->language }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Số trang</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="page_count" value="{{ $book->page_count }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mô tả</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="summary" rows="3">{{ $book->summary }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ảnh bìa</label>
                        <div class="col-sm-10">
                            <x-uploads.file-upload name="image" :value="$book->image" />
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label">Trạng thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                @foreach(\App\Enums\BasicStatus::cases() as $status)
                                    <option value="{{ $status->value }}" {{ $book->status == $status->value ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.declarations.books.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

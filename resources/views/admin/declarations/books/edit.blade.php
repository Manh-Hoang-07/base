@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa Sách</h2>
        <form action="{{ route('admin.books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Mã sách</label>
                <input type="text" class="form-control" name="code" value="{{ $book->code }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" name="title" value="{{ $book->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Số tập</label>
                <input type="number" class="form-control" name="volume" value="{{ $book->volume }}">
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
                <select name="publisher_id" class="form-control" required>
                    @foreach($publishers as $publisher)
                        <option value="{{ $publisher->id }}" {{ $book->publisher_id == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                    @endforeach
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
                <input type="file" class="form-control" name="cover_image">
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-control">
                    <option value="active" {{ $book->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ $book->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

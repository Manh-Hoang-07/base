@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chỉnh Sửa Bản Sao Sách</h2>
        <form action="{{ route('admin.declarations.book_copies.update', $bookCopy->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Sách</label>
                <select class="form-control select2" name="book_id" data-url="{{ route('admin.declarations.books.autocomplete') }}" data-display-field="name" data-selected={{ $permission->book_id ?? '' }}>
                    <option value="">Chọn sách</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số Thứ Tự</label>
                <input type="number" class="form-control" name="copy_number" value="{{ $bookCopy->copy_number }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng Thái</label>
                <select name="status" class="form-control">
                    @foreach(\App\Enums\BasicStatus::cases() as $status)
                        <option value="{{ $status->value }}" {{ $bookCopy->status == $status->value ? 'selected' : '' }}>{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection

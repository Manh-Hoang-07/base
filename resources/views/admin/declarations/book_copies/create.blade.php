@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Bản Sao Sách</h2>
        <form action="{{ route('admin.declarations.book_copies.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Sách</label>
                <select class="form-control select2" name="book_id" data-url="{{ route('admin.declarations.books.autocomplete') }}" data-display-field="name">
                    <option value="">Chọn sách</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Số Thứ Tự</label>
                <input type="number" class="form-control" name="copy_number" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Trạng Thái</label>
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


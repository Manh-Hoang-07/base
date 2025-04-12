@extends('admin.index')

@section('page_title', 'Thêm Bản Sao Sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.book_copies.index') }}">Bản Sao Sách</a></li>
    <li class="breadcrumb-item active">Thêm</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Bản Sao Sách</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.book_copies.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Sách</label>
                            <select class="form-control select2" name="book_id"
                                    data-url="{{ route('admin.declarations.books.autocomplete') }}"
                                    data-display-field="name">
                                <option value="">Chọn sách</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số Thứ Tự</label>
                            <input type="number" class="form-control" name="copy_number" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Trạng Thái</label>
                            <select name="status" class="form-control">
                                @foreach(\App\Enums\BasicStatus::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success">Thêm</button>
                        <a href="{{ route('admin.declarations.book_copies.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

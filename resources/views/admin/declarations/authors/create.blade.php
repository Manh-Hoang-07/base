@extends('admin.index')

@section('page_title', 'Thêm Tác Giả')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.authors.index') }}">Tác Giả</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Tác Giả</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.authors.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tên Tác Giả</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Bút Danh</label>
                        <div class="col-sm-10">
                            <input type="text" name="pen_name" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Số Điện Thoại</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Quốc Tịch</label>
                        <div class="col-sm-10">
                            <input type="text" name="nationality" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tiểu Sử</label>
                        <div class="col-sm-10">
                            <textarea name="biography" rows="4" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ngày Sinh</label>
                        <div class="col-sm-4">
                            <input type="date" name="birth_date" class="form-control">
                        </div>

                        <label class="col-sm-2 col-form-label text-end">Ngày Mất</label>
                        <div class="col-sm-4">
                            <input type="date" name="death_date" class="form-control">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm Tác Giả</button>
                        <a href="{{ route('admin.declarations.authors.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

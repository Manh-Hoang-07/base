@extends('admin.index')

@section('page_title', 'Thêm Nhà Xuất Bản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.publishers.index') }}">Nhà Xuất Bản</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Nhà Xuất Bản</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.publishers.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mã Nhà Xuất Bản</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tên Nhà Xuất Bản</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Số Điện Thoại</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Địa Chỉ</label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Website</label>
                        <div class="col-sm-10">
                            <input type="text" name="website" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ngày Thành Lập</label>
                        <div class="col-sm-4">
                            <input type="date" name="established_at" class="form-control">
                        </div>

                        <label class="col-sm-2 col-form-label text-end">Trạng Thái</label>
                        <div class="col-sm-4">
                            <select name="status" class="form-control">
                                <option value="1">Hoạt Động</option>
                                <option value="0">Ngừng Hoạt Động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm Nhà Xuất Bản</button>
                        <a href="{{ route('admin.declarations.publishers.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@extends('admin.index')

@section('page_title', 'Thêm khu vực')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.areas.index') }}">Khu vực</a></li>
    <li class="breadcrumb-item active" aria-current="page">Thêm mới</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm khu vực</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.areas.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tên khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mã khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Loại khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="type" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Vị trí</label>
                        <div class="col-sm-10">
                            <input type="text" name="location" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mô tả</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Sức chứa</label>
                        <div class="col-sm-10">
                            <input type="number" name="capacity" class="form-control" min="0">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label">Trạng thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="1" selected>Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm</button>
                        <a href="{{ route('admin.declarations.areas.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

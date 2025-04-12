@extends('admin.index')

@section('page_title', 'Thêm Kệ Sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.shelves.index') }}">Kệ Sách</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Thêm Kệ Sách</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.shelves.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Khu Vực</label>
                        <div class="col-sm-10">
                            <select name="area_id" class="form-control" required>
                                <option value="">-- Chọn khu vực --</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mã Kệ Sách</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tên Kệ Sách</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Sức Chứa</label>
                        <div class="col-sm-10">
                            <input type="number" name="capacity" class="form-control" min="0" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Mô Tả</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Trạng Thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-control">
                                <option value="1">Hoạt Động</option>
                                <option value="0">Ngừng Hoạt Động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Thêm Kệ Sách</button>
                        <a href="{{ route('admin.declarations.shelves.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

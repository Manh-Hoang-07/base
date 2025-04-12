@extends('admin.index')

@section('page_title', 'Chỉnh sửa Nhà Xuất Bản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.publishers.index') }}">Nhà Xuất Bản</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chỉnh sửa Nhà Xuất Bản</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.publishers.update', $publisher->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="code" class="col-sm-2 col-form-label">Mã Nhà Xuất Bản</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $publisher->code) }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Tên Nhà Xuất Bản</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $publisher->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $publisher->email) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="phone" class="col-sm-2 col-form-label">Số Điện Thoại</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $publisher->phone) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="address" class="col-sm-2 col-form-label">Địa Chỉ</label>
                        <div class="col-sm-10">
                            <textarea name="address" class="form-control" rows="2">{{ old('address', $publisher->address) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="website" class="col-sm-2 col-form-label">Website</label>
                        <div class="col-sm-10">
                            <input type="text" name="website" class="form-control"
                                   value="{{ old('website', $publisher->website) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="established_at" class="col-sm-2 col-form-label">Ngày Thành Lập</label>
                        <div class="col-sm-10">
                            <input type="date" name="established_at" class="form-control"
                                   value="{{ old('established_at', $publisher->established_at) }}">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.declarations.publishers.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

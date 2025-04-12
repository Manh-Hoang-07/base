@extends('admin.index')

@section('page_title', 'Chỉnh sửa khu vực')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.areas.index') }}">Khu vực</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chỉnh sửa khu vực</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.areas.update', $area->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Tên khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $area->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="code" class="col-sm-2 col-form-label">Mã khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $area->code) }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="type" class="col-sm-2 col-form-label">Loại khu vực</label>
                        <div class="col-sm-10">
                            <input type="text" name="type" class="form-control @error('type') is-invalid @enderror"
                                   value="{{ old('type', $area->type) }}" required>
                            @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="location" class="col-sm-2 col-form-label">Vị trí</label>
                        <div class="col-sm-10">
                            <input type="text" name="location" class="form-control"
                                   value="{{ old('location', $area->location) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-2 col-form-label">Mô tả</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $area->description) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="capacity" class="col-sm-2 col-form-label">Sức chứa</label>
                        <div class="col-sm-10">
                            <input type="number" name="capacity" class="form-control" min="0"
                                   value="{{ old('capacity', $area->capacity) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="status" class="col-sm-2 col-form-label">Trạng thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $area->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status', $area->status) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.declarations.areas.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

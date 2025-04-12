@extends('admin.index')

@section('page_title', 'Chỉnh sửa Kệ Sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.shelves.index') }}">Kệ Sách</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Chỉnh sửa Kệ Sách</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.declarations.shelves.update', $shelf->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label for="area_id" class="col-sm-2 col-form-label">Khu Vực</label>
                        <div class="col-sm-10">
                            <select name="area_id" class="form-select @error('area_id') is-invalid @enderror" required>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ old('area_id', $shelf->area_id) == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('area_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="code" class="col-sm-2 col-form-label">Mã Kệ Sách</label>
                        <div class="col-sm-10">
                            <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $shelf->code) }}" required>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="name" class="col-sm-2 col-form-label">Tên Kệ Sách</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $shelf->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="capacity" class="col-sm-2 col-form-label">Sức Chứa</label>
                        <div class="col-sm-10">
                            <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', $shelf->capacity) }}" min="0" required>
                            @error('capacity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="description" class="col-sm-2 col-form-label">Mô Tả</label>
                        <div class="col-sm-10">
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $shelf->description) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label for="status" class="col-sm-2 col-form-label">Trạng Thái</label>
                        <div class="col-sm-10">
                            <select name="status" class="form-select">
                                <option value="1" {{ old('status', $shelf->status) == 1 ? 'selected' : '' }}>Hoạt động</option>
                                <option value="0" {{ old('status', $shelf->status) == 0 ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.declarations.shelves.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

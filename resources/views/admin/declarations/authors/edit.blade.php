@extends('admin.index')

@section('page_title', 'Sửa Tác Giả')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.authors.index') }}">Tác giả</a></li>
    <li class="breadcrumb-item active">Sửa</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Cập nhật thông tin tác giả</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('admin.declarations.authors.update', $author->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tên Tác Giả</label>
                        <div class="col-sm-10">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $author->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Bút Danh</label>
                        <div class="col-sm-10">
                            <input type="text" name="pen_name" class="form-control @error('pen_name') is-invalid @enderror"
                                   value="{{ old('pen_name', $author->pen_name) }}" required>
                            @error('pen_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $author->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Số Điện Thoại</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $author->phone) }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Quốc Tịch</label>
                        <div class="col-sm-10">
                            <input type="text" name="nationality" class="form-control"
                                   value="{{ old('nationality', $author->nationality) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Tiểu Sử</label>
                        <div class="col-sm-10">
                            <textarea name="biography" class="form-control" rows="4">{{ old('biography', $author->biography) }}</textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-2 col-form-label">Ngày Sinh</label>
                        <div class="col-sm-10">
                            <input type="date" name="birth_date" class="form-control"
                                   value="{{ old('birth_date', $author->birth_date) }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-sm-2 col-form-label">Ngày Mất</label>
                        <div class="col-sm-10">
                            <input type="date" name="death_date" class="form-control"
                                   value="{{ old('death_date', $author->death_date) }}">
                        </div>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Cập nhật</button>
                        <a href="{{ route('admin.declarations.authors.index') }}" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

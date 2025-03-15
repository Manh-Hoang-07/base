@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa chức vụ</h2>

        <form action="{{ route('admin.declarations.positions.update', $permission->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên chức vụ</label>
                <input type="text" class="form-control" name="name" value="{{ $permission->name ?? '' }}" required>
                <label for="code" class="form-label">Mã chức vụ</label>
                <input type="text" class="form-control" name="code" value="{{ $permission->code ?? '' }}" required>
                <label for="description" class="form-label">Mô tả</label>
                <input type="text" class="form-control" name="description" value="{{ $permission->description ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

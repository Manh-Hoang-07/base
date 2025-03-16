@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm chức vụ</h2>
        <form action="{{ route('admin.declarations.positions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên chức vụ</label>
                <input type="text" class="form-control" name="name" required>
                <label for="code" class="form-label">Mã chức vụ</label>
                <input type="text" class="form-control" name="code" required>
                <label for="description" class="form-label">Mô tả</label>
                <input type="text" class="form-control" name="description">
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

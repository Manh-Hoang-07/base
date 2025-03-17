@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm khu vực</h2>
        <form action="{{ route('admin.declarations.areas.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên khu vực</label>
                <input type="text" class="form-control" name="name" required>

                <label for="code" class="form-label">Mã khu vực</label>
                <input type="text" class="form-control" name="code" required>

                <label for="type" class="form-label">Loại khu vực</label>
                <input type="text" class="form-control" name="type" required>

                <label for="location" class="form-label">Vị trí</label>
                <input type="text" class="form-control" name="location">

                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"></textarea>

                <label for="capacity" class="form-label">Sức chứa</label>
                <input type="number" class="form-control" name="capacity" min="0">

                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="1" selected>Hoạt động</option>
                    <option value="0">Không hoạt động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

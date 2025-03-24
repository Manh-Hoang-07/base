@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm series</h2>
        <form action="{{ route('admin.declarations.series.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên series</label>
                <input type="text" class="form-control" name="name" required>

                <label for="code" class="form-label">Mã series</label>
                <input type="text" class="form-control" name="code" required>

                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" name="description" rows="3"></textarea>

                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" name="status">
                    <option value="active" selected>Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

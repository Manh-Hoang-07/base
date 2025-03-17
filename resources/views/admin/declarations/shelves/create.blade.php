@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Kệ Sách</h2>
        <form action="{{ route('admin.declarations.shelves.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="area_id" class="form-label">Khu Vực</label>
                <select class="form-control" name="area_id" required>
                    @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>

                <label for="code" class="form-label">Mã Kệ Sách</label>
                <input type="text" class="form-control" name="code" required>

                <label for="name" class="form-label">Tên Kệ Sách</label>
                <input type="text" class="form-control" name="name" required>

                <label for="capacity" class="form-label">Sức Chứa</label>
                <input type="number" class="form-control" name="capacity" required>

                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" name="description"></textarea>

                <label for="status" class="form-label">Trạng Thái</label>
                <select class="form-control" name="status">
                    <option value="1">Hoạt Động</option>
                    <option value="0">Ngừng Hoạt Động</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

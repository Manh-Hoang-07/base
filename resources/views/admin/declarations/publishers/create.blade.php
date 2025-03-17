@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Nhà Xuất Bản</h2>
        <form action="{{ route('admin.declarations.publishers.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Mã Nhà Xuất Bản</label>
                <input type="text" class="form-control" name="code" required>

                <label for="name" class="form-label">Tên Nhà Xuất Bản</label>
                <input type="text" class="form-control" name="name" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email">

                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" name="phone">

                <label for="address" class="form-label">Địa Chỉ</label>
                <textarea class="form-control" name="address"></textarea>

                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control" name="website">

                <label for="established_at" class="form-label">Ngày Thành Lập</label>
                <input type="date" class="form-control" name="established_at">

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

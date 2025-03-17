@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa Nhà Xuất Bản</h2>

        <form action="{{ route('admin.declarations.publishers.update', $publisher->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="code" class="form-label">Mã Nhà Xuất Bản</label>
                <input type="text" class="form-control" name="code" value="{{ $publisher->code ?? '' }}" required>

                <label for="name" class="form-label">Tên Nhà Xuất Bản</label>
                <input type="text" class="form-control" name="name" value="{{ $publisher->name ?? '' }}" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ $publisher->email ?? '' }}">

                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" name="phone" value="{{ $publisher->phone ?? '' }}">

                <label for="address" class="form-label">Địa Chỉ</label>
                <textarea class="form-control" name="address">{{ $publisher->address ?? '' }}</textarea>

                <label for="website" class="form-label">Website</label>
                <input type="text" class="form-control" name="website" value="{{ $publisher->website ?? '' }}">

                <label for="established_at" class="form-label">Ngày Thành Lập</label>
                <input type="date" class="form-control" name="established_at" value="{{ $publisher->established_at ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

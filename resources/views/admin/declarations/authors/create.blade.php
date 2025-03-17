@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Tác Giả</h2>
        <form action="{{ route('admin.declarations.authors.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên Tác Giả</label>
                <input type="text" class="form-control" name="name" required>

                <label for="pen_name" class="form-label">Bút Danh</label>
                <input type="text" class="form-control" name="pen_name">

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>

                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" name="phone">

                <label for="nationality" class="form-label">Quốc Tịch</label>
                <input type="text" class="form-control" name="nationality">

                <label for="biography" class="form-label">Tiểu Sử</label>
                <textarea class="form-control" name="biography" rows="4"></textarea>

                <label for="birth_date" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="birth_date">

                <label for="death_date" class="form-label">Ngày Mất</label>
                <input type="date" class="form-control" name="death_date">
            </div>
            <button type="submit" class="btn btn-success">Thêm Tác Giả</button>
        </form>
    </div>
@endsection

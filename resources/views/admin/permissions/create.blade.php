@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Thêm quyền</h2>

        <form action="{{ route('admin.permissions.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Ý nghĩa quyền</label>
                <input type="text" class="form-control" name="title" required>
                <label for="name" class="form-label">Tên quyền</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection

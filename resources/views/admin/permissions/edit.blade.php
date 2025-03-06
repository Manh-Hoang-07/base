@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Sửa quyền</h2>

        <form action="{{ route('admin.permissions.update', $permission->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Ý nghĩa quyền</label>
                <input type="text" class="form-control" name="title" value="{{ $permission->title ?? '' }}" required>
                <label for="name" class="form-label">Tên quyền</label>
                <input type="text" class="form-control" name="name" value="{{ $permission->name ?? '' }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

@extends('admin.index')

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
                <label for="parent_id" class="form-label">Quyền cha</label>
                <select class="form-control select2" name="parent_id" data-url="{{ route('admin.permissions.autocomplete') }}">
                    <option value="">Chọn quyền cha</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Thêm</button>
        </form>
    </div>
@endsection


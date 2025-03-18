@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Thêm Vai Trò Mới</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Ý nghĩa vai trò</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Tên Vai Trò</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <select class="form-control select2" name="permissions[]" data-field="name" multiple
                        data-url="{{ route('admin.permissions.autocomplete') }}">
                    <option value="">Chọn quyền</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Thêm Vai Trò</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection

@extends('admin.index')

@section('content')
    <h2>Chỉnh sửa quyền - {{ $role->name ?? '' }}</h2>
    <form action="{{ route('admin.roles.update', $role->id ?? '') }}" method="POST">
        @csrf
        <input type="text" name="title" value="{{ $role->title ?? '' }}">
        <input type="text" name="name" value="{{ $role->name ?? '' }}">
        <div class="form-group">
            <select class="form-control select2" name="permissions[]" data-field="name" multiple
                    data-selected="{{ json_encode($role->permissions->pluck('name')->toArray()) }}"
                    data-url="{{ route('admin.permissions.autocomplete') }}">
                <option value="">Chọn quyền</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
@endsection

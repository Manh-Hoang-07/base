@extends('admin.home.dashboard')

@section('content')
    <h2>Chỉnh sửa quyền - {{ $role->name ?? '' }}</h2>
    <form action="{{ route('admin.roles.update', $role->id ?? '') }}" method="POST">
        @csrf
        <input type="text" name="title" value="{{ $role->title ?? '' }}">
        <input type="text" name="name" value="{{ $role->name ?? '' }}">
        <div class="form-group">
            @foreach ($permissions ?? [] as $permission)
                <div class="form-check">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name ?? '' }}"
                        {{ $role->hasPermissionTo($permission->name ?? '') ? 'checked' : '' }}>
                    <label>{{ $permission->name ?? ''}}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
@endsection

@extends('admin.home.dashboard')

@section('title', 'Gán Vai Trò')

@section('content')
    <h2>Gán Vai Trò Cho Người Dùng</h2>

    <form method="POST" action="{{ route('admin.users.assignRoles', $user->id) }}">
        @csrf

        <p><strong>Tên:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>

        <label><strong>Vai trò:</strong></label><br>
        @foreach($roles as $role)
            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                {{ in_array($role->name, $userRoles) ? 'checked' : '' }}>
            {{ $role->name }} <br>
        @endforeach

        <button type="submit">Lưu</button>
    </form>

    <a href="{{ route('admin.users.index') }}">Quay lại</a>
@endsection

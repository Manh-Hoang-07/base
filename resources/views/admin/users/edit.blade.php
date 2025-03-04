@extends('admin.home.dashboard')

@section('content')
    <div class="container">
        <h2>Gán Vai Trò Cho {{ $user->name }}</h2>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            <input type="hidden" name="name" value="{{ $user->name }}">
            <input type="hidden" name="email" value="{{ $user->email }}">
            <div class="form-group">
                <label for="roles">Vai Trò:</label>
                <select name="roles[]" id="roles" class="form-control" multiple>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}"
                                @if($user->hasRole($role->name)) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Cập Nhật</button>
        </form>
    </div>
@endsection

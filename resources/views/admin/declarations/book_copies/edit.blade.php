@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa bài đăng</h2>

        <form action="{{ route('admin.post.update', $post->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên bài đăng</label>
                <input type="text" class="form-control" name="name" value="{{ $post->name ?? '' }}" required>

            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
@endsection

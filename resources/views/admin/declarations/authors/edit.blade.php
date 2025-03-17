@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Sửa Tác Giả</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.declarations.authors.update', $author->id ?? '') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tên Tác Giả</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $author->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="pen_name" class="form-label">Bút Danh</label>
                <input type="text" class="form-control" name="pen_name" value="{{ old('pen_name', $author->pen_name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="{{ old('email', $author->email ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Số Điện Thoại</label>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $author->phone ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label for="nationality" class="form-label">Quốc Tịch</label>
                <input type="text" class="form-control" name="nationality" value="{{ old('nationality', $author->nationality ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="biography" class="form-label">Tiểu Sử</label>
                <textarea class="form-control" name="biography" rows="4">{{ old('biography', $author->biography ?? '') }}</textarea>
            </div>
            <div class="mb-3">
                <label for="birth_date" class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="birth_date" value="{{ old('birth_date', $author->birth_date ?? '') }}">
            </div>
            <div class="mb-3">
                <label for="death_date" class="form-label">Ngày Mất (nếu có)</label>
                <input type="date" class="form-control" name="death_date" value="{{ old('death_date', $author->death_date ?? '') }}">
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật</button>
        </form>
    </div>
@endsection

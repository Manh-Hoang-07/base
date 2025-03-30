@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Chi tiết Sách</h2>
        <table class="table table-bordered">
            <tr><th>ID</th><td>{{ $book->id }}</td></tr>
            <tr><th>Mã</th><td>{{ $book->code }}</td></tr>
            <tr><th>Tiêu đề</th><td>{{ $book->title }}</td></tr>
            <tr><th>ISBN</th><td>{{ $book->isbn }}</td></tr>
            <tr><th>Nhà xuất bản</th><td>{{ $book->publisher->name }}</td></tr>
            <tr><th>Ngôn ngữ</th><td>{{ $book->language }}</td></tr>
            <tr><th>Ngày xuất bản</th><td>{{ $book->published_at }}</td></tr>
            <tr><th>Trạng thái</th><td>{{ $book->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}</td></tr>
        </table>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
@endsection


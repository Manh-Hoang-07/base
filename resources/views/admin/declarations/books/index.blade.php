@extends('admin.index')

@section('content')
    <div class="container">
        <h2>Danh sách Sách</h2>
        <a href="{{ route('admin.declarations.books.create') }}" class="btn btn-success">Thêm Sách</a>

        <table class="table table-bordered mt-3">
            <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Tiêu đề</th>
                <th>Nhà xuất bản</th>
                <th>Ngôn ngữ</th>
                <th>Ngày xuất bản</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->code }}</td>
                    <td>{{ $book->name }}</td>
                    <td>{{ $book->publisher->name }}</td>
                    <td>{{ $book->language }}</td>
                    <td>{{ $book->published_at }}</td>
                    <td><span class="badge {{ $book->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                        {{ $book->status == 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                    </span></td>
                    <td>
{{--                        <a href="{{ route('admin.declarations.books.show', $book->id) }}" class="btn btn-info btn-sm">Xem</a>--}}
                        <a href="{{ route('admin.declarations.books.edit', $book->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                        <form action="{{ route('admin.declarations.books.delete', $book->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

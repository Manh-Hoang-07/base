@extends('admin.index')

@section('page_title', 'Quản lý bản sao sách')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Bản sao sách</li>
@endsection

@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <form action="{{ route('admin.declarations.book_copies.index') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <input type="text" name="name" class="form-control" placeholder="Tìm theo tên sách"
                                                   value="{{ request('name') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('admin.declarations.book_copies.index') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                @canany(['manage_declarations', 'create_declarations'])
                                    <a href="{{ route('admin.declarations.book_copies.create') }}" class="btn btn-success ms-auto">Thêm bản sao</a>
                                @endcanany
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Mã sách</th>
                                    <th>Số thứ tự</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bookCopies as $index => $copy)
                                    <tr>
                                        <td>{{ $bookCopies->firstItem() + $index }}</td>
                                        <td>{{ $copy->book->code ?? 'N/A' }}</td>
                                        <td>{{ $copy->copy_number }}</td>
                                        <td>
                                            <span class="badge {{ $copy->status === 'available' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $copy->status === 'available' ? 'Có sẵn' : 'Đang mượn' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.book_copies.edit', $copy->id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.book_copies.destroy', $copy->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa"
                                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        @include('vendor.pagination.pagination', ['paginator' => $bookCopies])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

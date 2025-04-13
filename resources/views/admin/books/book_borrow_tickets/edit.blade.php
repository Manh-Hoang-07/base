@extends('admin.index')

@section('title', 'Cập nhật vai trò')
@section('page_title', 'Cập nhật vai trò')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Cập nhật vai trò</li>
@endsection

@section('content')
    <div class="container">
        <h2 class="mb-4">Chỉnh sửa phiếu mượn #{{ $ticket->id }}</h2>

        <form action="{{ route('admin.book-borrow-tickets.update', $ticket->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Người mượn</label>
                <input type="text" class="form-control" value="{{ $ticket->user->name }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày mượn</label>
                <input type="date" name="borrowed_at" class="form-control" value="{{ $ticket->borrowed_at }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Hạn trả</label>
                <input type="date" name="due_at" class="form-control" value="{{ $ticket->due_at }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ghi chú</label>
                <textarea name="note" class="form-control">{{ $ticket->note }}</textarea>
            </div>

            <hr>
            <p><strong>📌 Ghi chú:</strong> Không sửa được danh sách sách trong phiếu mượn tại đây (nếu cần, phải hủy phiếu và tạo lại).</p>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('admin.book-borrow-tickets.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
@endsection

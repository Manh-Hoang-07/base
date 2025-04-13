@extends('admin.index')

@section('page_title', 'Tạo phiếu mượn sách')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('test') }}">Danh sách phiếu mượn</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tạo phiếu mượn sách</li>
@endsection

@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Tạo phiếu mượn sách</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('test2') }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Người mượn</label>
                                    <select class="form-control select2 @error('user_id') is-invalid @enderror"
                                            name="user_id"
                                            data-display-field="email"
                                            data-url="{{ route('admin.users.autocomplete') }}">
                                        <option value="">Chọn người mượn</option>
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Ngày mượn</label>
                                    <input type="date" name="borrowed_at" class="form-control" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Hạn trả</label>
                                    <input type="date" name="due_at" class="form-control" required>
                                </div>
                            </div>

                            <hr>

                            <h5 class="mb-3">Danh sách sách mượn</h5>

                            <table class="table table-bordered" id="books-table">
                                <thead>
                                <tr>
                                    <th width="60%">Sách</th>
                                    <th width="25%">Số lượng</th>
                                    <th width="15%"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2 @error('books[0][book_id]') is-invalid @enderror"
                                                name="books[0][book_id]"
                                                data-url="{{ route('admin.declarations.books.autocomplete') }}">
                                            <option value="">Chọn sách</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="books[0][quantity]" class="form-control" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-remove">X</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <button type="button" class="btn btn-secondary mb-3" id="add-book">+ Thêm sách</button>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Tạo phiếu</button>
                                <a href="{{ route('test') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            // Thêm sách
            let bookIndex = 1;
            $('#add-book').click(function () {
                const row = `
                    <tr>
                        <td>
                            <select name="books[${bookIndex}][book_id]" class="form-control select2" data-url="{{ route('admin.declarations.books.autocomplete') }}">
                                <option value="">-- Chọn sách --</option>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="books[${bookIndex}][quantity]" class="form-control" value="1" min="1" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-remove">X</button>
                        </td>
                    </tr>
                `;
                $('#books-table tbody').append(row);
                bookIndex++;
            });

            // Xóa sách
            $(document).on('click', '.btn-remove', function () {
                $(this).closest('tr').remove();
            });
        });
    </script>
@endsection

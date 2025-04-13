@extends('admin.index')

@section('page_title', 'Danh sách phiếu mượn sách')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">Danh sách phiếu mượn sách</li>
@endsection

@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <!-- Form lọc -->
                                <form action="{{ route('test') }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" name="user_name" class="form-control" placeholder="Tên người mượn"
                                                   value="{{ request('user_name') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="date" name="borrowed_at" class="form-control" placeholder="Ngày mượn"
                                                   value="{{ request('borrowed_at') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Lọc</button>
                                            <a href="{{ route('test') }}" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-sm-3 d-flex">
                                <a href="{{ route('test1') }}" class="btn btn-primary ms-auto">+ Tạo phiếu mượn</a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Người mượn</th>
                                <th>Ngày mượn</th>
                                <th>Hạn trả</th>
                                <th>Ghi chú</th>
                                <th>Thao tác</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tickets as $index => $ticket)
                                <tr>
                                    <td>{{ $tickets->firstItem() + $index }}</td>
                                    <td>{{ $ticket->user->name ?? '---' }}</td>
                                    <td>{{ $ticket->borrowed_at }}</td>
                                    <td>{{ $ticket->due_at }}</td>
                                    <td>{{ $ticket->note }}</td>
                                    <td>
                                        <a href="{{ route('admin.ticket-borrows.edit', $ticket->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.ticket-borrows.destroy', $ticket->id) }}" method="POST"
                                              style="display:inline-block;"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Hiển thị phân trang -->
                    @include('vendor.pagination.pagination', ['paginator' => $tickets])
                </div>
            </div>
        </div>
    </div>
@endsection

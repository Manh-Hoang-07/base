@extends('admin.index')

@section('page_title', 'Sửa chức vụ')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.declarations.positions.index') }}">Chức vụ</a></li>
    <li class="breadcrumb-item active" aria-current="page">Sửa chức vụ</li>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa chức vụ</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.declarations.positions.update', $position->id ?? '') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên chức vụ</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name', $position->name ?? '') }}" required>

                        <label for="code" class="form-label mt-3">Mã chức vụ</label>
                        <input type="text" class="form-control" name="code" value="{{ old('code', $position->code ?? '') }}" required>

                        <label for="description" class="form-label mt-3">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $position->description ?? '') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.declarations.positions.index') }}" class="btn btn-secondary">Quay lại</a>
                </form>
            </div>
        </div>
    </div>
@endsection

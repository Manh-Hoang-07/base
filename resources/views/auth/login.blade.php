@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
        @error('email')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <input type="password" class="form-control" id="password" name="password">
        @error('password')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
</form>

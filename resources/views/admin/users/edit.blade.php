@extends('admin.index')

@section('title', 'Chỉnh sửa tài khoản')

@section('page_title', 'Chỉnh sửa tài khoản')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Danh sách tài khoản</a></li>
    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa tài khoản</li>
@endsection

@section('content')
    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <form id="user-edit-form" data-api-url="/api/v1/admin/users/update/{{ $user->id }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Nhập email..." value="{{ $user->email }}" required>
                                        @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                                        <input type="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới...">
                                        @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu mới...">
                                        @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-success" id="submit-btn">
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                            Cập nhật
                                        </button>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
                                    </div>
                                </div>
                            </div> <!-- row -->
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('user-edit-form');
    const submitBtn = document.getElementById('submit-btn');
    const spinner = submitBtn.querySelector('.spinner-border');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Disable button and show spinner
        submitBtn.disabled = true;
        spinner.classList.remove('d-none');

        // Clear previous errors
        document.querySelectorAll('.text-danger').forEach(el => el.remove());
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const formData = new FormData(form);
            const apiUrl = form.dataset.apiUrl;

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            const response = await fetch(apiUrl, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show success message
                if (typeof toastr !== 'undefined') {
                    toastr.success(data.message || 'Cập nhật tài khoản thành công!');
                } else {
                    alert(data.message || 'Cập nhật tài khoản thành công!');
                }

                // Redirect to index page
                setTimeout(() => {
                    window.location.href = '{{ route("admin.users.index") }}';
                }, 1500);
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');

                            // Create error message
                            const errorDiv = document.createElement('span');
                            errorDiv.className = 'text-danger';
                            errorDiv.textContent = data.errors[field][0];
                            input.parentNode.appendChild(errorDiv);
                        }
                    });
                }

                if (typeof toastr !== 'undefined') {
                    toastr.error(data.message || 'Có lỗi xảy ra!');
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            if (typeof toastr !== 'undefined') {
                toastr.error('Có lỗi xảy ra khi xử lý yêu cầu!');
            } else {
                alert('Có lỗi xảy ra khi xử lý yêu cầu!');
            }
        } finally {
            // Re-enable button and hide spinner
            submitBtn.disabled = false;
            spinner.classList.add('d-none');
        }
    });
});
</script>
@endsection

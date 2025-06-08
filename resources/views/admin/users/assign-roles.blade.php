@extends('admin.index')

@section('title', 'Gán Vai Trò')

@section('page_title', 'Gán Vai Trò Cho Người Dùng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Tài khoản</a></li>
    <li class="breadcrumb-item active" aria-current="page">Gán vai trò</li>
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
                        <form id="assign-roles-form" data-api-url="/api/v1/admin/users/roles/{{ $user->id }}" method="POST">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"><strong>Email:</strong></label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><strong>Vai trò:</strong></label>
                                <select class="form-control select2" name="roles[]"
                                        multiple
                                        data-selected='@json($userRoles ?? [])'
                                        data-url="{{ route('admin.roles.autocomplete') }}">
                                    <option value="">Chọn vai trò</option>
                                    {{-- Pre-populate selected roles --}}
                                    @if(isset($userRoles) && is_array($userRoles))
                                        @foreach($userRoles as $roleName)
                                            @php
                                                $role = \Spatie\Permission\Models\Role::where('name', $roleName)->first();
                                            @endphp
                                            @if($role)
                                                <option value="{{ $role->name }}" selected>{{ $role->title ?? $role->name }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('roles')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submit-btn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    Lưu
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
                            </div>
                        </form>
                    </div>
                </div>
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
    const form = document.getElementById('assign-roles-form');
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
                method: 'POST',
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
                    toastr.success(data.message || 'Gán vai trò thành công!');
                } else {
                    alert(data.message || 'Gán vai trò thành công!');
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

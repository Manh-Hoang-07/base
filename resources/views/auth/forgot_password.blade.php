<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
<div class="container">
    <div class="card p-4 shadow-lg" style="max-width: 400px; margin: auto;">
        <h3 class="text-center">Quên Mật Khẩu</h3>

        <!-- Form nhập email -->
        <div id="step1">
            <input type="email" id="email" class="form-control mt-2" placeholder="Nhập email">
            <button class="btn btn-primary mt-2 w-100" id="sendOtp">Gửi OTP</button>
            <p class="text-success mt-2" id="message"></p>
        </div>

        <!-- Form nhập OTP -->
        <div id="step2" class="d-none">
            <input type="text" id="otp" class="form-control mt-2" placeholder="Nhập OTP">
            <input type="password" id="password" class="form-control mt-2" placeholder="Mật khẩu mới">
            <input type="password" id="password_confirmation" class="form-control mt-2" placeholder="Xác nhận mật khẩu">
            <button class="btn btn-success mt-2 w-100" id="resetPassword">Đặt lại mật khẩu</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        // Gửi OTP
        $('#sendOtp').click(function() {
            let data = {
                'email': $('#email').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'), // Lấy CSRF token từ meta tag
            };
            $.post('{{ route('send.forgot.password') }}', data, function(response) {
                $('#message').text(response.message);
                $('#step1').hide();
                $('#step2').removeClass('d-none');
            }).fail(function(err) {
                alert(err.responseJSON.message);
            });
        });

        // Đặt lại mật khẩu
        $('#resetPassword').click(function() {
            let data = {
                'email': $('#email').val(),
                'password': $('#password').val(),
                'password_confirmation': $('#password_confirmation').val(),
                '_token': $('meta[name="csrf-token"]').attr('content'), // Lấy CSRF token từ meta tag
                'otp': $('#otp').val()
            };
            $.post('{{ route('reset.password') }}', data, function(response) {
                alert(response.message);
                window.location.href = '{{ route('login') }}';
            }).fail(function(err) {
                alert(err.responseJSON.message);
            });
        });
    });
</script>
</body>
</html>

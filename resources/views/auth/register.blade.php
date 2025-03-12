<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đăng Ký</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 400px;
        }
        .register-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        .register-container .form-group label {
            font-weight: bold;
        }
        .register-container .btn {
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            padding: 10px;
            transition: background-color 0.3s ease;
        }
        .register-container .btn:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2 class="text-center">Đăng Ký</h2>
    <form id="register_form">
        @csrf
        <div class="form-group">
            <label for="name">Tên:</label>
            <input type="text" name="name" id="name" class="form-control" required>
            <small class="error-message" id="name-error"></small>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" class="form-control" required>
            <small class="error-message" id="email-error"></small>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu:</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <small class="error-message" id="password-error"></small>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Xác nhận mật khẩu:</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            <small class="error-message" id="password_confirmation-error"></small>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#register_form').on('submit', function(event) {
            event.preventDefault();


            $('.error-message').text('');

            let isValid = true;
            let name = $('#name').val().trim();
            let email = $('#email').val().trim();
            let password = $('#password').val();
            let passwordConfirmation = $('#password_confirmation').val();

            if (name === '') {
                $('#name-error').text('Tên không được để trống');
                isValid = false;
            }

            if (email === '') {
                $('#email-error').text('Email không được để trống');
                isValid = false;
            } else if (!/\S+@\S+\.\S+/.test(email)) {
                $('#email-error').text('Email không hợp lệ');
                isValid = false;
            }

            if (password === '') {
                $('#password-error').text('Mật khẩu không được để trống');
                isValid = false;
            } else if (password.length < 6 || password.length > 16) {
                $('#password-error').text('Mật khẩu phải có từ 6 đến 16 ký tự');
                isValid = false;
            }

            if (password !== passwordConfirmation) {
                $('#password_confirmation-error').text('Mật khẩu xác nhận không khớp');
                isValid = false;
            }

            if (!isValid) {
                return;
            }

            $.ajax({
                url: '{{ route('register') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if(response.status === true) {
                        toastr.success(response.message || 'Đăng ký tài khoản thành công');
                        setTimeout(function() {
                            window.location.href = "{{ url('/') }}";
                        }, 3000);
                    } else {
                        toastr.error(response.message || 'Đăng ký tài khoản thất bại');
                    }
                },
                error: function(xhr) {
                    let message = xhr.responseJSON.message || 'Đăng ký thất bại';
                    toastr.error(message);
                }
            });
        });

        @if(session('error'))
        toastr.error("{{ session('error') }}");
        @endif

        @if(session('success'))
        toastr.success("{{ session('success') }}");
        @endif
    });
</script>
</body>
</html>

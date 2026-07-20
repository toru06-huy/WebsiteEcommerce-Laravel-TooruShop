@extends('layouts.client')
@section('title', 'Đăng nhập – VELOUR')

@push('styles')
    <style>
        /* Đảm bảo đồng bộ hệ thống biến màu sắc */
        :root {
            --cream: #f9f6f0;
            --white: #ffffff;
            --black: #111111;
            --gray: #767676;
            --border: #e5e5e5;
            --gold: #bc9c6a;
            --transition: all 0.3s ease;
            --font-display: 'Playfair Display', serif;
            --font-body: 'Montserrat', sans-serif;
        }

        .auth-page {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 24px;
            background: var(--cream);
        }

        .auth-box {
            background: var(--white);
            width: 100%;
            max-width: 520px;
            /* Đồng bộ kích thước rộng 520px với Register */
            padding: 50px 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            /* Đổ bóng nhẹ sang trọng */
            border-radius: 2px;
        }

        .auth-logo {
            display: block;
            text-align: center;
            font-family: var(--font-display);
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 6px;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: var(--black);
            text-decoration: none;
        }

        .auth-logo span {
            color: var(--gold);
        }

        .auth-subtitle {
            text-align: center;
            font-size: 12px;
            color: var(--gray);
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .auth-title {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 32px;
            color: var(--black);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border);
            font-size: 13px;
            outline: none;
            transition: var(--transition);
            font-family: var(--font-body);
            background-color: #fafafa;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--black);
            background-color: var(--white);
        }

        .form-control.error {
            border-color: #c0392b;
        }

        /* Định dạng thông báo Alert tinh tế hơn */
        .auth-error {
            background: rgba(192, 57, 43, .04);
            border: 1px solid rgba(192, 57, 43, .15);
            padding: 14px 16px;
            font-size: 13px;
            color: #c0392b;
            margin-bottom: 24px;
            border-radius: 2px;
        }

        .auth-success {
            background: rgba(39, 174, 96, .04);
            border: 1px solid rgba(39, 174, 96, .15);
            padding: 14px 16px;
            font-size: 13px;
            color: #27ae60;
            margin-bottom: 24px;
            border-radius: 2px;
        }

        /* Hàng ghi nhớ đăng nhập */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .remember-row input {
            accent-color: var(--black);
            width: 15px;
            height: 15px;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 13px;
            color: var(--gray);
            cursor: pointer;
            user-select: none;
        }

        /* Nút bấm Premium đồng bộ */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--black);
            color: var(--white);
            border: 1px solid var(--black);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background: var(--gold);
            border-color: var(--gold);
        }

        /* Phần chân trang chuyển hướng dạng Flex ngang sang trọng */
        .auth-footer-group {
            margin-top: 32px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .auth-link-register {
            color: var(--gray);
            text-decoration: none;
        }

        .auth-link-register a {
            color: var(--black);
            font-weight: 600;
            text-decoration: underline;
        }

        .auth-link-register a:hover {
            color: var(--gold);
        }

        .auth-link-back {
            color: var(--gray);
            text-decoration: none;
            transition: var(--transition);
        }

        .auth-link-back:hover {
            color: var(--black);
        }
    </style>
@endpush

@section('content')
    <div class="auth-page">
        <div class="auth-box">
            <a href="{{ route('client.home') }}" class="auth-logo">VEL<span>O</span>UR</a>
            <p class="auth-subtitle">Thời trang cao cấp Việt Nam</p>

            @if (session('info'))
                <div class="auth-success">
                    <i class="fa-solid fa-circle-info" style="margin-right:8px;"></i>{{ session('info') }}
                </div>
            @endif
            @if (session('success'))
                <div class="auth-success">
                    <i class="fa-solid fa-circle-check" style="margin-right:8px;"></i>{{ session('success') }}
                </div>
            @endif
            @if ($errors->has('email'))
                <div class="auth-error">
                    <i class="fa-solid fa-circle-xmark" style="margin-right:8px;"></i>{{ $errors->first('email') }}
                </div>
            @endif
            @if ($errors->has('forgot_email'))
                <div class="auth-error">
                    <i class="fa-solid fa-circle-xmark" style="margin-right:8px;"></i>{{ $errors->first('forgot_email') }}
                </div>
            @endif

            <div id="login-form-wrapper">
                <h2 class="auth-title">Đăng nhập</h2>
                <form method="POST" action="{{ route('client.login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="login">Email hoặc số điện thoại</label>
                        <input type="text" name="login" id="login"
                            class="form-control {{ $errors->has('login') ? 'error' : '' }}" value="{{ old('login') }}"
                            placeholder="email@example.com hoặc 0912345678" autocomplete="username" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật khẩu</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="••••••••"
                            autocomplete="current-password" required>
                    </div>

                    <div class="remember-row" style="justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="#" id="btn-show-forgot"
                            style="font-size: 13px; color: var(--gray); text-decoration: none;">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn-submit">Đăng nhập</button>
                </form>
            </div>

            <div id="forgot-form-wrapper" style="display: none;">
                <h2 class="auth-title">Khôi phục mật khẩu</h2>
                <p style="font-size: 13px; text-align: center; color: var(--gray); margin-bottom: 24px;">
                    Nhập email của bạn. Hệ thống sẽ cấp lại một mật khẩu ngẫu nhiên mới gửi về hộp thư này.
                </p>

                <form method="POST" action="{{ route('client.forgot-password.post') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="forgot_email">Email của bạn</label>
                        <input type="email" name="forgot_email" id="forgot_email"
                            class="form-control {{ $errors->has('forgot_email') ? 'error' : '' }}"
                            placeholder="email@example.com" required>
                    </div>

                    <button type="submit" class="btn-submit" style="margin-bottom: 15px;">Gửi mật khẩu mới</button>
                    <button type="button" id="btn-hide-forgot" class="btn-submit"
                        style="background: transparent; color: var(--black); border-color: var(--border);">Hủy bỏ</button>
                </form>
            </div>

            <div class="auth-footer-group">
                <div class="auth-link-register">
                    Chưa có tài khoản? <a href="{{ route('client.register.verify') }}">Đăng ký ngay</a>
                </div>
                <a href="{{ route('client.home') }}" class="auth-link-back">
                    <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Quay về trang chủ
                </a>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('btn-show-forgot').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form-wrapper').style.display = 'none';
            document.getElementById('forgot-form-wrapper').style.display = 'block';
        });

        document.getElementById('btn-hide-forgot').addEventListener('click', function() {
            document.getElementById('forgot-form-wrapper').style.display = 'none';
            document.getElementById('login-form-wrapper').style.display = 'block';
        });

        // Giữ lại form quên mật khẩu nếu có lỗi submit riêng của form này
        @if ($errors->has('forgot_email'))
            document.getElementById('login-form-wrapper').style.display = 'none';
            document.getElementById('forgot-form-wrapper').style.display = 'block';
        @endif
    </script>
@endsection

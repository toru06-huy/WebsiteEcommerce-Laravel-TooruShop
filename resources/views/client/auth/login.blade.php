@extends('layouts.client')
@section('title', 'Đăng nhập – VELOUR')

@push('styles')
    @vite(['resources/css/client/auth/login.css'])
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

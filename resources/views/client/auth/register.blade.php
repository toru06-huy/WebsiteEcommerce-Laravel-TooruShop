@extends('layouts.client')
@section('title', 'Đăng ký – VELOUR')

@push('styles')
    <style>
        /* Reset nhẹ & Biến màu sắc nếu layout tổng thể chưa có */
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
            /* Tăng nhẹ để form thông thoáng hơn */
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

        /* Định dạng Grid bao quát toàn bộ ô nhập liệu */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 0;
            /* Để Grid tự quản lý khoảng cách qua gap */
        }

        /* Các ô chiếm trọn 100% chiều ngang */
        .form-group.full {
            grid-column: span 2;
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

        .form-error {
            font-size: 11px;
            color: #c0392b;
            margin-top: 6px;
        }

        /* Tối ưu nút bấm chuẩn Premium */
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
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: var(--gold);
            border-color: var(--gold);
        }

        /* Phần chân trang chuyển hướng */
        .auth-footer-group {
            margin-top: 32px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .auth-link-login {
            color: var(--gray);
            text-decoration: none;
        }

        .auth-link-login a {
            color: var(--black);
            font-weight: 600;
            text-decoration: underline;
        }

        .auth-link-login a:hover {
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
            <h2 class="auth-title">Tạo tài khoản</h2>

            <form method="POST" action="{{ route('client.register.post') }}">
                @csrf

                <div class="form-grid">

                    <div class="form-group full">
                        <label class="form-label" for="fullName">Họ và tên <span style="color:#c0392b;">*</span></label>
                        <input type="text" name="fullName" id="fullName"
                            class="form-control {{ $errors->has('fullName') ? 'error' : '' }}" value="{{ old('fullName') }}"
                            placeholder="Nguyễn Văn A" required>
                        @error('fullName')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="email">Email <span style="color:#c0392b;">*</span></label>
                        <input type="email" name="email" id="email" class="form-control"
                            value="{{ request()->query('email') }}" readonly required>
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group full">
                        <label class="form-label" for="phone">Số điện thoại <span style="color:#c0392b;">*</span></label>
                        <input type="tel" name="phone" id="phone"
                            class="form-control {{ $errors->has('phone') ? 'error' : '' }}" value="{{ old('phone') }}"
                            placeholder="0901 234 567" required>
                        @error('phone')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="sex">Giới tính</label>
                        <select name="sex" id="sex"
                            class="form-control {{ $errors->has('sex') ? 'error' : '' }}">
                            <option value="">-- Chọn --</option>
                            <option value="Nam" {{ old('sex') === 'Nam' ? 'selected' : '' }}>Nam</option>
                            <option value="Nữ" {{ old('sex') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                            <option value="Khác" {{ old('sex') === 'Khác' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('sex')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="birthday">Ngày sinh</label>
                        <input type="date" name="birthday" id="birthday"
                            class="form-control {{ $errors->has('birthday') ? 'error' : '' }}"
                            value="{{ old('birthday') }}" max="{{ date('Y-m-d') }}">
                        @error('birthday')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">Mật khẩu <span style="color:#c0392b;">*</span></label>
                        <input type="password" name="password" id="password"
                            class="form-control {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Tối thiểu 6 ký tự" required>
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Xác nhận mật khẩu <span
                                style="color:#c0392b;">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"
                            placeholder="Nhập lại" required>
                    </div>

                </div> <button type="submit" class="btn-submit">
                    Tạo tài khoản
                </button>
            </form>

            <div class="auth-footer-group">
                <div class="auth-link-login">
                    Đã có tài khoản? <a href="{{ route('client.login') }}">Đăng nhập</a>
                </div>
                <a href="{{ route('client.home') }}" class="auth-link-back">
                    <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Quay về trang chủ
                </a>
            </div>

        </div>
    </div>
@endsection

@extends('layouts.client')
@section('title', 'Đăng ký – VELOUR')

@push('styles')
    @vite(['resources/css/client/auth/register.css'])
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

@extends('layouts.client')
@section('title', 'Xác thực Email – VELOUR')

@push('styles')
    @vite(['resources/css/client/auth/verify-email.css'])
@endpush

@section('content')
<div class="auth-page">
  <div class="auth-box">
    <a href="{{ route('client.home') }}" class="auth-logo">VEL<span>O</span>UR</a>
    <p class="auth-subtitle">Thời trang cao cấp Việt Nam</p>
    <h2 class="auth-title">Xác thực Email</h2>

    <div id="ajaxAlert" class="ajax-alert"></div>

    <form id="otpVerifyForm">
      <div class="form-group">
        <label class="form-label" for="otpEmail">Địa chỉ Email <span style="color:#c0392b;">*</span></label>
        <div class="otp-input-group">
            <input type="email" id="otpEmail" class="form-control" placeholder="email@example.com" required>
            <button type="button" id="btnSendOtp" class="btn-send-otp">Gửi mã</button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="otpCode">Mã xác thực (6 số) <span style="color:#c0392b;">*</span></label>
        <input type="text" id="otpCode" class="form-control" placeholder="••••••" maxlength="6" required>
      </div>

      <button type="button" id="btnVerifyOtp" class="btn-submit">
        Xác thực & Tiếp tục
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnSendOtp = document.getElementById('btnSendOtp');
    const btnVerifyOtp = document.getElementById('btnVerifyOtp');
    const otpEmailInput = document.getElementById('otpEmail');
    const otpCodeInput = document.getElementById('otpCode');
    const ajaxAlert = document.getElementById('ajaxAlert');

    function showAlert(type, text) {
        ajaxAlert.className = `ajax-alert ${type}`;
        ajaxAlert.innerHTML = `<i class="fa-solid ${type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark'}" style="margin-right:8px;"></i>${text}`;
        ajaxAlert.style.display = 'block';
    }

    // 1. Logic Gửi Mã OTP (30 giây cooldown chống spam)
    btnSendOtp.addEventListener('click', function () {
        const email = otpEmailInput.value.trim();
        if (!email || !email.includes('@')) {
            showAlert('error', 'Vui lòng nhập địa chỉ email hợp lệ.');
            return;
        }

        btnSendOtp.disabled = true;
        showAlert('success', 'Đang gửi mã OTP đến email của bạn...');

        fetch("{{ route('client.otp.send') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email: email })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message);
                
                // Bắt đầu đếm ngược 30 giây
                let countdown = 30;
                btnSendOtp.innerText = `${countdown}s`;
                const timer = setInterval(() => {
                    countdown--;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        btnSendOtp.innerText = 'Gửi mã';
                        btnSendOtp.disabled = false;
                    } else {
                        btnSendOtp.innerText = `${countdown}s`;
                    }
                }, 1000);
            } else {
                showAlert('error', data.message);
                btnSendOtp.disabled = false;
            }
        })
        .catch(err => {
            showAlert('error', 'Có lỗi hệ thống khi gửi mã. Vui lòng thử lại.');
            btnSendOtp.disabled = false;
        });
    });

    // 2. Logic Xác Thực Mã OTP
    btnVerifyOtp.addEventListener('click', function () {
        const email = otpEmailInput.value.trim();
        const otp = otpCodeInput.value.trim();

        if (!email || otp.length !== 6) {
            showAlert('error', 'Vui lòng nhập đầy đủ Email và mã OTP gồm 6 chữ số.');
            return;
        }

        fetch("{{ route('client.otp.verify') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ email: email, otp: otp })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Xác thực thành công! Đang chuyển hướng sang trang đăng ký...');
                
                // Chuyển hướng sang route Đăng ký kèm theo tham số email trên URL query string
                setTimeout(() => {
                    window.location.href = "{{ route('client.register') }}?email=" + encodeURIComponent(email);
                }, 1200);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(err => {
            showAlert('error', 'Xác thực thất bại, vui lòng thử lại.');
        });
    });
});
</script>
@endsection
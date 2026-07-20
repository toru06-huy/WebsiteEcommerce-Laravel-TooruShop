@extends('layouts.client')
@section('title', 'Xác thực Email – VELOUR')

@push('styles')
<style>
/* Đồng bộ hệ thống biến màu sắc Premium của VELOUR */
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
    padding: 50px 40px; 
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); 
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
.auth-logo span { color: var(--gold); }

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

/* Group ô nhập Email và nút Gửi mã trên một hàng */
.otp-input-group {
    display: flex;
    gap: 12px;
}

.btn-send-otp {
    white-space: nowrap;
    padding: 0 24px;
    background: var(--white);
    color: var(--black);
    border: 1px solid var(--black);
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    cursor: pointer;
    transition: var(--transition);
}

.btn-send-otp:hover:not(:disabled) {
    background: var(--black);
    color: var(--white);
}

.btn-send-otp:disabled {
    border-color: var(--border);
    color: var(--gray);
    cursor: not-allowed;
}

/* Alert thông báo từ AJAX phản hồi nhanh */
.ajax-alert {
    padding: 14px 16px; 
    font-size: 13px; 
    margin-bottom: 24px; 
    border-radius: 2px;
    display: none;
}
.ajax-alert.error {
    background: rgba(192,57,43,.04); 
    border: 1px solid rgba(192,57,43,.15); 
    color: #c0392b;
}
.ajax-alert.success {
    background: rgba(39,174,96,.04); 
    border: 1px solid rgba(39,174,96,.15); 
    color: #27ae60;
}

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
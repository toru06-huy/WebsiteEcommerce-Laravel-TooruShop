@component('mail::message')
# XÁC THỰC EMAIL ĐĂNG KÝ

Cảm ơn bạn đã lựa chọn **VELOUR** – Thời trang cao cấp Việt Nam. 

Để hoàn tất bước xác thực và tiếp tục quy trình tạo tài khoản thành viên, vui lòng sử dụng mã OTP gồm 6 chữ số dưới đây:

@component('mail::panel')
<div style="text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #bc9c6a; margin: 10px 0;">
    {{ $otpCode }}
</div>
@endcomponent

<p style="font-size: 13px; color: #767676; text-align: center;">
    * Mã xác thực này có hiệu lực trong vòng <strong>5 phút</strong> và chỉ được sử dụng một lần duy nhất. Vì lý do bảo mật, vui lòng không chia sẻ mã này cho bất kỳ ai.
</p>

Nếu bạn không thực hiện yêu cầu này, bạn có thể an tâm bỏ qua email này.

Trân trọng,<br>
**Đội ngũ VELOUR**
@endcomponent
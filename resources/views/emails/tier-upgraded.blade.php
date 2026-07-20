@component('mail::message')
# CHÚC MỪNG SỰ THĂNG HẠNG CỦA BẠN

Xin chào **{{ $user->fullName ?? $user->name }}**,

**VELOUR** xin trân trọng chúc mừng bạn đã chính thức ghi danh vào phân hạng thành viên **{{ strtoupper($newTier) }}**. Đây là lời tri ân sâu sắc cho sự đồng hành và phong cách thời trang tuyệt vời mà bạn đã chia sẻ cùng chúng tôi trong suốt thời gian qua.

Để ghi dấu cột mốc đặc biệt này, VELOUR xin gửi tặng bạn một đặc quyền ưu đãi độc bản:

@component('mail::panel')
<div style="text-align: center; margin: 10px 0;">
    <p style="font-size: 14px; color: #767676; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;">Mã Giảm Giá Ưu Đãi Hạng {{ $newTier }}</p>
    <div style="font-size: 32px; font-weight: bold; letter-spacing: 4px; color: #111111; margin-bottom: 10px;">
        {{ $code }}
    </div>
    <p style="font-size: 16px; color: #bc9c6a; font-weight: 600; margin: 0;">
        GIẢM NGAY {{ $discountValue }}% CHO ĐƠN HÀNG TIẾP THEO
    </p>
</div>
@endcomponent

@component('mail::button', ['url' => route('client.home')])
Khám Phá Bộ Sưu Tập Mới
@endcomponent

<p style="font-size: 13px; color: #767676; text-align: center;">
    * Hạn sử dụng mã ưu đãi: đến hết ngày <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>.<br>
    Mã ưu đãi áp dụng cá nhân cho 01 lần sử dụng và không áp dụng đồng thời với các chương trình khác.
</p>

Một lần nữa, cảm ơn bạn đã trở thành một phần tinh tế trong câu chuyện của VELOUR. Chúc bạn sẽ có thêm nhiều trải nghiệm mua sắm đẳng cấp sắp tới.

Trân trọng,<br>
**Đội ngũ VELOUR**
@endcomponent
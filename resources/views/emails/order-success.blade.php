@component('mail::message')
# CẢM ƠN BẠN ĐÃ ĐẶT HÀNG!

Xin chào **{{ $order->name }}**,

Đơn hàng của bạn đã được ghi nhận thành công trên hệ thống của **VELOUR**. Chúng tôi đang chuẩn bị để đóng gói và giao những sản phẩm tinh tế nhất đến tay bạn trong thời gian sớm nhất.

---

### 📌 THÔNG TIN GIAO HÀNG & THANH TOÁN
* **Mã đơn hàng:** #{{ $order->orderID }}
* **Khách hàng:** {{ $order->name }}
* **Số điện thoại:** {{ $order->phone }}
* **Địa chỉ nhận hàng:** {{ $order->shippingAddress }}
* **Hình thức:** {{ str_replace("\n", " | ", $order->payment) }}

---

### 📦 DANH SÁCH SẢN PHẨM ĐẶT MUA

@component('mail::table')
| Sản phẩm | Số lượng | Đơn giá | Thành tiền |
| :--- | :---: | :---: | :---: |
@foreach($cartItems as $item)
| {{ $item['productName'] }}<br><small style="color:#767676;">Size: {{ $item['sizeName'] }} / Màu: {{ $item['colorName'] }}</small> | {{ $item['quantity'] }} | {{ number_format($item['price'], 0, ',', '.') }}đ | {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ |
@endforeach
@endcomponent

<div style="border-top: 1px solid #e5e5e5; padding-top: 15px; margin-top: 15px; text-align: right; font-size: 14px; line-height: 1.6;">
Tạm tính: <strong>{{ number_format($order->totalAmount, 0, ',', '.') }}đ</strong><br>
Giảm giá: <span style="color:#c0392b;">-{{ number_format($order->discountAmount, 0, ',', '.') }}đ</span><br>
Tiền ship: <strong>{{ number_format($shippingFee, 0, ',', '.') }}đ</strong><br>
Tổng thanh toán: <strong style="font-size: 18px; color: #bc9c6a;">{{ number_format($order->finalAmount, 0, ',', '.') }}đ</strong>
</div>

---

@component('mail::button', ['url' => route('client.home')])
Tiếp tục mua sắm cùng VELOUR
@endcomponent

Nếu bạn có bất kỳ thắc mắc nào liên quan đến đơn hàng này, vui lòng liên hệ trực tiếp với bộ phận Chăm sóc khách hàng của chúng tôi để được hỗ trợ nhanh nhất.

Trân trọng,<br>
**Đội ngũ VELOUR**
@endcomponent
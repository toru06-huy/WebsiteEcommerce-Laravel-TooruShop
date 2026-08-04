@extends('layouts.client')
@section('title', 'Thông tin nhận hàng – VELOUR')

@push('styles')
  @vite(['resources/css/client/checkout/shipping.css'])
@endpush

@section('content')
<div class="checkout-bar">
  <div class="container">
    <div class="checkout-steps">
      <div class="step done">
        <div class="step-num"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
        <div class="step-label">Giỏ hàng</div>
      </div>
      <div class="step-sep"></div>
      <div class="step active">
        <div class="step-num">2</div>
        <div class="step-label">Thông tin nhận hàng</div>
      </div>
      <div class="step-sep"></div>
      <div class="step pending">
        <div class="step-num">3</div>
        <div class="step-label">Thanh toán</div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="checkout-layout">

    {{-- LEFT: Form --}}
    <div class="form-section">
      <h2 class="form-title">Thông tin nhận hàng</h2>
      <form method="POST" action="{{ route('client.checkout.shipping.post') }}" id="shippingForm">
        @csrf
        <div class="form-grid">
          <div class="form-group full">
            <label class="form-label">Họ và tên <span>*</span></label>
            <input type="text" name="fullName" class="form-control {{ $errors->has('fullName') ? 'error' : '' }}"
              value="{{ old('fullName', $user->fullName ?? '') }}" placeholder="Nguyễn Văn A" required>
            @error('fullName')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group full">
            <label class="form-label">Số điện thoại <span>*</span></label>
            <input type="tel" name="phone" class="form-control {{ $errors->has('phone') ? 'error' : '' }}"
              value="{{ old('phone', $user->phone ?? '') }}" placeholder="0901 234 567" required>
            @error('phone')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Tỉnh / Thành phố <span>*</span></label>
            <input type="text" name="city" class="form-control {{ $errors->has('city') ? 'error' : '' }}"
              value="Hồ Chí Minh" readonly style="background:#f5f5f5; color:#555; cursor:not-allowed;" required>
            @error('city')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Quận / Huyện <span>*</span></label>
            <select name="district" id="shipping-district" class="form-control {{ $errors->has('district') ? 'error' : '' }}" onchange="updateWards()" required>
                <option value="">Chọn Quận/Huyện</option>
            </select>
            @error('district')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group full">
            <label class="form-label">Phường / Xã <span>*</span></label>
            <select name="ward" id="shipping-ward" class="form-control {{ $errors->has('ward') ? 'error' : '' }}" required>
                <option value="">Chọn Phường/Xã</option>
            </select>
            @error('ward')<span class="form-error">{{ $message }}</span>@enderror
          </div>
          <div class="form-group full">
            <label class="form-label">Địa chỉ chi tiết <span>*</span></label>
            <input type="text" name="addressDetail" class="form-control {{ $errors->has('addressDetail') ? 'error' : '' }}"
              value="{{ old('addressDetail', $address?->addressDetail ?? '') }}" placeholder="Số nhà, tên đường, toà nhà..." required>
            @error('addressDetail')<span class="form-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div style="margin-top:28px;display:flex;gap:12px;">
          <a href="{{ route('client.cart') }}" class="btn-outline" style="padding:13px 24px;font-size:11px;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
          </a>
          <button type="submit" class="btn-primary" style="flex:1;padding:14px;justify-content:center;">
            Tiếp tục <i class="fa-solid fa-arrow-right" style="margin-left:8px;"></i>
          </button>
        </div>
      </form>
    </div>

    {{-- RIGHT: Summary --}}
    <div class="checkout-summary">
      <div class="summary-box">
        <div class="summary-box-title">Sản phẩm ({{ count($cartItems) }})</div>
        @foreach($cartItems as $item)
          <div class="summary-item">
            @if($item['imageURL'])
              <img class="summary-item-img" src="{{ asset('storage/'.$item['imageURL']) }}" alt="{{ $item['productName'] }}">
            @else
              <div class="summary-item-img" style="display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-shirt" style="color:rgba(0,0,0,.15);"></i></div>
            @endif
            <div class="summary-item-info">
              <div class="summary-item-name">{{ $item['productName'] }}</div>
              <div class="summary-item-variant">
                @if($item['colorName']) {{ $item['colorName'] }} @endif
                @if($item['sizeName']) · {{ $item['sizeName'] }} @endif
                · x{{ $item['quantity'] }}
              </div>
              <div class="summary-item-price">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ</div>
            </div>
          </div>
        @endforeach

        <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:4px;">
          <div class="summary-row"><span>Tạm tính</span><span>{{ number_format($total,0,',','.') }}đ</span></div>
          @if($discountData)
            <div class="summary-row discount-row"><span>Giảm giá</span><span>−{{ number_format($discountData['discountAmount'],0,',','.') }}đ</span></div>
          @endif
          <div class="summary-row total-row"><span>Tổng cộng</span><span id="finalTotal">{{ number_format($discountData ? $discountData['finalAmount'] : $total, 0,',','.') }}đ</span></div>
        </div>
      </div>

      {{-- Discount code --}}
      <div class="discount-box">
        <div class="summary-box-title">Mã giảm giá</div>
        @if($discountData)
          <div class="applied-discount">
            <div>
              <span>{{ $discountData['discountCode'] }}</span>
              <div style="font-size:11px;color:var(--gray);">{{ $discountData['discountName'] }}</div>
            </div>
            <button onclick="removeDiscount()">Xóa</button>
          </div>
        @else
          <div class="discount-input-wrap">
            <input type="text" class="discount-input" id="discountCode" placeholder="VELOUR10" maxlength="50">
            <button class="btn-apply-discount" onclick="applyDiscount()">Áp dụng</button>
          </div>
          <div class="discount-msg" id="discountMsg"></div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
// Bộ dữ liệu danh sách Quận/Huyện, Phường/Xã TP. Hồ Chí Minh chuẩn
const hcmData = {
    "Quận 1": ["Phường Bến Nghé", "Phường Bến Thành", "Phường Cầu Kho", "Phường Cầu Ông Lãnh", "Phường Cô Giang", "Phường Đa Kao", "Phường Nguyễn Cư Trinh", "Phường Nguyễn Thái Bình", "Phường Phạm Ngũ Lão", "Phường Tân Định"],
    "Quận 3": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường Võ Thị Sáu"],
    "Quận 4": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 6", "Phường 8", "Phường 9", "Phường 10", "Phường 13", "Phường 14", "Phường 15", "Phường 16"],
    "Quận 5": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14"],
    "Quận 6": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14"],
    "Quận 7": ["Phường Bình Thuận", "Phường Phú Mỹ", "Phường Phú Thuận", "Phường Tân Hưng", "Phường Tân Kiểng", "Phường Tân Phong", "Phường Tân Phú", "Phường Tân Quy", "Phường Tân Thuận Đông", "Phường Tân Thuận Tây"],
    "Quận 8": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 16"],
    "Quận 10": ["Phường 1", "Phường 2", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận 11": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận 12": ["Phường An Phú Đông", "Phường Đông Hưng Thuận", "Phường Hiệp Thành", "Phường Tân Chánh Hiệp", "Phường Tân Hưng Thuận", "Phường Tân Thới Hiệp", "Phường Tân Thới Nhất", "Phường Thạnh Lộc", "Phường Thạnh Xuân", "Phường Thới An", "Phường Trung Mỹ Tây"],
    "Thành phố Thủ Đức": ["Phường An Khánh", "Phường An Lợi Đông", "Phường An Phú", "Phường Bình Chiểu", "Phường Bình Thọ", "Phường Bình Trưng Đông", "Phường Bình Trưng Tây", "Phường Cát Lái", "Phường Hiệp Bình Chánh", "Phường Hiệp Bình Phước", "Phường Hiệp Phú", "Phường Linh Chiểu", "Phường Linh Đông", "Phường Linh Tây", "Phường Linh Trung", "Phường Linh Xuân", "Phường Long Bình", "Phường Long Phước", "Phường Long Thạnh Mỹ", "Phường Long Trường", "Phường Phú Hữu", "Phường Phước Bình", "Phường Phước Long A", "Phường Phước Long B", "Phường Tam Bình", "Phường Tam Phú", "Phường Tăng Nhơn Phú A", "Tăng Nhơn Phú B", "Phường Thạnh Mỹ Lợi", "Phường Thảo Điền", "Phường Thủ Thiêm", "Phường Trường Thạnh", "Phường Trường Thọ"],
    "Quận Bình Tân": ["Phường An Lạc", "Phường An Lạc A", "Phường Bình Trị Đông", "Phường Bình Trị Đông A", "Phường Bình Trị Đông B", "Phường Bình Hưng Hòa", "Phường Bình Hưng Hòa A", "Phường Bình Hưng Hòa B", "Phường Tân Tạo", "Phường Tân Tạo A"],
    "Quận Bình Thạnh": ["Phường 1", "Phường 2", "Phường 3", "Phường 5", "Phường 6", "Phường 7", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 17", "Phường 19", "Phường 21", "Phường 22", "Phường 24", "Phường 25", "Phường 26", "Phường 27", "Phường 28"],
    "Quận Gò Vấp": ["Phường 1", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15", "Phường 16", "Phường 17"],
    "Quận Phú Nhuận": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 13", "Phường 15", "Phường 17"],
    "Quận Tân Bình": ["Phường 1", "Phường 2", "Phường 3", "Phường 4", "Phường 5", "Phường 6", "Phường 7", "Phường 8", "Phường 9", "Phường 10", "Phường 11", "Phường 12", "Phường 13", "Phường 14", "Phường 15"],
    "Quận Tân Phú": ["Phường Hiệp Tân", "Phường Hòa Thạnh", "Phường Phú Thạnh", "Phường Phú Thọ Hòa", "Phường Phú Trung", "Phường Sơn Kỳ", "Phường Tân Quý", "Phường Tân Sơn Nhì", "Phường Tân Thành", "Phường Tân Thới Hòa", "Phường Tây Thạnh"],
    "Huyện Bình Chánh": ["Thị trấn Tân Túc", "Xã An Phú Tây", "Xã Bình Chánh", "Xã Bình Hưng", "Xã Bình Lợi", "Xã Đa Phước", "Xã Hưng Long", "Xã Lê Minh Xuân", "Xã Phạm Văn Hai", "Xã Phong Phú", "Xã Quy Đức", "Xã Tân Kiên", "Xã Tân Nhựt", "Xã Tân Quý Tây", "Xã Vĩnh Lộc A", "Xã Vĩnh Lộc B"],
    "Huyện Cần Giờ": ["Thị trấn Cần Thạnh", "Xã An Thới Đông", "Xã Bình Khánh", "Xã Long Hòa", "Xã Lý Nhơn", "Xã Tam Thôn Hiệp", "Xã Thạnh An"],
    "Huyện Củ Chi": ["Thị trấn Củ Chi", "Xã An Nhơn Tây", "Xã An Phú", "Xã Bình Mỹ", "Xã Hòa Phú", "Xã Nhuận Đức", "Xã Phạm Văn Cội", "Xã Phú Hòa Đông", "Xã Phú Mỹ Hưng", "Xã Phước Hiệp", "Xã Phước Thạnh", "Xã Phước Vĩnh An", "Xã Tân An Hội", "Xã Tân Định", "Xã Tân Phú Trung", "Xã Tân Thạnh Đông", "Xã Tân Thạnh Tây", "Xã Tân Thông Hội", "Xã Thái Mỹ", "Xã Trung An", "Xã Trung Lập Hạ", "Xã Trung Lập Thượng"],
    "Huyện Hóc Môn": ["Thị trấn Hóc Môn", "Xã Bà Điểm", "Xã Đông Thạnh", "Xã Nhị Bình", "Xã Tân Hiệp", "Xã Tân Thới Nhì", "Xã Tân Xuân", "Xã Thới Tam Thôn", "Xã Trung Chánh", "Xã Xuân Thới Đông", "Xã Xuân Thới Sơn", "Xã Xuân Thới Thượng"],
    "Huyện Nhà Bè": ["Thị trấn Nhà Bè", "Xã Hiệp Phước", "Xã Long Thới", "Xã Nhơn Đức", "Xã Phú Xuân", "Xã Phước Kiển", "Xã Phước Lộc"]
};

// Hàm đổ dữ liệu Phường/Xã tương ứng
function updateWards(selectedWard = '') {
    const distSelect = document.getElementById('shipping-district');
    const wardSelect = document.getElementById('shipping-ward');
    const selectedDist = distSelect.value;
    
    let htmlOptions = '<option value="">Chọn Phường/Xã</option>';
    if (selectedDist && hcmData[selectedDist]) {
        hcmData[selectedDist].forEach(ward => {
            const active = (ward === selectedWard) ? 'selected' : '';
            htmlOptions += `<option value="${ward}" ${active}>${ward}</option>`;
        });
    }
    wardSelect.innerHTML = htmlOptions;
}

// Khởi tạo danh sách Quận/Huyện ban đầu và khôi phục giá trị cũ nếu có
document.addEventListener("DOMContentLoaded", function() {
    const distSelect = document.getElementById('shipping-district');
    
    // Gán dữ liệu Quận/Huyện vào thẻ select
    let htmlDistricts = '<option value="">Chọn Quận/Huyện</option>';
    for (let key in hcmData) {
        htmlDistricts += `<option value="${key}">${key}</option>`;
    }
    distSelect.innerHTML = htmlDistricts;

    // Lấy dữ liệu cũ từ Laravel (nếu có dữ liệu form submit lỗi, hoặc thông tin lưu trong DB)
    const oldDistrict = "{{ old('district', $address?->district ?? '') }}";
    const oldWard = "{{ old('ward', $address?->ward ?? '') }}";

    if (oldDistrict) {
        distSelect.value = oldDistrict;
        updateWards(oldWard);
    }
});

async function applyDiscount() {
  const code = document.getElementById('discountCode')?.value?.trim();
  if (!code) return;
  const msgEl = document.getElementById('discountMsg');
  msgEl.textContent = 'Đang kiểm tra...';
  msgEl.className = 'discount-msg';

  try {
    const r = await fetch('{{ route("client.checkout.discount.apply") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrf },
      body: JSON.stringify({ code }),
    });
    const d = await r.json();
    if (d.success) {
      msgEl.textContent = d.message;
      msgEl.className = 'discount-msg success';
      document.getElementById('finalTotal').textContent = d.finalAmount;
      setTimeout(() => location.reload(), 800);
    } else {
      msgEl.textContent = d.message;
      msgEl.className = 'discount-msg error';
    }
  } catch {
    msgEl.textContent = 'Có lỗi xảy ra.';
    msgEl.className = 'discount-msg error';
  }
}

async function removeDiscount() {
  await fetch('{{ route("client.checkout.discount.remove") }}', {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': window.csrf },
  });
  location.reload();
}

document.getElementById('discountCode')?.addEventListener('keydown', e => {
  if (e.key === 'Enter') { e.preventDefault(); applyDiscount(); }
});
</script>
@endpush
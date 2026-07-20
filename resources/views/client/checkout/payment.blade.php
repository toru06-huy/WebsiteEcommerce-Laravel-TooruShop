@extends('layouts.client')
@section('title', 'Phương thức thanh toán – VELOUR')

@push('styles')
<style>
.checkout-bar { background: var(--cream); padding: 24px 0; border-bottom: 1px solid var(--border); }
.checkout-steps { display: flex; align-items: center; justify-content: center; gap: 0; }
.step { display: flex; align-items: center; gap: 10px; }
.step-num { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 500; }
.step.done .step-num { background: var(--black); color: var(--white); }
.step.active .step-num { background: var(--gold); color: var(--white); }
.step.pending .step-num { background: transparent; border: 1px solid var(--border); color: var(--gray); }
.step-label { font-size: 12px; letter-spacing: .5px; }
.step.active .step-label { color: var(--black); font-weight: 500; }
.step.pending .step-label { color: var(--gray); }
.step-sep { width: 60px; height: 1px; background: var(--border); margin: 0 8px; flex-shrink: 0; }
@media(max-width:600px){ .step-sep { width: 24px; } .step-label { display: none; } }

.checkout-layout { display: grid; grid-template-columns: 1fr 340px; gap: 40px; padding: 40px 0 80px; align-items: start; }
@media(max-width:960px){ .checkout-layout { grid-template-columns: 1fr; } }

.form-title { font-family: var(--font-display); font-size: 24px; font-weight: 300; margin-bottom: 28px; }

/* Shipping recap */
.shipping-display { background: var(--cream); padding: 18px 20px; margin-bottom: 28px; display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; }
.shipping-display-info { font-size: 13px; line-height: 1.8; }
.shipping-display-info strong { font-size: 14px; }
.shipping-display a { font-size: 12px; color: var(--gold); text-decoration: underline; white-space: nowrap; flex-shrink: 0; }

/* Payment options */
.payment-options { display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }
.payment-option {
  border: 1px solid var(--border); padding: 16px 20px; cursor: pointer;
  transition: var(--transition); display: flex; align-items: center; gap: 14px;
  user-select: none;
}
.payment-option:hover { border-color: var(--black); }
.payment-option.selected { border-color: var(--gold); background: rgba(184,150,90,.06); }
.payment-option-content { flex: 1; }
.payment-option-title { font-size: 14px; font-weight: 500; color: var(--black); }
.payment-option-desc { font-size: 12px; color: var(--gray); margin-top: 3px; }
.payment-option-icon { font-size: 22px; color: var(--gray); flex-shrink: 0; }

/* Bank info */
.bank-info { background: var(--cream); border-left: 3px solid var(--gold); padding: 20px 24px; margin-top: 12px; display: none; }
.bank-info.visible { display: block; }
.bank-info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; font-size: 13px; border-bottom: 1px solid var(--border); gap: 8px; }
.bank-info-row:last-child { border-bottom: none; }
.bank-info-label { color: var(--gray); flex-shrink: 0; }
.bank-info-value { font-weight: 500; color: var(--black); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; justify-content: flex-end; }
.bank-info-value.highlight { color: var(--gold); font-size: 16px; }
/* type="button" bắt buộc – ngăn submit form khi click Sao chép */
.copy-btn { background: none; border: none; font-size: 11px; color: var(--gold); text-decoration: underline; cursor: pointer; font-family: var(--font-body); padding: 0; white-space: nowrap; }
.copy-btn:hover { color: var(--black); }
.bank-note { margin-top: 12px; font-size: 11px; color: var(--gray); line-height: 1.7; padding: 10px 12px; background: rgba(184,150,90,.06); }

/* Submit button */
.btn-submit-order {
  flex: 1; padding: 15px 24px; background: var(--gold); color: var(--white);
  border: 1px solid var(--gold); font-size: 12px; letter-spacing: 2px; text-transform: uppercase;
  font-weight: 500; cursor: pointer; transition: var(--transition);
  display: flex; align-items: center; justify-content: center; gap: 8px;
}
.btn-submit-order:hover:not(:disabled) { background: #a07840; border-color: #a07840; }
.btn-submit-order:disabled { opacity: .65; cursor: not-allowed; }

/* Summary */
.summary-box { background: var(--cream); padding: 24px; margin-bottom: 16px; }
.summary-box-title { font-size: 11px; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 16px; font-weight: 500; }
.summary-item { display: flex; gap: 12px; margin-bottom: 12px; }
.summary-item-img { width: 56px; height: 68px; object-fit: cover; background: var(--border); flex-shrink: 0; }
.summary-item-info { flex: 1; min-width: 0; }
.summary-item-name { font-size: 12px; line-height: 1.4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.summary-item-variant { font-size: 11px; color: var(--gray); margin-top: 2px; }
.summary-item-price { font-size: 12px; color: var(--gold); margin-top: 4px; font-weight: 500; }
.summary-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--gray); padding: 8px 0; }
.summary-row.total-row { color: var(--black); font-weight: 500; font-size: 16px; border-top: 1px solid var(--border); margin-top: 4px; }
.summary-row.discount-row { color: var(--gold); }
</style>
@endpush

@section('content')

{{-- Step bar --}}
<div class="checkout-bar">
  <div class="container">
    <div class="checkout-steps">
      <div class="step done">
        <div class="step-num"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
        <div class="step-label">Giỏ hàng</div>
      </div>
      <div class="step-sep"></div>
      <div class="step done">
        <div class="step-num"><i class="fa-solid fa-check" style="font-size:12px;"></i></div>
        <div class="step-label">Thông tin nhận hàng</div>
      </div>
      <div class="step-sep"></div>
      <div class="step active">
        <div class="step-num">3</div>
        <div class="step-label">Thanh toán</div>
      </div>
    </div>
  </div>
</div>

<div class="container">
  <div class="checkout-layout">

    {{-- ─── LEFT: Form thanh toán ─── --}}
    <div>
      <h2 class="form-title">Phương thức thanh toán</h2>

      {{-- Recap địa chỉ --}}
      <div class="shipping-display">
        <div class="shipping-display-info">
          <strong>{{ $shippingInfo['fullName'] }}</strong> &nbsp;·&nbsp; {{ $shippingInfo['phone'] }}<br>
          {{ $shippingInfo['addressDetail'] }}, {{ $shippingInfo['ward'] }},<br>
          {{ $shippingInfo['district'] }}, {{ $shippingInfo['city'] }}
        </div>
        <a href="{{ route('client.checkout.shipping') }}">Sửa</a>
      </div>

      {{-- Form gửi POST đến finalize --}}
      <form method="POST" action="{{ route('client.checkout.finalize') }}" id="paymentForm">
        @csrf

        {{-- Hidden input – JS cập nhật khi chọn phương thức --}}
        <input type="hidden" name="payment_method" id="paymentMethodInput" value="cod">

        {{-- Lựa chọn phương thức --}}
        <div class="payment-options">

          {{-- COD --}}
          <div class="payment-option selected" id="optCod" tabindex="0" role="radio" aria-checked="true" data-method="cod">
            <div class="payment-option-icon"><i class="fa-solid fa-money-bill"></i></div>
            <div class="payment-option-content">
              <div class="payment-option-title">Thanh toán khi nhận hàng (COD)</div>
              <div class="payment-option-desc">Trả tiền mặt khi nhận hàng. Kiểm tra hàng trước khi thanh toán.</div>
            </div>
            <i class="fa-solid fa-circle-check" id="checkCod" style="color:var(--gold);font-size:18px;flex-shrink:0;"></i>
          </div>

          {{-- Chuyển khoản --}}
          <div class="payment-option" id="optBank" tabindex="0" role="radio" aria-checked="false" data-method="bank">
            <div class="payment-option-icon"><i class="fa-solid fa-building-columns"></i></div>
            <div class="payment-option-content">
              <div class="payment-option-title">Chuyển khoản ngân hàng</div>
              <div class="payment-option-desc">Chuyển khoản và nhận hàng sau khi Velour xác nhận thanh toán.</div>
            </div>
            <i class="fa-solid fa-circle" id="checkBank" style="color:var(--border);font-size:18px;flex-shrink:0;"></i>
          </div>
        </div>

        {{-- Thông tin ngân hàng (ẩn theo mặc định) --}}
        <div class="bank-info" id="bankInfo">
          <div style="font-size:12px;letter-spacing:1px;text-transform:uppercase;color:var(--gray);margin-bottom:14px;font-weight:500;">
            Thông tin tài khoản nhận tiền
          </div>
          <div class="bank-info-row">
            <span class="bank-info-label">Ngân hàng</span>
            <span class="bank-info-value">
              BIDV
              <button type="button" class="copy-btn" onclick="copyText('BIDV')">Sao chép</button>
            </span>
          </div>
          <div class="bank-info-row">
            <span class="bank-info-label">Số tài khoản</span>
            <span class="bank-info-value">
              0931462157
              <button type="button" class="copy-btn" onclick="copyText('0931462157')">Sao chép</button>
            </span>
          </div>
          <div class="bank-info-row">
            <span class="bank-info-label">Chủ tài khoản</span>
            <span class="bank-info-value">NGUYEN HOANG QUOC HUY</span>
          </div>
          <div class="bank-info-row">
            <span class="bank-info-label">Số tiền</span>
            <span class="bank-info-value highlight">{{ number_format($finalAmount, 0, ',', '.') }}đ</span>
          </div>
          <div class="bank-info-row">
            <span class="bank-info-label">Nội dung CK</span>
            <span class="bank-info-value" style="font-size:12px;">
              <span id="syntaxText">{{ $shippingInfo['fullName'] }} - {{ $shippingInfo['phone'] }}</span>
              <button type="button" class="copy-btn" onclick="copySyntax()">Sao chép</button>
            </span>
          </div>
          <div class="bank-note">
            <i class="fa-solid fa-circle-info" style="margin-right:6px;color:var(--gold);"></i>
            Đơn hàng xác nhận sau 1–2 giờ khi nhận được thanh toán. Vui lòng chuyển
            <strong>đúng số tiền</strong> và <strong>đúng nội dung</strong>.
          </div>
        </div>

        {{-- Nút hành động --}}
        <div style="display:flex;gap:12px;margin-top:28px;">
          <a href="{{ route('client.checkout.shipping') }}" class="btn-outline"
             style="padding:13px 24px;font-size:11px;white-space:nowrap;">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
          </a>
          <button type="submit" class="btn-submit-order" id="submitBtn">
            <i class="fa-solid fa-circle-check" id="submitIcon"></i>
            <span id="submitText">Hoàn thành đặt hàng</span>
          </button>
        </div>

      </form>{{-- end #paymentForm --}}
    </div>

    {{-- ─── RIGHT: Tóm tắt ─── --}}
    <div>
      <div class="summary-box">
        <div class="summary-box-title">Đơn hàng của bạn ({{ count($cartItems) }})</div>

        @foreach($cartItems as $item)
          <div class="summary-item">
            @if($item['imageURL'])
              <img class="summary-item-img"
                   src="{{ asset('storage/' . $item['imageURL']) }}"
                   alt="{{ $item['productName'] }}">
            @else
              <div class="summary-item-img" style="display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-shirt" style="color:rgba(0,0,0,.15);font-size:20px;"></i>
              </div>
            @endif
            <div class="summary-item-info">
              <div class="summary-item-name" title="{{ $item['productName'] }}">{{ $item['productName'] }}</div>
              <div class="summary-item-variant">
                @if($item['colorName']) {{ $item['colorName'] }} @endif
                @if($item['sizeName']) · {{ $item['sizeName'] }} @endif
                · x{{ $item['quantity'] }}
              </div>
              <div class="summary-item-price">
                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
              </div>
            </div>
          </div>
        @endforeach

        <div style="border-top:1px solid var(--border);padding-top:12px;margin-top:4px;">
          <div class="summary-row">
            <span>Tạm tính</span>
            <span>{{ number_format($total, 0, ',', '.') }}đ</span>
          </div>
          <div class="summary-row">
            <span>Vận chuyển</span>
            @if($total >= 500000)
              <span style="color:var(--gold);">Miễn phí</span>
            @else
              <span style="color:var(--gold);">42.000đ</span>
            @endif
          </div>
          @if($discountData)
            <div class="summary-row discount-row">
              <span>Giảm giá ({{ $discountData['discountCode'] }})</span>
              <span>−{{ number_format($discountData['discountAmount'], 0, ',', '.') }}đ</span>
            </div>
          @endif
          <div class="summary-row total-row">
            <span>Tổng thanh toán</span>
            <span style="color:var(--gold);">{{ number_format($finalAmount, 0, ',', '.') }}đ</span>
          </div>
        </div>
      </div>

      <div style="background:var(--cream);padding:16px 20px;font-size:12px;color:var(--gray);line-height:1.8;">
        <div style="font-size:11px;letter-spacing:1.5px;text-transform:uppercase;font-weight:500;color:var(--black);margin-bottom:8px;">
          <i class="fa-solid fa-location-dot" style="margin-right:6px;color:var(--gold);"></i>Giao đến
        </div>
        {{ $shippingInfo['addressDetail'] }}, {{ $shippingInfo['ward'] }},
        {{ $shippingInfo['district'] }}, {{ $shippingInfo['city'] }}
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
// ─── Chọn phương thức ────────────────────────────────────────────────────────
const optCod        = document.getElementById('optCod');
const optBank       = document.getElementById('optBank');
const bankInfo      = document.getElementById('bankInfo');
const paymentInput  = document.getElementById('paymentMethodInput');

function selectPayment(method) {
  const isCod = (method === 'cod');
  paymentInput.value = method;

  // Cập nhật COD card
  optCod.classList.toggle('selected', isCod);
  optCod.setAttribute('aria-checked', String(isCod));
  const ckCod = document.getElementById('checkCod');
  ckCod.className = isCod ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle';
  ckCod.style.color = isCod ? 'var(--gold)' : 'var(--border)';

  // Cập nhật Bank card
  optBank.classList.toggle('selected', !isCod);
  optBank.setAttribute('aria-checked', String(!isCod));
  const ckBank = document.getElementById('checkBank');
  ckBank.className = !isCod ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle';
  ckBank.style.color = !isCod ? 'var(--gold)' : 'var(--border)';

  // Hiện/ẩn bank info
  bankInfo.classList.toggle('visible', !isCod);
}

// Click
optCod.addEventListener('click',  () => selectPayment('cod'));
optBank.addEventListener('click', () => selectPayment('bank'));

// Bàn phím
[optCod, optBank].forEach(el => {
  el.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      selectPayment(el.dataset.method);
    }
  });
});

// ─── Submit – chống double submit ────────────────────────────────────────────
let submitting = false;

document.getElementById('paymentForm').addEventListener('submit', function (e) {
  if (submitting) {
    // Ngăn gửi lần 2
    e.preventDefault();
    return;
  }
  submitting = true;

  // Cập nhật UI nút sau khi form đã bắt đầu submit (setTimeout = macro-task sau submit)
  const btn  = document.getElementById('submitBtn');
  const icon = document.getElementById('submitIcon');
  const text = document.getElementById('submitText');
  setTimeout(function () {
    btn.disabled      = true;
    icon.className    = 'fa-solid fa-spinner fa-spin';
    text.textContent  = 'Đang xử lý...';
  }, 10);
});

// ─── Copy helpers ─────────────────────────────────────────────────────────────
function copyText(text) {
  navigator.clipboard.writeText(text)
    .then(() => showToast('Đã sao chép!'))
    .catch(() => {});
}

function copySyntax() {
  const text = document.getElementById('syntaxText').textContent.trim();
  navigator.clipboard.writeText(text)
    .then(() => showToast('Đã sao chép nội dung chuyển khoản!'))
    .catch(() => {});
}

function showToast(msg) {
  const el = document.createElement('div');
  el.className = 'flash flash-success';
  el.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + msg;
  document.getElementById('flashContainer').appendChild(el);
  setTimeout(() => el.remove(), 3000);
}
</script>
@endpush

@extends('layouts.client')
@section('title', 'Giỏ hàng – VELOUR')

@push('styles')
  @vite(['resources/css/client/cart.css'])
@endpush

@section('content')
<div class="cart-hero">
  <div class="container">

@if(session('error') || session('stock_errors'))
<div style="background:#fff3f3;border:1px solid #f5c6c6;border-radius:8px;padding:16px 20px;margin-bottom:20px;">
    <div style="font-weight:600;color:#c0392b;margin-bottom:8px;">
        ⚠️ {{ session('error', 'Đặt hàng thất bại') }}
    </div>
    @if(session('stock_errors'))
    <ul style="margin:0;padding-left:20px;color:#c0392b;font-size:0.875rem;">
        @foreach(session('stock_errors') as $err)
        <li>{{ $err }}</li>
        @endforeach
    </ul>
    <div style="margin-top:10px;font-size:0.85rem;color:#666;">Vui lòng cập nhật số lượng và thử lại.</div>
    @endif
</div>
@endif
    <div class="breadcrumb">
      <a href="{{ route('client.home') }}">Trang chủ</a>
      <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
      <span>Giỏ hàng</span>
    </div>
    <h1>Giỏ hàng <span style="font-size:20px;color:var(--gray);font-family:var(--font-body);font-weight:300;">({{ count($cartItems) }} sản phẩm)</span></h1>
  </div>
</div>

<div class="container">
  @if(empty($cartItems))
    <div class="empty-cart">
      <i class="fa-solid fa-bag-shopping"></i>
      <h2>Giỏ hàng của bạn đang trống</h2>
      <p>Hãy khám phá những sản phẩm tuyệt vời của chúng tôi</p>
      <a href="{{ route('client.shop') }}" class="btn-primary">Tiếp tục mua sắm</a>
    </div>
  @else
    <div class="cart-layout">

      {{-- LEFT: Items --}}
      <div>
        <div class="cart-header-row">
          <span>Sản phẩm</span>
          <span>Số lượng</span>
          <span>Thành tiền</span>
        </div>
        @foreach($cartItems as $item)
          <div class="cart-item" id="cartItem{{ $item['variantID'] }}">
            <div class="cart-item-img">
              @if($item['imageURL'])
                <img src="{{ asset('storage/'.$item['imageURL']) }}" alt="{{ $item['productName'] }}">
              @else
                <div class="placeholder-icon"><i class="fa-solid fa-shirt" style="font-size:28px;color:rgba(0,0,0,.1);"></i></div>
              @endif
            </div>
            <div class="cart-item-info">
              <div class="cart-item-name">{{ $item['productName'] }}</div>
              <div class="cart-item-variant">
                @if($item['colorName']) Màu: {{ $item['colorName'] }} @endif
                @if($item['sizeName']) | Size: {{ $item['sizeName'] }} @endif
              </div>
              <div class="cart-item-price">{{ number_format($item['price'], 0, ',', '.') }}đ</div>
              <div class="cart-item-bottom">
                <div class="qty-ctrl-sm">
                  <button onclick="updateQty({{ $item['variantID'] }}, -1)">−</button>
                  <input type="number" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" id="qty{{ $item['variantID'] }}"
                    onchange="setQty({{ $item['variantID'] }}, this.value)">
                  <button onclick="updateQty({{ $item['variantID'] }}, 1)">+</button>
                </div>
                <button class="btn-remove" onclick="removeItem({{ $item['variantID'] }})">Xóa</button>
                <div class="cart-item-subtotal" id="sub{{ $item['variantID'] }}">
                  {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <div style="margin-top:24px;">
          <a href="{{ route('client.shop') }}" style="font-size:12px;color:var(--gray);text-decoration:underline;">
            <i class="fa-solid fa-arrow-left" style="margin-right:6px;"></i>Tiếp tục mua sắm
          </a>
        </div>
      </div>

      {{-- RIGHT: Summary --}}
      <div class="cart-summary">
        <div class="summary-title">Tóm tắt đơn hàng</div>
        <div class="summary-row"><span>Tạm tính</span><span id="summarySubtotal">{{ number_format($total,0,',','.') }}đ</span></div>
        <div class="summary-row total"><span>Tổng cộng</span><span id="summaryTotal">{{ number_format($total,0,',','.') }}đ</span></div>

        <form method="POST" action="{{ route('client.checkout.proceed') }}">
          @csrf
          <button type="submit" class="btn-gold btn-checkout">
            <i class="fa-solid fa-credit-card" style="margin-right:8px;"></i>Đặt hàng
          </button>
        </form>
        @guest
          <p class="summary-note">Bạn sẽ được yêu cầu đăng nhập trước khi thanh toán</p>
        @endguest
        <a href="{{ route('client.shop') }}" class="btn-continue">Tiếp tục mua sắm</a>
      </div>

    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
const itemPrices = {
  @foreach($cartItems as $item)
    {{ $item['variantID'] }}: {{ $item['price'] }},
  @endforeach
};

function formatVND(n) {
  return new Intl.NumberFormat('vi-VN').format(Math.round(n)) + 'đ';
}

function recalcTotal() {
  let total = 0;
  document.querySelectorAll('[id^="qty"]').forEach(input => {
    const variantId = input.id.replace('qty', '');
    const qty = parseInt(input.value) || 0;
    const price = itemPrices[variantId] || 0;
    const sub = qty * price;
    const subEl = document.getElementById('sub' + variantId);
    if (subEl) subEl.textContent = formatVND(sub);
    total += sub;
  });
  document.getElementById('summarySubtotal').textContent = formatVND(total);
  document.getElementById('summaryTotal').textContent = formatVND(total);
}

async function updateQty(variantId, delta) {
  const input = document.getElementById('qty' + variantId);
  const max   = parseInt(input.max) || 99;
  let newQty  = (parseInt(input.value) || 1) + delta;
  newQty = Math.max(1, Math.min(newQty, max));
  input.value = newQty;
  await patchQty(variantId, newQty);
  recalcTotal();
}

async function setQty(variantId, val) {
  const input = document.getElementById('qty' + variantId);
  const max   = parseInt(input.max) || 99;
  let newQty  = Math.max(1, Math.min(parseInt(val) || 1, max));
  input.value = newQty;
  await patchQty(variantId, newQty);
  recalcTotal();
}

async function patchQty(variantId, qty) {
  await fetch(`/gio-hang/${variantId}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrf },
    body: JSON.stringify({ quantity: qty }),
  });
  updateCartCount();
}

async function removeItem(variantId) {
  if (!confirm('Xóa sản phẩm này khỏi giỏ hàng?')) return;
  const r = await fetch(`/gio-hang/${variantId}`, {
    method: 'DELETE',
    headers: { 'X-CSRF-TOKEN': window.csrf },
  });
  const d = await r.json();
  if (d.success) {
    const el = document.getElementById('cartItem' + variantId);
    el.style.opacity = '0'; el.style.transition = 'opacity .3s';
    setTimeout(() => { el.remove(); delete itemPrices[variantId]; recalcTotal(); updateCartCount(); }, 300);
  }
}
</script>
@endpush
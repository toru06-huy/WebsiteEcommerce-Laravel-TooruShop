@extends('layouts.client')
@section('title', 'Giỏ hàng – VELOUR')

@push('styles')
<style>
.cart-hero { background: var(--cream); padding: 32px 0; border-bottom: 1px solid var(--border); }
.cart-hero h1 { font-family: var(--font-display); font-size: 36px; font-weight: 300; }

.cart-layout { display: grid; grid-template-columns: 1fr 320px; gap: 40px; padding: 40px 0 80px; align-items: start; }
@media(max-width:900px){ .cart-layout { grid-template-columns: 1fr; } }

/* Cart items */
.cart-header-row { display: grid; grid-template-columns: 1fr auto auto; gap: 20px; align-items: center; padding-bottom: 12px; border-bottom: 1px solid var(--border); font-size: 11px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--gray); }
.cart-item { display: grid; grid-template-columns: 90px 1fr; gap: 20px; padding: 20px 0; border-bottom: 1px solid var(--border); align-items: start; }
.cart-item-img { width: 90px; height: 110px; overflow: hidden; background: var(--cream); flex-shrink: 0; }
.cart-item-img img { width: 100%; height: 100%; object-fit: cover; }
.cart-item-img .placeholder-icon { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
.cart-item-info { display: flex; flex-direction: column; gap: 4px; }
.cart-item-name { font-size: 14px; color: var(--black); font-weight: 400; line-height: 1.4; }
.cart-item-variant { font-size: 12px; color: var(--gray); }
.cart-item-price { font-size: 14px; color: var(--gold); margin-top: 4px; font-weight: 500; }
.cart-item-bottom { display: flex; align-items: center; gap: 16px; margin-top: 10px; }
.qty-ctrl-sm { display: flex; align-items: center; border: 1px solid var(--border); }
.qty-ctrl-sm button { width: 32px; height: 34px; font-size: 16px; transition: var(--transition); }
.qty-ctrl-sm button:hover { background: var(--cream); }
.qty-ctrl-sm input { width: 42px; height: 34px; text-align: center; border: none; border-left: 1px solid var(--border); border-right: 1px solid var(--border); font-size: 13px; outline: none; }
.cart-item-subtotal { font-size: 13px; font-weight: 500; margin-left: auto; white-space: nowrap; }
.btn-remove { font-size: 12px; color: var(--gray); text-decoration: underline; cursor: pointer; background: none; transition: var(--transition); }
.btn-remove:hover { color: #c0392b; }

.empty-cart { text-align: center; padding: 80px 20px; }
.empty-cart i { font-size: 64px; opacity: .12; display: block; margin-bottom: 20px; }
.empty-cart h2 { font-family: var(--font-display); font-size: 24px; font-weight: 300; margin-bottom: 8px; }
.empty-cart p { color: var(--gray); font-size: 14px; margin-bottom: 24px; }

/* Summary */
.cart-summary { position: sticky; top: 88px; background: var(--cream); padding: 28px; }
.summary-title { font-size: 13px; letter-spacing: 2px; text-transform: uppercase; font-weight: 500; margin-bottom: 20px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--gray); margin-bottom: 12px; }
.summary-row.total { color: var(--black); font-weight: 500; font-size: 16px; padding-top: 12px; border-top: 1px solid var(--border); margin-top: 4px; }
.summary-note { font-size: 11px; color: var(--gray); text-align: center; margin-top: 12px; }
.btn-checkout { display: block; width: 100%; padding: 16px; text-align: center; font-size: 12px; letter-spacing: 2px; text-transform: uppercase; font-weight: 500; margin-top: 16px; }
.btn-continue { display: block; text-align: center; font-size: 12px; color: var(--gray); text-decoration: underline; margin-top: 12px; }
.btn-continue:hover { color: var(--black); }
</style>
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
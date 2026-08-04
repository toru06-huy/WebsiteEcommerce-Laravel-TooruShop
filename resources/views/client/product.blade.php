@extends('layouts.client')
@section('title', $product->productName . ' – VELOUR')

@push('styles')
  @vite(['resources/css/client/product.css'])
@endpush

@section('content')
<div class="container">
  <div class="breadcrumb">
    <a href="{{ route('client.home') }}">Trang chủ</a>
    <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
    @if($product->category->parent)
      <a href="{{ route('client.shop.category', $product->category->parentID) }}">{{ $product->category->parent->categoryName }}</a>
      <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
    @endif
    <a href="{{ route('client.shop.category', $product->categoryID) }}">{{ $product->category->categoryName }}</a>
    <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
    <span>{{ $product->productName }}</span>
  </div>

  <div class="detail-layout">

    {{-- LEFT: Gallery --}}
    <div class="img-gallery">
      <div class="img-main">
        @if($product->coverImage)
          <img src="{{ asset('storage/'.$product->coverImage->imageURL) }}" alt="{{ $product->productName }}" id="mainImg">
        @else
          <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#ede8e0,#d8cfc3);">
            <i class="fa-solid fa-shirt" style="font-size:64px;color:rgba(0,0,0,.12);"></i>
          </div>
        @endif
      </div>
      @if($product->images->count() > 1)
        <div class="img-thumbnails">
          @foreach($product->images as $i => $img)
            <div class="img-thumb {{ $i === 0 ? 'active' : '' }}" onclick="switchImg('{{ asset('storage/'.$img->imageURL) }}', this)">
              <img src="{{ asset('storage/'.$img->imageURL) }}" alt="">
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- RIGHT: Info --}}
    <div class="product-detail-info">
      <div class="detail-category">{{ $product->category->parent?->categoryName }} / {{ $product->category->categoryName }}</div>
      <h1 class="detail-name">{{ $product->productName }}</h1>
      <div class="detail-price" id="displayPrice">
          @if($product->is_on_sale)
              <span style="text-decoration:line-through;color:#aaa;font-size:14px;margin-right:6px;">{{ number_format($product->basePrice,0,',','.')}}đ</span>
              <span style="color:#c0392b;">{{ number_format($product->discounted_price,0,',','.')}}đ</span>
          @else
              {{ number_format($product->basePrice,0,',','.')}}đ
          @endif
      </div>

      @php $totalStock = $product->variants->sum('stockQuantity'); @endphp

      @if(!empty($variantMap))
        {{-- Color selection --}}
        <div class="option-label">Màu sắc: <span id="selectedColorName">—</span></div>
        <div class="color-options">
          @foreach($variantMap as $colorId => $colorData)
            <div class="color-swatch-text" data-color-id="{{ $colorId }}" onclick="selectColor({{ $colorId }})">
              @if($colorData['colorHex'] && $colorData['colorHex'] !== '#cccccc')
                <span style="width:16px;height:16px;border-radius:50%;background:{{ $colorData['colorHex'] }};border:1px solid var(--border);flex-shrink:0;"></span>
              @endif
              {{ $colorData['colorName'] }}
            </div>
          @endforeach
        </div>

        {{-- Size selection --}}
        <div class="option-label">Kích cỡ: <span id="selectedSizeName">—</span></div>
        <div class="size-options" id="sizeOptions">
          <span style="font-size:13px;color:var(--gray);">Vui lòng chọn màu sắc trước</span>
        </div>

        {{-- Quantity + Add to cart --}}
        <div id="addSection" style="display:none;">
          <div class="qty-row">
            <div class="qty-ctrl">
              <button onclick="changeQty(-1)">−</button>
              <input type="number" id="qtyInput" value="1" min="1" max="99">
              <button onclick="changeQty(1)">+</button>
            </div>
            <button class="btn-primary btn-add-cart" onclick="addToCart()">
              <i class="fa-solid fa-bag-shopping"></i> Thêm vào giỏ
            </button>
          </div>
          <div id="stockMsg" style="font-size:12px;color:var(--gray);margin-bottom:8px;"></div>
        </div>
        <div id="outSection" style="display:none;" class="out-msg">
          <i class="fa-solid fa-clock"></i> Biến thể này đã hết hàng
        </div>

        {{-- Nút yêu thích --}}
        @auth('web')
        @php
          $isWishlisted = auth('web')->user()->wishlists()->where('productID', $product->productID)->exists();
        @endphp
        <button id="wishlist-btn" onclick="toggleWishlist({{ $product->productID }}, this)"
          style="margin-top:12px;display:flex;align-items:center;gap:8px;background:none;border:1px solid {{ $isWishlisted ? '#e74c3c' : 'var(--border)' }};border-radius:8px;padding:10px 20px;font-size:13px;color:{{ $isWishlisted ? '#e74c3c' : 'var(--gray)' }};cursor:pointer;transition:all .2s;width:100%;justify-content:center;">
          <svg width="16" height="16" fill="{{ $isWishlisted ? '#e74c3c' : 'none' }}" stroke="{{ $isWishlisted ? '#e74c3c' : 'currentColor' }}" stroke-width="2" viewBox="0 0 24 24" id="wish-icon">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
          <span id="wish-label">{{ $isWishlisted ? 'Đã lưu yêu thích' : 'Lưu vào yêu thích' }}</span>
        </button>
        @else
        <a href="{{ route('client.login') }}"
          style="margin-top:12px;display:flex;align-items:center;gap:8px;background:none;border:1px solid var(--border);border-radius:8px;padding:10px 20px;font-size:13px;color:var(--gray);text-decoration:none;justify-content:center;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
          </svg>
          Đăng nhập để lưu yêu thích
        </a>
        @endauth
      @else
        <div class="out-msg"><i class="fa-solid fa-face-sad-tear"></i> Sản phẩm hiện chưa có biến thể nào</div>
      @endif

      <hr class="detail-divider">
      @if($product->manufacturer)
        <p style="font-size:12px;color:var(--gray);margin-bottom:8px;">
          <i class="fa-solid fa-building" style="margin-right:6px;"></i>Thương hiệu: <strong>{{ $product->manufacturer->manufacturerName ?? '' }}</strong>
        </p>
      @endif
      @if($product->description)
        <div class="detail-desc">{!! nl2br(e($product->description)) !!}</div>
      @endif

      <hr class="detail-divider">
      <div style="display:flex;flex-direction:column;gap:8px;">
        <div style="font-size:12px;color:var(--gray);"><i class="fa-solid fa-truck" style="margin-right:8px;color:var(--gold);"></i>Miễn phí vận chuyển cho đơn từ 500.000đ</div>
        <div style="font-size:12px;color:var(--gray);"><i class="fa-solid fa-rotate-left" style="margin-right:8px;color:var(--gold);"></i>Đổi trả trong 30 ngày</div>
        <div style="font-size:12px;color:var(--gray);"><i class="fa-solid fa-shield-check" style="margin-right:8px;color:var(--gold);"></i>Bảo hành chất lượng sản phẩm</div>
      </div>
    </div>
  </div>

  {{-- Related --}}
  @if($related->isNotEmpty())
    <div class="related-section">
      <h2 class="related-title">Sản phẩm liên quan</h2>
      <div class="related-grid">
        @foreach($related as $rp)
          @php $rStock = $rp->variants->sum('stockQuantity'); @endphp
          <div class="related-card" onclick="window.location='{{ route('client.product.show', $rp->productID) }}'">
            <div class="related-img-wrap">
              @if($rp->coverImage)
                <img class="rimg" src="{{ asset('storage/'.$rp->coverImage->imageURL) }}" alt="{{ $rp->productName }}" style="{{ $rStock === 0 ? 'filter:grayscale(1)' : '' }}" loading="lazy">
              @else
                <div class="rimg" style="display:flex;align-items:center;justify-content:center;background:var(--cream);"><i class="fa-solid fa-shirt" style="font-size:36px;color:rgba(0,0,0,.1);"></i></div>
              @endif
            </div>
            <div class="related-info">
              <div class="related-name">{{ $rp->productName }}</div>
              <div class="related-price">
                  @if($rp->is_on_sale)
                      <span style="text-decoration:line-through;color:#aaa;font-size:11px;margin-right:4px;">{{ number_format($rp->basePrice,0,',','.')}}đ</span>
                      <span style="color:#c0392b;">{{ number_format($rp->discounted_price,0,',','.')}}đ</span>
                  @else
                      {{ number_format($rp->basePrice,0,',','.')}}đ
                  @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- Recently viewed --}}
  @if($viewProducts->isNotEmpty())
    <div class="viewed-section">
      <h2 class="viewed-title">Sản phẩm vừa xem</h2>
      <div class="viewed-grid">
        @foreach($viewProducts as $vp)
          @php $vStock = $vp->variants->sum('stockQuantity'); @endphp
          <div class="viewed-card" onclick="window.location='{{ route('client.product.show', $vp->productID) }}'">
            <div class="viewed-img-wrap">
              @if($vp->coverImage)
                <img class="vimg" src="{{ asset('storage/'.$vp->coverImage->imageURL) }}" alt="{{ $vp->productName }}" style="{{ $vStock === 0 ? 'filter:grayscale(1)' : '' }}" loading="lazy">
              @else
                <div class="vimg" style="display:flex;align-items:center;justify-content:center;background:var(--cream);"><i class="fa-solid fa-shirt" style="font-size:36px;color:rgba(0,0,0,.1);"></i></div>
              @endif
            </div>
            <div class="viewed-info">
              <div class="viewed-name">{{ $vp->productName }}</div>
              <div class="viewed-price">
                  @if($vp->is_on_sale)
                      <span style="text-decoration:line-through;color:#aaa;font-size:11px;margin-right:4px;">{{ number_format($vp->basePrice,0,',','.')}}đ</span>
                      <span style="color:#c0392b;">{{ number_format($vp->discounted_price,0,',','.')}}đ</span>
                  @else
                      {{ number_format($vp->basePrice,0,',','.')}}đ
                  @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
// Variant data from PHP
const variantMap = @json($variantMap);
let selectedColorId = null;
let selectedSizeId  = null;
let selectedVariantId = null;

function switchImg(src, thumb) {
  document.getElementById('mainImg')?.setAttribute('src', src);
  document.querySelectorAll('.img-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
}

function selectColor(colorId) {
  selectedColorId = colorId;
  selectedSizeId  = null;
  selectedVariantId = null;

  // Highlight color
  document.querySelectorAll('.color-swatch-text').forEach(el => el.classList.remove('active'));
  document.querySelector(`[data-color-id="${colorId}"]`)?.classList.add('active');

  const colorData = variantMap[colorId];
  document.getElementById('selectedColorName').textContent = colorData.colorName;

  // Render sizes
  const sizes = colorData.sizes;
  const sizeOptions = document.getElementById('sizeOptions');
  sizeOptions.innerHTML = '';

  Object.values(sizes).forEach(size => {
    const btn = document.createElement('button');
    btn.className = 'size-btn' + (size.stockQuantity === 0 ? ' disabled' : '');
    btn.textContent = size.sizeName;
    btn.dataset.sizeId = size.sizeID;
    btn.dataset.variantId = size.variantID;
    btn.dataset.stock = size.stockQuantity;
    btn.dataset.price = size.price;
    if (size.stockQuantity > 0) {
      btn.onclick = () => selectSize(size.sizeID);
    }
    sizeOptions.appendChild(btn);
  });

  document.getElementById('selectedSizeName').textContent = '—';
  document.getElementById('addSection').style.display = 'none';
  document.getElementById('outSection').style.display = 'none';
}

function selectSize(sizeId) {
  const colorData = variantMap[selectedColorId];
  const size = colorData.sizes[sizeId];
  if (!size || size.stockQuantity === 0) return;

  selectedSizeId    = sizeId;
  selectedVariantId = size.variantID;

  document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
  document.querySelector(`.size-btn[data-size-id="${sizeId}"]`)?.classList.add('active');
  document.getElementById('selectedSizeName').textContent = size.sizeName;

  // Price
  document.getElementById('displayPrice').textContent = new Intl.NumberFormat('vi-VN').format(size.price) + 'đ';

  // Stock info
  document.getElementById('stockMsg').textContent = `Còn ${size.stockQuantity} sản phẩm`;
  const qtyInput = document.getElementById('qtyInput');
  qtyInput.max = size.stockQuantity;
  if (parseInt(qtyInput.value) > size.stockQuantity) qtyInput.value = size.stockQuantity;

  document.getElementById('addSection').style.display = 'block';
  document.getElementById('outSection').style.display = 'none';
}

function changeQty(delta) {
  const input = document.getElementById('qtyInput');
  const max   = parseInt(input.max) || 99;
  let v = parseInt(input.value) + delta;
  v = Math.max(1, Math.min(v, max));
  input.value = v;
}

async function addToCart() {
  if (!selectedVariantId) { alert('Vui lòng chọn màu sắc và kích cỡ.'); return; }
  const qty = parseInt(document.getElementById('qtyInput').value) || 1;
  if (isNaN(qty) || qty < 1) {
    showToast('Số lượng phải lớn hơn hoặc bằng 1.', 'error');
    qtyInput.value = 1;
    qtyInput.focus(); 
    return;  
  }
  const max = parseInt(qtyInput.max) || 99;
  if (qty > max) {
    showToast(`Số lượng vượt quá tồn kho (Tối đa: ${max}).`, 'error');
    qtyInput.value = max;
    return;
  }
  try {
    const r = await fetch('{{ route("client.cart.add") }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrf },
      body: JSON.stringify({ variantID: selectedVariantId, quantity: qty }),
    });
    const d = await r.json();
    if (d.success) {
      document.getElementById('cartBadge').textContent = d.count;
      showToast(d.message, 'success');
    } else {
      showToast(d.message, 'error');
    }
  } catch {
    showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'error');
  }
}

function showToast(msg, type) {
  const el = document.createElement('div');
  el.className = `flash flash-${type}`;
  el.innerHTML = `<i class="fa-solid fa-${type==='success'?'circle-check':'circle-xmark'}"></i> ${msg}`;
  document.getElementById('flashContainer').appendChild(el);
  setTimeout(() => el.remove(), 4000);
}

// Override toggleWishlist để cập nhật UI đúng trên trang này
const _origToggle = window.toggleWishlist;
window.toggleWishlist = async function(productId, btn) {
  if (!btn) return;
  await _origToggle(productId, btn);
  // Cập nhật button wishlist riêng của trang product
  const wishBtn = document.getElementById('wishlist-btn');
  const wishIcon = document.getElementById('wish-icon');
  const wishLabel = document.getElementById('wish-label');
  if (!wishBtn) return;
  const on = wishBtn.style.color.includes('e74c3c') ? false : true; // toggle
  // Dùng data từ global toggle đã cập nhật btn.classList
  const isNowOn = btn.classList?.contains('wishlisted') ?? false;
  wishBtn.style.border  = isNowOn ? '1px solid #e74c3c' : '1px solid var(--border)';
  wishBtn.style.color   = isNowOn ? '#e74c3c' : 'var(--gray)';
  wishIcon.setAttribute('fill',   isNowOn ? '#e74c3c' : 'none');
  wishIcon.setAttribute('stroke', isNowOn ? '#e74c3c' : 'currentColor');
  wishLabel.textContent = isNowOn ? 'Đã lưu yêu thích' : 'Lưu vào yêu thích';
};
</script>
@endpush
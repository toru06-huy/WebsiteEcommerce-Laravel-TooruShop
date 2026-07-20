@php
  $totalStock = $product->variants->sum('stockQuantity');
  $isOut = $totalStock === 0;
  $isOnSale = $product->is_on_sale;
  $isWishlisted = auth('web')->check()
    ? auth('web')->user()->wishlists()->where('productID', $product->productID)->exists()
    : false;
@endphp
<div class="product-card" style="position:relative;" onclick="window.location='{{ route('client.product.show', $product->productID) }}'">

  {{-- Wishlist button --}}
  @auth('web')
  <button class="wishlist-btn {{ $isWishlisted ? 'wishlisted' : '' }}"
    data-id="{{ $product->productID }}"
    onclick="event.stopPropagation(); toggleWishlist({{ $product->productID }}, this)"
    title="{{ $isWishlisted ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
    style="position:absolute;top:10px;right:10px;z-index:5;background:rgba(255,255,255,.9);border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:transform .15s;">
    <svg width="16" height="16" fill="{{ $isWishlisted ? '#e74c3c' : 'none' }}" stroke="{{ $isWishlisted ? '#e74c3c' : '#666' }}" stroke-width="2" viewBox="0 0 24 24">
      <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
    </svg>
  </button>
  @endauth
  <div class="product-img-wrap">
    <div class="product-img-inner" style="{{ $isOut ? 'filter:grayscale(1);' : '' }}">
      @if($product->coverImage)
        <img src="{{ asset('storage/' . $product->coverImage->imageURL) }}" alt="{{ $product->productName }}" loading="lazy">
      @else
        <div class="product-img-placeholder"><i class="fa-solid fa-shirt"></i></div>
      @endif
    </div>

    {{-- Badge giảm giá --}}
    @if($isOnSale && !$isOut)
      @php $disc = $product->activeDiscount(); @endphp
      <div style="position:absolute;top:10px;left:10px;background:var(--gold);color:#fff;font-size:10px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:.5px;">
        -{{ number_format($disc->discountValue, 0) }}%
      </div>
    @endif

    @if($isOut)
      <div class="out-of-stock-badge"><span class="out-of-stock-text">Hết hàng</span></div>
    @else
      <div class="product-card-overlay">
        <button class="btn-quick-add" onclick="event.stopPropagation(); window.location='{{ route('client.product.show', $product->productID) }}'">
          Chọn sản phẩm
        </button>
      </div>
    @endif
  </div>
  <div class="product-info">
    <div class="product-name">{{ $product->productName }}</div>
    <div class="product-price">
      @if($isOnSale)
        <span style="text-decoration:line-through;color:#aaa;font-size:12px;margin-right:4px;">{{ number_format($product->basePrice,0,',','.') }}đ</span>
        <span style="color:#c0392b;">{{ number_format($product->discounted_price,0,',','.') }}đ</span>
      @else
        {{ number_format($product->basePrice,0,',','.') }}đ
      @endif
    </div>
  </div>
</div>
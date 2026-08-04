@extends('layouts.client')

@section('title', 'Sản phẩm yêu thích')

@push('styles')
    @vite(['resources/css/client/user/wishlist.css'])
@endpush

@section('content')
<div class="profile-page">

    {{-- ── Sidebar ── --}}
    <aside class="sidebar">
        <div class="sidebar-avatar">
            <div class="avatar-circle">{{ mb_strtoupper(mb_substr($user->fullName, 0, 1)) }}</div>
            <div class="sidebar-name">{{ $user->fullName }}</div>
            <div class="sidebar-role">{{ $user->role }}</div>
            @if($user->membership)
            @php $tier = $user->membership->tier; @endphp
            <div class="tier-badge tier-{{ strtolower($tier) }}">
                @if($tier === 'Bronze') 🥉 @elseif($tier === 'Silver') 🥈 @elseif($tier === 'Gold') 🥇 @else 💎 @endif
                {{ $tier }}
            </div>
            @endif
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('client.profile', $user->userID) }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Hồ sơ cá nhân
            </a>
            <a href="{{ route('client.profile.orders', $user->userID) }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                Đơn hàng của tôi
            </a>
            <a href="{{ route('client.profile.vouchers', $user->userID) }}">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/><circle cx="7.5" cy="7.5" r="1.5"/></svg>
                Mã giảm giá của tôi
            </a>
            <a href="{{ route('client.wishlist.index') }}" class="active">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                Sản phẩm yêu thích
            </a>
        </nav>
    </aside>

    {{-- ── Main ── --}}
    <div class="main-content">

        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Sản phẩm yêu thích</div>
                    <div class="card-subtitle">{{ $wishlists->total() }} sản phẩm đã lưu</div>
                </div>
                <a href="{{ route('client.shop') }}" style="font-size:12px;color:var(--stone);text-decoration:underline;">Tiếp tục mua sắm</a>
            </div>

            @if($wishlists->isEmpty())
            <div class="empty-state">
                <svg width="56" height="56" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <h3>Chưa có sản phẩm yêu thích</h3>
                <p>Nhấn ♡ trên sản phẩm để lưu vào đây.</p>
                <a href="{{ route('client.shop') }}" class="btn-wish-shop">Khám phá sản phẩm</a>
            </div>
            @else
            <div>
                @foreach($wishlists as $wl)
                @php
                    $product = $wl->product;
                    if (!$product) continue;
                    $isOnSale = $product->is_on_sale;
                @endphp
                <div class="wish-item" id="wish-{{ $product->productID }}">
                    {{-- Ảnh --}}
                    <a href="{{ route('client.product.show', $product->productID) }}" class="wish-img">
                        @if($product->coverImage)
                            <img src="{{ asset('storage/' . $product->coverImage->imageURL) }}" alt="{{ $product->productName }}">
                        @else
                            <div class="wish-img-placeholder"><i class="fa-solid fa-shirt" style="font-size:28px;color:rgba(0,0,0,.1);"></i></div>
                        @endif
                    </a>

                    {{-- Thông tin --}}
                    <div class="wish-info">
                        <a href="{{ route('client.product.show', $product->productID) }}" class="wish-name">
                            {{ $product->productName }}
                        </a>
                        @if($product->category)
                        <div class="wish-category">{{ $product->category->categoryName }}</div>
                        @endif

                        <div class="wish-price">
                            @if($isOnSale)
                                <span class="wish-price-original">{{ number_format($product->basePrice, 0, ',', '.') }}đ</span>
                                <span style="color:var(--error);">{{ number_format($product->discounted_price, 0, ',', '.') }}đ</span>
                            @else
                                {{ number_format($product->basePrice, 0, ',', '.') }}đ
                            @endif
                        </div>

                        <div class="wish-bottom">
                            <a href="{{ route('client.product.show', $product->productID) }}" class="btn-wish-shop">
                                <i class="fa-solid fa-bag-shopping" style="font-size:11px;"></i>
                                Mua ngay
                            </a>
                            <button class="btn-wish-remove" onclick="removeWishlist({{ $product->productID }}, this)">
                                Xóa khỏi yêu thích
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($wishlists->hasPages())
            <div style="padding:20px 28px;border-top:1px solid var(--sand);">
                {{ $wishlists->links() }}
            </div>
            @endif
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
async function removeWishlist(productId, btn) {
    const res = await fetch('{{ route("client.wishlist.toggle") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ productID: productId }),
    });
    const d = await res.json();
    if (d.success && !d.wishlisted) {
        const row = document.getElementById('wish-' + productId);
        row.style.opacity = '0';
        row.style.transition = 'opacity .3s';
        setTimeout(() => { row.remove(); }, 300);
    }
}
</script>
@endpush
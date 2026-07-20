@extends('layouts.client')

@section('title', 'Sản phẩm yêu thích')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --cream:      #faf8f5;
        --sand:       #ede9e2;
        --stone:      #c9c3b8;
        --charcoal:   #2b2b2b;
        --ink:        #1a1a1a;
        --sage:       #7a9e87;
        --sage-light: #eef3f0;
        --gold:       #c9a84c;
        --error:      #c0392b;
        --radius:     10px;
    }

    body { background: var(--cream); color: var(--charcoal); font-family: 'DM Sans', sans-serif; }

    .profile-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 56px 24px 80px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 40px;
        align-items: start;
    }

    /* Sidebar */
    .sidebar { position: sticky; top: 80px; background: #fff; border: 1px solid var(--sand); border-radius: var(--radius); overflow: hidden; }
    .sidebar-avatar { background: var(--sand); padding: 36px 24px; text-align: center; border-bottom: 1px solid var(--sand); }
    .avatar-circle { width: 80px; height: 80px; border-radius: 50%; background: var(--sage); display: flex; align-items: center; justify-content: center; font-family: 'Cormorant Garamond', serif; font-size: 2rem; color: #fff; margin: 0 auto 12px; }
    .sidebar-name { font-family: 'Cormorant Garamond', serif; font-size: 1.1rem; font-weight: 600; color: var(--ink); }
    .sidebar-role { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 2px; color: var(--stone); margin-top: 4px; }
    .sidebar-nav { padding: 12px 0; }
    .sidebar-nav a { display: flex; align-items: center; gap: 10px; padding: 12px 24px; font-size: 0.875rem; color: var(--charcoal); text-decoration: none; transition: background 0.15s, color 0.15s; }
    .sidebar-nav a:hover, .sidebar-nav a.active { background: var(--sage-light); color: var(--sage); }
    .sidebar-nav a svg { opacity: 0.6; flex-shrink: 0; }
    .sidebar-nav a.active svg { opacity: 1; }

    /* Main */
    .main-content { display: flex; flex-direction: column; gap: 28px; }

    .card { background: #fff; border: 1px solid var(--sand); border-radius: var(--radius); overflow: hidden; }
    .card-header { padding: 22px 28px 18px; border-bottom: 1px solid var(--sand); display: flex; align-items: center; justify-content: space-between; }
    .card-title { font-family: 'Cormorant Garamond', serif; font-size: 1.15rem; font-weight: 600; color: var(--ink); }
    .card-subtitle { font-size: 0.8rem; color: var(--stone); margin-top: 3px; }

    /* Wishlist item — giống cart item */
    .wish-item {
        display: grid;
        grid-template-columns: 90px 1fr;
        gap: 20px;
        padding: 20px 28px;
        border-bottom: 1px solid var(--sand);
        align-items: start;
    }
    .wish-item:last-child { border-bottom: none; }
    .wish-img { width: 90px; height: 110px; overflow: hidden; background: var(--cream); flex-shrink: 0; border-radius: 6px; }
    .wish-img img { width: 100%; height: 100%; object-fit: cover; }
    .wish-img-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; }
    .wish-info { display: flex; flex-direction: column; gap: 4px; }
    .wish-name { font-size: 14px; color: var(--ink); font-weight: 500; line-height: 1.4; text-decoration: none; }
    .wish-name:hover { color: var(--sage); }
    .wish-category { font-size: 12px; color: var(--stone); }
    .wish-price { font-size: 14px; color: var(--gold); margin-top: 4px; font-weight: 500; }
    .wish-price-original { font-size: 12px; color: var(--stone); text-decoration: line-through; margin-right: 6px; }
    .wish-bottom { display: flex; align-items: center; gap: 12px; margin-top: 12px; flex-wrap: wrap; }
    .btn-wish-shop { display: inline-flex; align-items: center; gap: 6px; padding: 8px 18px; background: var(--ink); color: #fff; font-size: 12px; letter-spacing: 0.5px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; transition: opacity .15s; }
    .btn-wish-shop:hover { opacity: .8; }
    .btn-wish-remove { background: none; border: 1px solid var(--sand); border-radius: 6px; padding: 7px 14px; font-size: 12px; color: var(--stone); cursor: pointer; transition: all .15s; }
    .btn-wish-remove:hover { border-color: var(--error); color: var(--error); }

    /* Empty state */
    .empty-state { text-align: center; padding: 64px 24px; }
    .empty-state svg { opacity: .25; margin-bottom: 20px; }
    .empty-state h3 { font-family: 'Cormorant Garamond', serif; font-size: 1.3rem; color: var(--charcoal); margin-bottom: 8px; }
    .empty-state p { font-size: 0.875rem; color: var(--stone); margin-bottom: 24px; }

    /* Tier badge */
    .tier-badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; margin-top: 8px; }
    .tier-bronze   { background: #f0e6d9; color: #8b5e3c; }
    .tier-silver   { background: #eaecef; color: #5a6472; }
    .tier-gold     { background: #fdf3d0; color: #a07c10; }
    .tier-platinum { background: #e8f0f7; color: #2c5f8a; }

    @media (max-width: 768px) {
        .profile-page { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .sidebar { position: static; }
    }
</style>
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
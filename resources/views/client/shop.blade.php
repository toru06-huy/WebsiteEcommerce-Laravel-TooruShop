@extends('layouts.client')
@section('title', ($currentCategory ? $currentCategory->categoryName : 'Tất cả sản phẩm') . ' – VELOUR')

@push('styles')
    <style>
        .shop-hero {
            background: var(--cream);
            padding: 48px 0;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }

        .shop-hero h1 {
            font-family: var(--font-display);
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 300;
        }

        .shop-hero p {
            color: var(--gray);
            font-size: 13px;
            margin-top: 8px;
            letter-spacing: .5px;
        }

        .shop-layout {
            display: block;
            padding: 40px 0;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
        }

        .sidebar-section {
            border-bottom: 1px solid var(--border);
            padding: 20px 0;
        }

        .sidebar-section:first-child {
            padding-top: 0;
        }

        .sidebar-title {
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--black);
            font-weight: 500;
            margin-bottom: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .filter-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .filter-item input[type=checkbox] {
            accent-color: var(--black);
            width: 14px;
            height: 14px;
        }

        .filter-item label {
            font-size: 13px;
            color: var(--gray);
            cursor: pointer;
            transition: var(--transition);
        }

        .filter-item:hover label {
            color: var(--black);
        }

        .price-range {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .price-inputs {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .price-inputs input {
            flex: 1;
            padding: 8px 10px;
            border: 1px solid var(--border);
            font-size: 12px;
            outline: none;
            width: 0;
            transition: var(--transition);
        }

        .price-inputs input:focus {
            border-color: var(--black);
        }

        .price-inputs span {
            font-size: 12px;
            color: var(--gray);
            flex-shrink: 0;
        }

        .btn-apply-filter {
            width: 100%;
            padding: 10px;
            background: var(--black);
            color: var(--white);
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: var(--transition);
        }

        .btn-apply-filter:hover {
            background: var(--gold);
        }

        .btn-clear-filter {
            font-size: 11px;
            color: var(--gray);
            letter-spacing: 1px;
            text-decoration: underline;
            margin-top: 12px;
            display: block;
        }

        .btn-clear-filter:hover {
            color: var(--black);
        }

        /* PRODUCT AREA */
        .product-area {}

        .sort-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .sort-bar .result-count {
            font-size: 13px;
            color: var(--gray);
        }

        .sort-bar select {
            border: 1px solid var(--border);
            padding: 8px 14px;
            font-size: 13px;
            outline: none;
            color: var(--black);
            background: var(--white);
        }

        .product-grid-shop {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        @media(max-width:1200px) {
            .product-grid-shop {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:600px) {
            .product-grid-shop {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }

        .product-card {
            cursor: pointer;
        }

        .product-card:hover .product-img-inner {
            transform: scale(1.04);
        }

        .product-img-wrap {
            position: relative;
            overflow: hidden;
            aspect-ratio: 3/4;
            background: var(--cream);
        }

        .product-img-inner {
            width: 100%;
            height: 100%;
            transition: transform .5s cubic-bezier(.4, 0, .2, 1);
        }

        .product-img-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #ede8e0, #d8cfc3);
        }

        .product-img-placeholder i {
            font-size: 40px;
            color: rgba(0, 0, 0, .12);
        }

        .out-badge {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .out-badge-text {
            color: var(--white);
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, .6);
            padding: 7px 18px;
        }

        .product-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 12px;
            background: linear-gradient(to top, rgba(0, 0, 0, .45), transparent);
            opacity: 0;
            transition: var(--transition);
            display: flex;
            justify-content: center;
        }

        .product-card:hover .product-card-overlay {
            opacity: 1;
        }

        .btn-quick {
            background: var(--white);
            color: var(--black);
            padding: 9px 20px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .btn-quick:hover {
            background: var(--gold);
            color: var(--white);
        }

        .product-info {
            padding: 12px 2px 4px;
        }

        .product-name {
            font-size: 13px;
            color: var(--black);
            line-height: 1.4;
            margin-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 13px;
            color: var(--gold);
            font-weight: 500;
        }

        /* EMPTY */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 56px;
            margin-bottom: 20px;
            display: block;
            opacity: .2;
        }

        .empty-state h3 {
            font-size: 18px;
            font-weight: 400;
            margin-bottom: 8px;
            color: var(--black);
        }

        /* PAGINATION */
        .pagination-wrap {
            display: flex;
            justify-content: center;
            gap: 6px;
            padding: 40px 0 0;
        }

        .pagination-wrap a,
        .pagination-wrap span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid var(--border);
            font-size: 13px;
            transition: var(--transition);
            color: var(--black);
        }

        .pagination-wrap .active {
            background: var(--black);
            color: var(--white);
            border-color: var(--black);
        }

        .pagination-wrap a:hover {
            border-color: var(--black);
        }

        /* Filter toggle – luôn hiển thị */
        .mobile-filter-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid var(--border);
            font-size: 12px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 16px;
            cursor: pointer;
        }

        .sidebar {
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1100;
            background: var(--white);
            width: 280px;
            padding: 24px;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform .3s ease;
            box-shadow: 4px 0 24px rgba(0, 0, 0, .1);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 1099;
        }

        .sidebar-overlay.active {
            display: block;
        }
    </style>
@endpush

@section('content')

    <div class="shop-hero">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route('client.home') }}">Trang chủ</a>
                <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                @if ($currentCategory)
                    @if ($currentCategory->parent)
                        <a
                            href="{{ route('client.shop.category', $currentCategory->parentID) }}">{{ $currentCategory->parent->categoryName }}</a>
                        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                    @endif
                    <span>{{ $currentCategory->categoryName }}</span>
                @else
                    <span>
                        @if ($currentCategory)
                            {{ $currentCategory->categoryName }}
                        @elseif(request('filter') == 'latest')
                            Sản phẩm mới nhất
                        @elseif(request('filter') == 'sale')
                            Sản phẩm đang giảm giá
                        @elseif(request('filter') == 'bestseller')
                            Sản phẩm bán chạy nhất
                        @elseif(request('filter') == 'favourite')
                            Sản phẩm gợi ý cho bạn
                        @else
                            Tất cả sản phẩm
                        @endif
                    </span>
                @endif
            </div>
            <h1>
                @if ($currentCategory)
                    {{ $currentCategory->categoryName }}
                @elseif(request('filter') == 'latest')
                    Sản phẩm mới nhất
                @elseif(request('filter') == 'sale')
                    Sản phẩm đang giảm giá
                @elseif(request('filter') == 'bestseller')
                    Sản phẩm bán chạy nhất
                @elseif(request('filter') == 'favourite')
                    Sản phẩm gợi ý cho bạn
                @else
                    Tất cả sản phẩm
                @endif
            </h1>
            <p>{{ $products->total() }} sản phẩm</p>
        </div>
    </div>

    <div class="container">
        <div class="shop-layout">

            {{-- SIDEBAR --}}
            <div class="sidebar" id="sidebar">
                <form method="GET" action="" id="filterForm">
                    @if (request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    {{-- Size --}}
                    <div class="sidebar-section">
                        <div class="sidebar-title">Kích cỡ</div>
                        <div class="filter-list">
                            @foreach ($sizes as $size)
                                <div class="filter-item">
                                    <input type="checkbox" name="sizes[]" value="{{ $size->sizeID }}"
                                        id="size{{ $size->sizeID }}"
                                        {{ in_array($size->sizeID, request('sizes', [])) ? 'checked' : '' }}>
                                    <label for="size{{ $size->sizeID }}">{{ $size->sizeName }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Màu sắc --}}
                    <div class="sidebar-section">
                        <div class="sidebar-title">Màu sắc</div>
                        <div class="filter-list">
                            @foreach ($colors as $color)
                                <div class="filter-item">
                                    <input type="checkbox" name="colors[]" value="{{ $color->colorID }}"
                                        id="color{{ $color->colorID }}"
                                        {{ in_array($color->colorID, request('colors', [])) ? 'checked' : '' }}>
                                    <label for="color{{ $color->colorID }}"
                                        style="display:flex;align-items:center;gap:8px;">
                                        @if ($color->colorHex ?? false)
                                            <span
                                                style="width:14px;height:14px;border-radius:50%;background:{{ $color->colorHex }};border:1px solid var(--border);flex-shrink:0;"></span>
                                        @endif
                                        {{ $color->colorName }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Giá --}}
                    <div class="sidebar-section">
                        <div class="sidebar-title">Mức giá</div>
                        <div class="price-range">
                            <div class="price-inputs">
                                <input type="number" name="min_price" placeholder="Từ" value="{{ request('min_price') }}"
                                    min="0">
                                <span>—</span>
                                <input type="number" name="max_price" placeholder="Đến" value="{{ request('max_price') }}"
                                    min="0">
                            </div>
                            <button type="submit" class="btn-apply-filter">Áp dụng</button>
                        </div>
                    </div>

                    @if (request()->hasAny(['sizes', 'colors', 'min_price', 'max_price']))
                        <a href="{{ $categoryId ? route('client.shop.category', $categoryId) : route('client.shop') }}{{ request('search') ? '?search=' . request('search') : '' }}"
                            class="btn-clear-filter">
                            <i class="fa-solid fa-xmark"></i> Xóa bộ lọc
                        </a>
                    @endif
                </form>
            </div>
            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

            {{-- PRODUCT AREA --}}
            <div class="product-area">
                <div class="sort-bar">
                    <button class="mobile-filter-toggle" onclick="openSidebar()">
                        <i class="fa-solid fa-sliders"></i> Bộ lọc
                    </button>
                    <span class="result-count">Hiển thị {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}
                        / {{ $products->total() }} sản phẩm</span>
                </div>

                @if ($products->isEmpty())
                    <div class="empty-state">
                        <i class="fa-solid fa-face-sad-tear"></i>
                        <h3>Không có sản phẩm phù hợp</h3>
                        <p>Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                        <a href="{{ route('client.shop') }}" class="btn-outline"
                            style="display:inline-flex;margin-top:24px;">Xem tất cả sản phẩm</a>
                    </div>
                @else
                    <div class="product-grid-shop">
                        @foreach ($products as $product)
                            @php
                                $totalStock = $product->variants->sum('stockQuantity');
                                $isOut = $totalStock === 0;
                                $isWishlisted = auth('web')->check()
                                    ? auth('web')
                                        ->user()
                                        ->wishlists()
                                        ->where('productID', $product->productID)
                                        ->exists()
                                    : false;
                            @endphp
                            <div class="product-card" style="position:relative;"
                                onclick="window.location='{{ route('client.product.show', $product->productID) }}'">
                                {{-- Wishlist button --}}
                                @auth('web')
                                    <button class="wishlist-btn {{ $isWishlisted ? 'wishlisted' : '' }}"
                                        data-id="{{ $product->productID }}"
                                        onclick="event.stopPropagation(); toggleWishlist({{ $product->productID }}, this)"
                                        title="{{ $isWishlisted ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                        style="position:absolute;top:10px;right:10px;z-index:5;background:rgba(255,255,255,.92);border:none;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 1px 4px rgba(0,0,0,.12);">
                                        <svg width="15" height="15" fill="{{ $isWishlisted ? '#e74c3c' : 'none' }}"
                                            stroke="{{ $isWishlisted ? '#e74c3c' : '#666' }}" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    </button>
                                @endauth
                                <div class="product-img-wrap">
                                    <div class="product-img-inner" style="{{ $isOut ? 'filter:grayscale(1);' : '' }}">
                                        @if ($product->coverImage)
                                            <img src="{{ asset('storage/' . $product->coverImage->imageURL) }}"
                                                alt="{{ $product->productName }}" loading="lazy">
                                        @else
                                            <div class="product-img-placeholder"><i class="fa-solid fa-shirt"></i></div>
                                        @endif
                                    </div>
                                    @if ($isOut)
                                        <div class="out-badge"><span class="out-badge-text">Hết hàng</span></div>
                                    @else
                                        <div class="product-card-overlay">
                                            <button class="btn-quick"
                                                onclick="event.stopPropagation();window.location='{{ route('client.product.show', $product->productID) }}'">Chọn</button>
                                        </div>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <div class="product-name">{{ $product->productName }}</div>
                                    <div class="product-price">
                                        @if ($product->is_on_sale)
                                            <span
                                                style="text-decoration:line-through;color:#aaa;font-size:12px;margin-right:4px;">{{ number_format($product->basePrice, 0, ',', '.') }}đ</span>
                                            <span
                                                style="color:#c0392b;">{{ number_format($product->discounted_price, 0, ',', '.') }}đ</span>
                                        @else
                                            {{ number_format($product->basePrice, 0, ',', '.') }}đ
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($products->hasPages())
                        <div class="pagination-wrap">
                            {{-- Prev --}}
                            @if ($products->onFirstPage())
                                <span style="opacity:.35;"><i class="fa-solid fa-chevron-left"
                                        style="font-size:11px;"></i></span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}"><i class="fa-solid fa-chevron-left"
                                        style="font-size:11px;"></i></a>
                            @endif
                            {{-- Pages --}}
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <span class="active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach
                            {{-- Next --}}
                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}"><i class="fa-solid fa-chevron-right"
                                        style="font-size:11px;"></i></a>
                            @else
                                <span style="opacity:.35;"><i class="fa-solid fa-chevron-right"
                                        style="font-size:11px;"></i></span>
                            @endif
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('active');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }
    </script>
@endpush

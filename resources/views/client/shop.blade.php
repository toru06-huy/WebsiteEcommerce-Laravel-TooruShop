@extends('layouts.client')
@section('title', ($currentCategory ? $currentCategory->categoryName : 'Tất cả sản phẩm') . ' – VELOUR')

@push('styles')
    @vite(['resources/css/client/shop.css'])
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

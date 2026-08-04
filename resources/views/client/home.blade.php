@extends('layouts.client')
@section('title', 'VELOUR – Thời Trang Cao Cấp')

@push('styles')
    @vite(['resources/css/client/home.css'])
@endpush

@section('content')

    {{-- HERO --}}
    <section class="hero-slider" id="hero-slider">
{{-- ====== SLIDE 2: SẢN PHẨM GIẢM GIÁ NHIỀU NHẤT ====== --}}
        @if ($bestSaleProduct)
            @php $bestDisc = $bestSaleProduct->activeDiscount(); @endphp
            <div class="slide slide-sale active">
                <div class="slide-inner">
                    <div class="slide-text">
                        <p class="slide-label" style="color:var(--gold);">🔥 Giảm giá sốc nhất</p>
                        <h1 class="slide-title" style="color:var(--black);">{{ $bestSaleProduct->productName }}</h1>
                        <div style="margin-bottom:20px;">
                            @if ($bestDisc)
                                <span
                                    style="text-decoration:line-through;color:#999;font-size:1rem;">{{ number_format($bestSaleProduct->basePrice, 0, ',', '.') }}đ</span>
                                <span
                                    style="font-family:var(--font-display);font-size:1.8rem;font-weight:600;color:var(--gold);margin-left:12px;">
                                    {{ number_format($bestSaleProduct->discounted_price, 0, ',', '.') }}đ
                                </span>
                            @endif
                        </div>
                        <p class="slide-desc" style="color:var(--gray);">
                            {{ Str::limit($bestSaleProduct->description, 120) }}</p>
                        <a href="{{ route('client.product.show', $bestSaleProduct->productID) }}" class="btn-primary">Mua
                            ngay</a>
                    </div>
                    <div class="slide-img">
                        <div class="slide-img-frame">
                            @if ($bestSaleProduct->coverImage)
                                <img src="{{ asset('storage/' . $bestSaleProduct->coverImage->imageURL) }}"
                                    alt="{{ $bestSaleProduct->productName }}">
                            @else
                                <div class="slide-img-placeholder">👗</div>
                            @endif
                        </div>
                        @if ($bestDisc)
                            <div class="sale-badge">
                                <span style="font-size:15px;">-{{ number_format($bestDisc->discountValue, 0) }}%</span>
                                <span>OFF</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
        {{-- ====== SLIDE 1: MÃ GIẢM GIÁ CÔNG KHAI ====== --}}
        @if ($bannerDiscount)
            @foreach ($bannerDiscount as $bDiscount)
                <div class="slide slide-voucher {{ !$bestSaleProduct ? 'active' : '' }}">
                    <div class="slide-inner">
                        <div class="slide-text">
                            <p class="slide-label" style="color:var(--gold);">✨ Ưu đãi đặc biệt</p>
                            <h1 class="slide-title" style="color:#fff;">
                                @if ($bDiscount->startDate > $now)
                                    Sắp ra mắt<br><em
                                        style="font-style:italic;color:var(--gold);">{{ $bDiscount->discountName }}</em>
                                @else
                                    Đang diễn ra<br><em
                                        style="font-style:italic;color:var(--gold);">{{ $bDiscount->discountName }}</em>
                                @endif
                            </h1>
                            <p class="slide-desc" style="color:rgba(255,255,255,.7);">
                                @if ($bDiscount->startDate > $now)
                                    Bắt đầu từ ngày {{ $bDiscount->startDate->format('d/m/Y') }}
                                @else
                                    Đang áp dụng — Hết hạn {{ $bDiscount->endDate->format('d/m/Y H:i') }}
                                @endif
                            </p>
                            <a href="{{ route('client.shop') }}" class="btn-primary">Mua sắm ngay</a>
                        </div>
                        <div class="slide-img">
                            <div class="voucher-banner-card">
                                <div class="voucher-banner-code">{{ $bDiscount->discountCode }}</div>
                                <div class="voucher-banner-value">
                                    @if ($bDiscount->discountType === 'percentage')
                                        Giảm {{ number_format($bDiscount->discountValue, 0) }}%
                                    @else
                                        Giảm {{ number_format($bDiscount->discountValue, 0, ',', '.') }}đ
                                    @endif
                                </div>
                                <div class="voucher-banner-meta">
                                    @if ($bDiscount->minOrderValue > 0)
                                        <span>
                                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="1.5" viewBox="0 0 24 24">
                                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                                                <line x1="3" y1="6" x2="21" y2="6" />
                                            </svg>
                                            Đơn tối thiểu {{ number_format($bDiscount->minOrderValue, 0, ',', '.') }}đ
                                        </span>
                                    @endif
                                    <span>
                                        <svg width="14" height="14" fill="none" stroke="currentColor"
                                            stroke-width="1.5" viewBox="0 0 24 24">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                        {{ $bDiscount->startDate->format('d/m/Y') }} —
                                        {{ $bDiscount->endDate->format('d/m/Y') }}
                                    </span>
                                    @if ($bDiscount->discountLimit)
                                        <span>
                                            <svg width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="1.5" viewBox="0 0 24 24">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                                <circle cx="9" cy="7" r="4" />
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                            </svg>
                                            Còn {{ $bDiscount->discountLimit }} lượt sử dụng
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- ====== SLIDE 3: SẢN PHẨM HOT NHẤT ====== --}}
        @if ($hotProduct)
            <div class="slide slide-hot {{ !$bannerDiscount && !$bestSaleProduct ? 'active' : '' }}">
                <div class="slide-inner">
                    <div class="slide-text">
                        <p class="slide-label" style="color:var(--gold);">🏆 Bán chạy số 1</p>
                        <h1 class="slide-title" style="color:#fff;">{{ $hotProduct->productName }}</h1>
                        <div style="margin-bottom:20px;">
                            <span
                                style="font-family:var(--font-display);font-size:1.8rem;font-weight:600;color:var(--gold);">
                                {{ number_format($hotProduct->basePrice, 0, ',', '.') }}đ
                            </span>
                        </div>
                        <p class="slide-desc" style="color:rgba(255,255,255,.7);">
                            {{ Str::limit($hotProduct->description, 120) }}</p>
                        <div style="display:flex;gap:12px;align-items:center;margin-bottom:28px;">
                            @if ($hotProduct->category)
                                <span
                                    style="font-size:12px;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.5);border:1px solid rgba(255,255,255,.2);padding:4px 12px;border-radius:20px;">
                                    {{ $hotProduct->category->categoryName }}
                                </span>
                            @endif
                            <span style="font-size:12px;color:rgba(255,255,255,.5);">
                                "{{ number_format($hotProduct->total_stock ?? 0, 0, ',', '.') }} sản phẩm có sẵn"
                            </span>
                        </div>
                        <a href="{{ route('client.product.show', $hotProduct->productID) }}" class="btn-primary">Mua
                            ngay</a>
                    </div>
                    <div class="slide-img">
                        <div class="slide-img-frame">
                            @if ($hotProduct->coverImage)
                                <img src="{{ asset('storage/' . $hotProduct->coverImage->imageURL) }}"
                                    alt="{{ $hotProduct->productName }}">
                            @else
                                <div class="slide-img-placeholder">👗</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mặc định khi không có dữ liệu --}}
        @if (!$bannerDiscount && !$bestSaleProduct && !$hotProduct)
            <div class="slide slide-voucher active">
                <div class="slide-inner">
                    <div class="slide-text">
                        <p class="slide-label" style="color:var(--gold);">Bộ sưu tập mới 2026</p>
                        <h1 class="slide-title" style="color:#fff;">Thời trang<br>là ngôn ngữ<br><em
                                style="font-style:italic;color:var(--gold);">của bạn</em></h1>
                        <p class="slide-desc" style="color:rgba(255,255,255,.7);">Khám phá những thiết kế tinh tế từ chất
                            liệu cao cấp.</p>
                        <a href="{{ route('client.shop') }}" class="btn-primary">Khám phá ngay</a>
                    </div>
                </div>
            </div>
        @endif

        <div class="slider-dots" id="slider-dots"></div>
        <button class="slider-arrow prev" id="slider-prev">&#8592;</button>
        <button class="slider-arrow next" id="slider-next">&#8594;</button>
        <div class="slider-progress" id="slider-progress"></div>

    </section>

    {{-- CATEGORY STRIP --}}
    <div class="cat-strip">
        <div class="container">
            @foreach ($rootCategories as $cat)
                <a href="{{ route('client.shop.category', $cat->categoryID) }}" class="cat-pill">
                    <i class="fa-solid fa-tag"></i>
                    {{ $cat->categoryName }}
                </a>
                @foreach ($cat->children as $child)
                    <a href="{{ route('client.shop.category', $child->categoryID) }}"
                        class="cat-pill">{{ $child->categoryName }}</a>
                @endforeach
            @endforeach
        </div>
    </div>

    {{-- FEATURED PRODUCTS --}}
    {{-- FEATURED PRODUCTS --}}
    <section class="section" id="featured">
        <div class="container">

            {{-- ── Nổi bật (Mới nhất) ── --}}
            <div class="section-header">
                <p class="section-label">Mới nhất</p>
                <h2 class="section-title">Sản phẩm nổi bật</h2>
            </div>
            @if ($featuredProducts->isNotEmpty())
                <div class="product-grid">
                    @foreach ($featuredProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                    <a href="{{ route('client.shop', ['filter' => 'latest']) }}" class="btn-view-more">Xem chi tiết sản
                        phẩm mới</a>
                </div>
            @endif

            {{-- ── Đang giảm giá ── --}}
            @if ($saleProducts->isNotEmpty())
                <div class="section-header" style="margin-top:72px;">
                    <p class="section-label">Ưu đãi</p>
                    <h2 class="section-title">🔥 Đang giảm giá</h2>
                </div>
                <div class="product-grid">
                    @foreach ($saleProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                    <a href="{{ route('client.shop', ['filter' => 'sale']) }}" class="btn-view-more">Xem chi tiết ưu
                        đãi</a>
                </div>
            @endif

            {{-- ── Bán chạy ── --}}
            @if ($bestsellerProducts->isNotEmpty())
                <div class="section-header" style="margin-top:72px;">
                    <p class="section-label">Xu hướng</p>
                    <h2 class="section-title">📦 Bán chạy nhất</h2>
                </div>
                <div class="product-grid">
                    @foreach ($bestsellerProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                    <a href="{{ route('client.shop', ['filter' => 'bestseller']) }}" class="btn-view-more">Xem chi tiết
                        sản phẩm bán chạy</a>
                </div>
            @endif

            {{-- ── Yêu thích ── --}}
            @if ($favouriteProducts->isNotEmpty())
                <div class="section-header" style="margin-top:72px;">
                    <p class="section-label">Gợi ý</p>
                    <h2 class="section-title">❤️ Có thể bạn sẽ thích</h2>
                </div>
                <div class="product-grid">
                    @foreach ($favouriteProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                    <a href="{{ route('client.shop', ['filter' => 'favourite']) }}" class="btn-view-more">Xem chi tiết
                        gợi ý xu hướng</a>
                </div>
            @endif

            {{-- ── Sản phẩm Nam ── --}}
            @if ($maleProducts->isNotEmpty())
                <div class="section-header" style="margin-top:72px;">
                    <p class="section-label">Thời trang nam</p>
                    <h2 class="section-title">👔 Dành cho nam</h2>
                </div>
                <div class="product-grid">
                    @foreach ($maleProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                @if ($namRoot)
                    <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                        <a href="{{ route('client.shop.category', $namRoot->categoryID) }}" class="btn-view-more">Xem
                            toàn bộ BST Nam</a>
                    </div>
                @endif
            @endif

            {{-- ── Sản phẩm Nữ ── --}}
            @if ($femaleProducts->isNotEmpty())
                <div class="section-header" style="margin-top:72px;">
                    <p class="section-label">Thời trang nữ</p>
                    <h2 class="section-title">👗 Dành cho nữ</h2>
                </div>
                <div class="product-grid">
                    @foreach ($femaleProducts as $product)
                        @include('client.partials.product-card')
                    @endforeach
                </div>
                @if ($nuRoot)
                    <div style="text-align:center; margin-top:24px; margin-bottom: 48px;">
                        <a href="{{ route('client.shop.category', $nuRoot->categoryID) }}" class="btn-view-more">Xem toàn
                            bộ BST Nữ</a>
                    </div>
                @endif
            @endif

            <div style="text-align:center;margin-top:56px;">
                <a href="{{ route('client.shop') }}" class="btn-outline">Xem tất cả sản phẩm</a>
            </div>

        </div>
    </section>

    {{-- MID BANNER --}}
    <div class="mid-banner">
        <div class="mid-banner-inner">
            <h2>Chất lượng vượt thời gian</h2>
            <p>Mỗi sản phẩm Velour được làm từ những chất liệu được tuyển chọn kỹ lưỡng, mang lại sự thoải mái và phong cách
                bền bỉ.</p>
            <a href="{{ route('client.shop') }}" class="btn-gold"
                style="display:inline-flex;width:auto;padding:14px 40px;">Khám phá ngay</a>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const DURATION = 7000;
                const slides = Array.from(document.querySelectorAll('#hero-slider .slide'));
                const dotsWrap = document.getElementById('slider-dots');
                const progress = document.getElementById('slider-progress');
                if (!slides.length) return;

                let current = 0,
                    timer = null,
                    progTimer = null;

                // ── Tạo dots ─────────────────────────────────────────────────────────────
                const dots = slides.map((_, i) => {
                    const d = document.createElement('button');
                    d.className = 'slider-dot' + (i === 0 ? ' active' : '');
                    d.setAttribute('aria-label', 'Slide ' + (i + 1));
                    d.addEventListener('click', () => goTo(i));
                    dotsWrap.appendChild(d);
                    return d;
                });

                // ── Chuyển slide ──────────────────────────────────────────────────────────
                function goTo(idx) {
                    slides[current].classList.remove('active');
                    dots[current].classList.remove('active');
                    current = (idx + slides.length) % slides.length;
                    slides[current].classList.add('active');
                    dots[current].classList.add('active');
                    // Re-trigger animation
                    const txt = slides[current].querySelector('.slide-text');
                    const img = slides[current].querySelector('.slide-img');
                    if (txt) {
                        txt.style.animation = 'none';
                        txt.offsetHeight;
                        txt.style.animation = '';
                    }
                    if (img) {
                        img.style.animation = 'none';
                        img.offsetHeight;
                        img.style.animation = '';
                    }
                    startProgress();
                }

                // ── Progress bar ──────────────────────────────────────────────────────────
                function startProgress() {
                    clearTimeout(timer);
                    clearInterval(progTimer);
                    progress.style.transition = 'none';
                    progress.style.width = '0%';

                    // Force reflow
                    progress.offsetHeight;
                    progress.style.transition = `width ${DURATION}ms linear`;
                    progress.style.width = '100%';

                    timer = setTimeout(() => goTo(current + 1), DURATION);
                }

                // ── Arrows ────────────────────────────────────────────────────────────────
                document.getElementById('slider-prev')?.addEventListener('click', () => goTo(current - 1));
                document.getElementById('slider-next')?.addEventListener('click', () => goTo(current + 1));

                // ── Pause on hover ────────────────────────────────────────────────────────
                const slider = document.getElementById('hero-slider');
                slider.addEventListener('mouseenter', () => {
                    clearTimeout(timer);
                    progress.style.animationPlayState = 'paused';
                    const w = parseFloat(getComputedStyle(progress).width);
                    const total = parseFloat(getComputedStyle(slider).width);
                    progress.style.transition = 'none';
                    progress.style.width = (w / total * 100) + '%';
                });
                slider.addEventListener('mouseleave', () => startProgress());

                // ── Touch swipe ───────────────────────────────────────────────────────────
                let touchX = 0;
                slider.addEventListener('touchstart', e => {
                    touchX = e.touches[0].clientX;
                }, {
                    passive: true
                });
                slider.addEventListener('touchend', e => {
                    const dx = e.changedTouches[0].clientX - touchX;
                    if (Math.abs(dx) > 50) goTo(dx < 0 ? current + 1 : current - 1);
                });

                startProgress();
            })();
        </script>
    @endpush

@endsection

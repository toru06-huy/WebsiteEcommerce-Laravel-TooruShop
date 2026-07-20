@extends('layouts.client')
@section('title', 'VELOUR – Thời Trang Cao Cấp')

@push('styles')
    <style>
        /* ── HERO SLIDER ── */
        .hero-slider {
            position: relative;
            height: calc(100vh - 100px);
            min-height: 600px;
            overflow: hidden;
            margin: 0px 30px 30px;
            /* Thêm bo góc cho toàn bộ banner chính */
            border-radius: 24px;
            /* Đảm bảo các slide con không bị tràn ra ngoài góc bo */
            mask-image: -webkit-radial-gradient(white, black);
            -webkit-mask-image: -webkit-radial-gradient(white, black);
        }

        .slide {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            opacity: 0;
            transition: opacity .8s ease;
            pointer-events: none;
        }

        .slide.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Slide 1 — Voucher (Màu Hồng Tro / Rose Muted - Ấm áp, thời thượng và rất đầm mắt) */
        .slide-voucher {
            background: linear-gradient(135deg, #ebdcd9 0%, #decbc7 50%, #cca39a 100%);
        }

        /* Giữ màu chữ tối để tương phản tốt trên nền hồng tro */
        .slide-voucher .slide-title {
            color: #231f20 !important;
        }

        .slide-voucher .slide-desc {
            color: #4a4243 !important;
        }

        .slide-voucher .voucher-banner-card {
            background: rgba(255, 255, 255, 0.4);
            border: 1.5px dashed #a08233;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        }

        .slide-voucher .voucher-banner-value {
            color: #231f20;
        }

        .slide-voucher .voucher-banner-meta {
            color: #4a4243;
        }


        /* Slide 2 — Sale product (Màu Gỗ Mộc / Warm Oatmeal - Tông xám kem đậm, cực kỳ sang và tôn vải) */
        .slide-sale {
            background: linear-gradient(135deg, #f2ece4 0%, #e3d7c7 60%, #cebda8 100%);
        }


        /* Slide 3 — Hot product (Màu Xanh Thạch Thảo / Muted Sage - Tông xanh rêu mờ pastel vừa vặn, rất dịu mát) */
        .slide-hot {
            background: linear-gradient(135deg, #dee4df 0%, #cad4cb 50%, #aebdaf 100%);
        }

        /* Điều chỉnh chữ slide 3 theo nền trung tính */
        .slide-hot .slide-title {
            color: #1e2621 !important;
        }

        .slide-hot .slide-desc {
            color: #414d44 !important;
        }

        .slide-inner {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 60px;
        }

        .slide-text {
            animation: slideIn .7s ease both;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-label {
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .slide-title {
            font-family: var(--font-display);
            font-size: clamp(36px, 4.5vw, 64px);
            font-weight: 300;
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .slide-desc {
            font-size: 14px;
            line-height: 1.8;
            margin-bottom: 32px;
            opacity: .8;
        }

        /* Voucher card */
        .voucher-banner-card {
            border: 1.5px dashed rgba(201, 168, 76, .6);
            border-radius: 20px;
            /* Tăng từ 16px lên 20px cho mềm mại */
            padding: 32px 36px;
            background: rgba(255, 255, 255, .05);
            backdrop-filter: blur(4px);
        }

        .voucher-banner-code {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 54px);
            font-weight: 600;
            color: var(--gold);
            letter-spacing: 4px;
            margin-bottom: 16px;
        }

        .voucher-banner-value {
            font-size: clamp(20px, 2.5vw, 32px);
            color: #fff;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .voucher-banner-meta {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, .65);
        }

        .voucher-banner-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Product image panel */
        .slide-img {
            position: relative;
            display: flex;
            justify-content: center;
            animation: imgIn .9s ease both;
        }

        @keyframes imgIn {
            from {
                opacity: 0;
                transform: scale(.96) translateY(16px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .slide-img-frame {
            width: min(340px, 90%);
            aspect-ratio: 3/4;
            overflow: hidden;
            border-radius: 16px;
            /* Tăng từ 8px lên 16px để đồng bộ với độ cong của góc banner */
            box-shadow: 0 32px 80px rgba(0, 0, 0, .35);
        }

        .slide-img-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slide-img-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            background: rgba(255, 255, 255, .08);
        }

        .sale-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--gold);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
            color: #fff;
            line-height: 1.2;
            box-shadow: 0 8px 24px rgba(201, 168, 76, .5);
        }

        /* Dot navigation */
        .slider-dots {
            position: absolute;
            bottom: 28px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 10;
        }

        .slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .35);
            border: none;
            cursor: pointer;
            padding: 0;
            transition: all .3s;
        }

        .slider-dot.active {
            width: 24px;
            border-radius: 6px;
            /* Tăng từ 4px lên 6px nhìn sẽ tròn trịa thanh lịch hơn */
            background: var(--gold);
        }

        /* Arrow nav */
        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            backdrop-filter: blur(6px);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .2s;
        }

        .slider-arrow:hover {
            background: rgba(255, 255, 255, .25);
        }

        .slider-arrow.prev {
            left: 24px;
        }

        .slider-arrow.next {
            right: 24px;
        }

        /* Progress bar */
        .slider-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            /* Tăng nhẹ lên 4px để dễ nhìn thấy trên viền cong */
            background: var(--gold);
            width: 0%;
            transition: width linear;
            z-index: 10;
        }

        @media(max-width: 768px) {
            .slide-inner {
                grid-template-columns: 1fr;
                padding: 0 24px;
                gap: 32px;
            }

            .slide-img {
                display: none;
            }

            .hero-slider {
                min-height: 480px;
            }
        }

        /* ── PRODUCT TABS ── */
        .section-tabs {
            display: flex;
            gap: 0;
            justify-content: center;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--border);
        }

        .section-tab {
            padding: 12px 28px;
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            color: var(--gray);
            font-family: var(--font-sans, 'DM Sans', sans-serif);
            transition: all .2s;
            margin-bottom: -1px;
        }

        /* Style cho nút Xem Chi Tiết */
        .btn-view-more {
            display: inline-block;
            padding: 10px 28px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--black);
            border: 1px solid var(--black);
            background: transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-view-more:hover {
            background: var(--black);
            color: var(--white);
            letter-spacing: 2px;
            /* Hiệu ứng giãn chữ nhẹ khi hover */
        }

        .section-tab:hover {
            color: var(--black);
        }

        .section-tab.active {
            color: var(--black);
            border-bottom-color: var(--gold);
            font-weight: 500;
        }

        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* ── CATEGORIES STRIP ── */
        .cat-strip {
            padding: 64px 0;
            border-bottom: 1px solid var(--border);
        }

        .cat-strip .container {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .cat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border: 1px solid var(--border);
            font-size: 12px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: var(--transition);
            color: var(--gray);
        }

        .cat-pill:hover {
            border-color: var(--black);
            color: var(--black);
            background: var(--cream);
        }

        /* ── SECTION ── */
        .section {
            padding: 80px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .section-label {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }

        .section-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 48px);
            font-weight: 300;
            color: var(--black);
        }

        /* ── PRODUCT GRID ── */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        @media(max-width:1024px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(max-width:768px) {
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 14px;
            }
        }

        .product-card {
            position: relative;
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
            font-size: 48px;
            color: rgba(0, 0, 0, .15);
        }

        .out-of-stock-badge {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
        }

        .out-of-stock-badge::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .1);
        }

        .out-of-stock-text {
            color: var(--white);
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, .6);
            padding: 8px 20px;
            position: relative;
            z-index: 1;
        }

        .product-card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px;
            background: linear-gradient(to top, rgba(0, 0, 0, .5), transparent);
            opacity: 0;
            transition: var(--transition);
            display: flex;
            justify-content: center;
        }

        .product-card:hover .product-card-overlay {
            opacity: 1;
        }

        .btn-quick-add {
            background: var(--white);
            color: var(--black);
            padding: 10px 24px;
            font-size: 11px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: var(--transition);
            white-space: nowrap;
        }

        .btn-quick-add:hover {
            background: var(--gold);
            color: var(--white);
        }

        .product-info {
            padding: 14px 4px 4px;
        }

        .product-name {
            font-size: 14px;
            font-weight: 400;
            color: var(--black);
            margin-bottom: 6px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 14px;
            color: var(--gold);
            font-weight: 500;
        }

        /* ── BANNER ── */
        .mid-banner {
            margin: 0;
            background: var(--black);
            padding: 80px 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .mid-banner-inner {
            max-width: 600px;
        }

        .mid-banner h2 {
            font-family: var(--font-display);
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 300;
            color: var(--white);
            margin-bottom: 16px;
        }

        .mid-banner p {
            color: rgba(255, 255, 255, .6);
            font-size: 14px;
            margin-bottom: 28px;
            line-height: 1.8;
        }
    </style>
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

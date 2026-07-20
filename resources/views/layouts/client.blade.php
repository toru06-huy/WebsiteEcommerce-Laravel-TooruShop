<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'VELOUR – Thời Trang Cao Cấp')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --black: #1a1a1a;
            --white: #ffffff;
            --cream: #f7f4f0;
            --gold: #b8965a;
            --gold-light: #d4af7a;
            --gray: #6b6b6b;
            --gray-light: #e8e4df;
            --border: #e0dbd4;
            --font-display: 'Cormorant Garamond', serif;
            --font-body: 'DM Sans', sans-serif;
            --header-h: 72px;
            --transition: all .25s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--black);
            background: var(--white);
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
            border: none;
            background: none;
            font-family: var(--font-body);
        }

        img {
            max-width: 100%;
            display: block;
        }

        input,
        select,
        textarea {
            font-family: var(--font-body);
        }

        ul {
            list-style: none;
        }

        /* ===== ANNOUNCEMENT BAR ===== */
        .announcement-bar {
            background: var(--black);
            color: var(--white);
            text-align: center;
            font-size: 12px;
            letter-spacing: 1.5px;
            padding: 8px 20px;
            text-transform: uppercase;
        }

        /* ===== HEADER ===== */
        header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--white);
            border-bottom: 1px solid var(--border);
        }

        .header-inner {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            height: var(--header-h);
            padding: 0 32px;
            max-width: 1440px;
            margin: 0 auto;
        }

        /* LEFT – Actions */
        .header-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-input {
            width: 0;
            border: none;
            border-bottom: 1px solid transparent;
            background: transparent;
            font-size: 13px;
            letter-spacing: .5px;
            padding: 4px 28px 4px 0;
            outline: none;
            transition: var(--transition);
            color: var(--black);
        }

        .search-wrap.open .search-input {
            width: 140px;
            border-bottom-color: var(--black);
        }

        .search-wrap.open .search-category {
            width: 120px !important;
            border-bottom: 1px solid var(--border);
            padding: 4px 4px !important;
            margin-right: 6px;
            cursor: pointer;
        }

        .search-btn {
            position: absolute;
            right: 0;
            color: var(--black);
            font-size: 15px;
            padding: 4px;
            background: none;
        }

        .header-icon-btn {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            letter-spacing: .5px;
            color: var(--black);
            white-space: nowrap;
            padding: 4px 0;
            transition: var(--transition);
            position: relative;
        }

        .header-icon-btn:hover {
            color: var(--gold);
        }

        .header-icon-btn i {
            font-size: 16px;
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            right: -10px;
            background: var(--gold);
            color: var(--white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .cart-wrap {
            position: relative;
        }

        /* CENTER – Logo */
        .logo {
            text-align: center;
            font-family: var(--font-display);
            font-size: 30px;
            font-weight: 600;
            letter-spacing: 6px;
            text-transform: uppercase;
            color: var(--black);
            transition: var(--transition);
        }

        .logo:hover {
            color: var(--gold);
        }

        .logo span {
            color: var(--gold);
        }

        /* RIGHT – Mega Menu */
        .header-right {
            display: flex;
            justify-content: flex-end;
        }

        .nav-menu {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            gap: 4px;
        }

        .nav-item {
            position: relative;
            flex-shrink: 0;
        }

        .nav-link {
            display: block;
            padding: 8px 14px;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-weight: 500;
            color: var(--black);
            transition: var(--transition);
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--gold);
        }

        .nav-item:hover .nav-link {
            color: var(--gold);
        }

        /* Dropdown đơn giản (danh mục không có cấp cháu) */
        .nav-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border: 1px solid var(--border);
            min-width: 200px;
            padding: 12px 0;
            margin-top: 12px;
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .08);
            z-index: 1001;
        }

        /* Vùng đệm vô hình nối liền nav-link và dropdown để chuột di chuyển xuống không bị mất hover */
        .nav-item::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 100%;
            height: 14px;
        }

        .nav-item:hover .nav-dropdown {
            opacity: 1;
            visibility: visible;
        }

        .nav-dropdown a {
            display: block;
            padding: 10px 24px;
            font-size: 13px;
            color: var(--gray);
            letter-spacing: .5px;
            transition: var(--transition);
        }

        .nav-dropdown a:hover {
            color: var(--gold);
            background: var(--cream);
            padding-left: 30px;
        }

        /* Mega dropdown nhiều cột: Danh mục cha -> con -> cháu (VD: Nam -> Áo -> Áo sơ mi) */
        .mega-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            border: 1px solid var(--border);
            padding: 28px 36px;
            margin-top: 12px;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: flex-start;
            gap: 0;
            opacity: 0;
            visibility: hidden;
            transition: opacity .2s ease, visibility .2s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, .1);
            z-index: 1001;
            max-width: 92vw;
            overflow-x: auto;
        }

        .nav-item:hover .mega-dropdown {
            opacity: 1;
            visibility: visible;
        }

        .mega-col {
            min-width: 150px;
            flex-shrink: 0;
            padding: 0 32px;
        }

        .mega-col:first-child {
            padding-left: 0;
        }

        .mega-col:last-child {
            padding-right: 0;
        }

        .mega-col:not(:last-child) {
            border-right: 1px solid var(--border);
        }

        .mega-col-title {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--black);
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
            white-space: nowrap;
        }

        .mega-col-title:hover {
            color: var(--gold);
        }

        .mega-col a.mega-link {
            display: block;
            padding: 6px 0;
            font-size: 13px;
            color: var(--gray);
            letter-spacing: .3px;
            transition: var(--transition);
            white-space: nowrap;
        }

        .mega-col a.mega-link:hover {
            color: var(--gold);
            padding-left: 4px;
        }

        /* ===== MOBILE HEADER ===== */
        @media(max-width:768px) {
            .header-inner {
                grid-template-columns: auto 1fr auto;
                gap: 12px;
                padding: 0 16px;
            }

            .header-right {
                display: none;
            }

            .search-wrap.open .search-input {
                width: 130px;
            }

            .header-left {
                gap: 12px;
            }

            .header-icon-btn span {
                display: none;
            }
        }

        /* ===== FLASH MESSAGES ===== */
        .flash-container {
            position: fixed;
            top: 90px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
        }

        .flash {
            padding: 14px 20px;
            border-radius: 2px;
            font-size: 13px;
            letter-spacing: .3px;
            display: flex;
            align-items: center;
            gap: 10px;
            pointer-events: auto;
            animation: slideIn .3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
            max-width: 360px;
        }

        .flash-success {
            background: var(--black);
            color: var(--white);
        }

        .flash-error {
            background: #c0392b;
            color: var(--white);
        }

        .flash-info {
            background: var(--gold);
            color: var(--white);
        }

        @keyframes slideIn {
            from {
                transform: translateX(30px);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ===== MAIN ===== */
        main {
            min-height: calc(100vh - var(--header-h) - 200px);
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--black);
            color: rgba(255, 255, 255, .7);
            padding: 48px 32px 24px;
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-brand .logo {
            text-align: left;
            color: var(--white);
            font-size: 24px;
        }

        .footer-brand p {
            margin-top: 16px;
            font-size: 13px;
            line-height: 1.8;
        }

        .footer-col h4 {
            color: var(--white);
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 16px;
            font-weight: 500;
        }

        .footer-col a {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            transition: var(--transition);
        }

        .footer-col a:hover {
            color: var(--gold);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, .1);
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
        }

        @media(max-width:768px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 24px;
            }
        }

        /* ===== UTILITIES ===== */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--black);
            color: var(--white);
            border: 1px solid var(--black);
            padding: 14px 32px;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            transition: var(--transition);
            gap: 8px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--gold);
            border-color: var(--gold);
            color: var(--white);
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: var(--black);
            border: 1px solid var(--black);
            padding: 13px 32px;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            transition: var(--transition);
            gap: 8px;
            cursor: pointer;
        }

        .btn-outline:hover {
            background: var(--black);
            color: var(--white);
        }

        .btn-gold {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gold);
            color: var(--white);
            border: 1px solid var(--gold);
            padding: 14px 32px;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            width: 100%;
        }

        .btn-gold:hover {
            background: #a07840;
            border-color: #a07840;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--gray);
            letter-spacing: .5px;
            padding: 16px 0;
            flex-wrap: wrap;
        }

        .breadcrumb a:hover {
            color: var(--gold);
        }

        .breadcrumb span {
            color: var(--black);
        }

        @yield('styles')
    </style>
    @stack('styles')
</head>
@include('client.partials.minigame-widget')
<body>

    <div class="announcement-bar">Miễn phí vận chuyển cho đơn hàng từ 500.000đ &nbsp;|&nbsp; Đổi trả trong 30 ngày</div>

    <header>
        <div class="header-inner">

            {{-- LEFT --}}
            <div class="header-left">
                <div class="search-wrap" id="searchWrap">
                    <input type="text" class="search-input" id="searchInput" placeholder="Tìm kiếm sản phẩm..."
                        autocomplete="off">
                    <button class="search-btn" id="searchBtn" aria-label="Tìm kiếm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
                <a href="tel:19001234" class="header-icon-btn">
                    <i class="fa-solid fa-phone"></i>
                    <span>Liên hệ</span>
                </a>
                @auth
                    <div class="header-icon-btn" style="position:relative; cursor:pointer;" onclick="toggleUserMenu()">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>{{ Auth::user()->fullName }}</span>
                        <div id="userMenu"
                            style="display:none; position:absolute; top:calc(100%+8px); left:0; background:var(--white); border:1px solid var(--border);margin-top: 150px; min-width:180px; padding:8px 0; box-shadow:0 8px 24px rgba(0,0,0,.08); z-index:100;">
                            <form method="POST" action="{{ route('client.logout') }}">
                                @csrf
                                <button type="submit"
                                    style="display:block;width:100%;text-align:left;padding:10px 20px;font-size:13px;color:var(--gray);"
                                    onmouseover="this.style.color='var(--gold)'"
                                    onmouseout="this.style.color='var(--gray)'">
                                    <i class="fa-solid fa-right-from-bracket" style="margin-right:8px;"></i>Đăng xuất
                                </button>
                                <button type="button"
                                    onclick="window.location.href='{{ route('client.profile', Auth::user()->userID) }}'"
                                    style="display:block;width:100%;text-align:left;padding:10px 20px;font-size:13px;color:var(--gray);"
                                    onmouseover="this.style.color='var(--gold)'"
                                    onmouseout="this.style.color='var(--gray)'">
                                    <i class="fa-solid fa-user-pen" style="margin-right:8px;"></i>Thông tin cá nhân
                                </button>
                                <button type="button"
                                    onclick="window.location.href='{{ route('client.profile.orders', Auth::user()->userID) }}'"
                                    style="display:block;width:100%;text-align:left;padding:10px 20px;font-size:13px;color:var(--gray);"
                                    onmouseover="this.style.color='var(--gold)'"
                                    onmouseout="this.style.color='var(--gray)'">
                                    <i class="fa-solid fa-box" style="margin-right:8px;"></i>Đơn hàng của tôi
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('client.login') }}" class="header-icon-btn">
                        <i class="fa-solid fa-circle-user"></i>
                        <span>Đăng nhập</span>
                    </a>
                @endauth
                <a href="{{ route('client.cart') }}" class="header-icon-btn cart-wrap">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span>Giỏ hàng</span>
                    <span class="cart-badge" id="cartBadge">0</span>
                </a>
            </div>

            {{-- CENTER: Logo --}}
            <a href="{{ route('client.home') }}" class="logo">VEL<span>O</span>UR</a>

            {{-- RIGHT: Nav --}}
            <div class="header-right">
                @auth('web')
                    <a href="{{ route('client.wishlist.index') }}" class="header-icon-btn" style="margin-right: 10px">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"
                            viewBox="0 0 24 24">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                        </svg>
                        <span>YÊU THÍCH</span>
                    </a>
                @endauth
                <nav class="nav-menu">
                    @foreach ($rootCategories ?? [] as $cat)
                        <div class="nav-item">
                            <a href="{{ route('client.shop.category', $cat->categoryID) }}" class="nav-link">
                                {{ $cat->categoryName }}
                                @if ($cat->children->count())
                                    <i class="fa-solid fa-chevron-down" style="font-size:9px;margin-left:4px;"></i>
                                @endif
                            </a>

                            @if ($cat->children->count())
                                {{-- Mega dropdown: cột = danh mục con, mỗi cột liệt kê danh mục cháu bên dưới --}}
                                <div class="mega-dropdown">
                                    <div class="mega-col">
                                        <a href="{{ route('client.shop.category', $cat->categoryID) }}"
                                            class="mega-col-title">Tất cả {{ $cat->categoryName }}</a>
                                    </div>
                                    @foreach ($cat->children as $child)
                                        <div class="mega-col">
                                            <a href="{{ route('client.shop.category', $child->categoryID) }}"
                                                class="mega-col-title">{{ $child->categoryName }}</a>
                                            @foreach ($child->children as $grand)
                                                <a href="{{ route('client.shop.category', $grand->categoryID) }}"
                                                    class="mega-link">{{ $grand->categoryName }}</a>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                    <div class="nav-item">
                        <a href="{{ route('client.shop') }}" class="nav-link">Tất cả</a>
                    </div>
                </nav>
            </div>

        </div>
    </header>

    {{-- Flash messages --}}
    <div class="flash-container" id="flashContainer">
        @if (session('success'))
            <div class="flash flash-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="flash flash-error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="flash flash-info"><i class="fa-solid fa-circle-info"></i> {{ session('info') }}</div>
        @endif
        @if (session('order_success'))
            <div class="flash flash-success" style="max-width:420px;"><i class="fa-solid fa-circle-check"></i>
                {{ session('order_success') }}</div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo" style="text-align:left;">VEL<span>O</span>UR</div>
                    <p>Thương hiệu thời trang cao cấp Việt Nam. Chúng tôi tin rằng mỗi bộ trang phục là một câu chuyện.
                    </p>
                </div>
                <div class="footer-col">
                    <h4>Khám phá</h4>
                    <a href="{{ route('client.shop') }}">Tất cả sản phẩm</a>
                    @foreach ($rootCategories ?? [] as $cat)
                        <a href="{{ route('client.shop.category', $cat->categoryID) }}">{{ $cat->categoryName }}</a>
                    @endforeach
                </div>
                <div class="footer-col">
                    <h4>Hỗ trợ</h4>
                    <a href="#">Hướng dẫn đặt hàng</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Bảng size</a>
                    <a href="#">Liên hệ</a>
                </div>
                <div class="footer-col">
                    <h4>Liên hệ</h4>
                    <a href="tel:19001234"><i class="fa-solid fa-phone" style="margin-right:6px;"></i>1900 1234</a>
                    <a href="mailto:hello@velour.vn"><i class="fa-solid fa-envelope"
                            style="margin-right:6px;"></i>hello@velour.vn</a>
                    <div style="display:flex;gap:12px;margin-top:12px;">
                        <a href="#" style="font-size:18px;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="font-size:18px;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="font-size:18px;"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ date('Y') }} VELOUR. Bảo lưu mọi quyền.</span>
                <span>Thiết kế tại Việt Nam 🇻🇳</span>
            </div>
        </div>
    </footer>

    <script>
        // Search toggle
        const searchWrap = document.getElementById('searchWrap');
        const searchInput = document.getElementById('searchInput');
        const searchCategory = document.getElementById('searchCategory');
        const searchBtn = document.getElementById('searchBtn');

        // Map categoryID -> URL danh mục tương ứng (dùng để tìm kiếm trong 1 danh mục cụ thể)
        const categoryRoutes = {
            @foreach ($rootCategories ?? [] as $cat)
                '{{ $cat->categoryID }}': '{{ route('client.shop.category', $cat->categoryID) }}',
                @foreach ($cat->children as $child)
                    '{{ $child->categoryID }}': '{{ route('client.shop.category', $child->categoryID) }}',
                    @foreach ($child->children as $grand)
                        '{{ $grand->categoryID }}': '{{ route('client.shop.category', $grand->categoryID) }}',
                    @endforeach
                @endforeach
            @endforeach
        };

        function buildSearchUrl() {
            const q = searchInput.value.trim();
            const catId = searchCategory ? searchCategory.value : '';
            const base = catId && categoryRoutes[catId] ? categoryRoutes[catId] : '{{ route('client.shop') }}';
            return q ? base + '?search=' + encodeURIComponent(q) : base;
        }

        searchBtn.addEventListener('click', () => {
            if (searchWrap.classList.contains('open')) {
                const q = searchInput.value.trim();
                const catId = searchCategory ? searchCategory.value : '';
                if (q || catId) window.location.href = buildSearchUrl();
                else {
                    searchWrap.classList.remove('open');
                    searchInput.blur();
                }
            } else {
                searchWrap.classList.add('open');
                searchInput.focus();
            }
        });
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                const q = searchInput.value.trim();
                const catId = searchCategory ? searchCategory.value : '';
                if (q || catId) window.location.href = buildSearchUrl();
            }
            if (e.key === 'Escape') {
                searchWrap.classList.remove('open');
                searchInput.blur();
            }
        });
        if (searchCategory) {
            searchCategory.addEventListener('change', () => {
                searchInput.focus();
            });
        }

        // User menu toggle
        function toggleUserMenu() {
            const m = document.getElementById('userMenu');
            m.style.display = m.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            const um = document.getElementById('userMenu');
            if (um && !um.closest('.header-icon-btn')?.contains(e.target)) um.style.display = 'none';
        });

        // Flash auto-hide
        setTimeout(() => {
            document.querySelectorAll('.flash').forEach(el => el.remove());
        }, 5000);

        // Cart count
        async function updateCartCount() {
            try {
                const r = await fetch('{{ route('client.cart.count') }}');
                const d = await r.json();
                document.getElementById('cartBadge').textContent = d.count || 0;
            } catch {}
        }
        updateCartCount();

        // CSRF helper
        window.csrf = '{{ csrf_token() }}';
    </script>

    @stack('scripts')

    <script>
        // ── Wishlist toggle (global) ──────────────────────────────────────────────────
        async function toggleWishlist(productId, btn) {
            @auth('web')
                try {
                    const res = await fetch('{{ route('client.wishlist.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ||
                                '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            productID: productId
                        }),
                    });
                    const d = await res.json();
                    if (!d.success) return;

                    const svg = btn.querySelector('svg');
                    const on = d.wishlisted;
                    btn.classList.toggle('wishlisted', on);
                    svg.setAttribute('fill', on ? '#e74c3c' : 'none');
                    svg.setAttribute('stroke', on ? '#e74c3c' : '#666');
                    btn.title = on ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích';
                } catch (e) {
                    console.error(e);
                }
            @else
                window.location = '{{ route('client.login') }}';
            @endauth
        }
    </script>
</body>

</html>
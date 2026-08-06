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
    @vite(['resources/css/client/layout.css'])
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
                <a href="https://zalo.me/0931462157" target="_blank" class="header-icon-btn">
                    <i class="fa-solid fa-phone"></i>
                    <span>Zalo</span>
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
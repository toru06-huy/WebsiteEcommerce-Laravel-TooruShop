<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — VELOUR Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/admin/admin.css'])
    @stack('styles')
</head>
<body>

<!-- ═══ SIDEBAR ═══ -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-head">
        <div class="brand-icon"><span></span></div>
        <span class="brand-name">Velour</span>
    </div>

    <div class="sidebar-user">
        <div class="u-name">{{ Auth::user()->fullName }}</div>
        <div class="u-role">{{ Auth::user()->role }}</div>
        @if(Auth::user()->role === 'Employee' && Auth::user()->employee)
            <div class="u-code">Mã NV: {{ Auth::user()->employee->employeeCode }}</div>
        @endif
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section">Tổng quan</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>
<div class="nav-section">Catalogue</div>
        @if(in_array(Auth::user()->role, ['Admin', 'Owner']))
        <a href="{{ route('admin.categories.index') }}"
           class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            Danh mục
        </a>
        
        <a href="{{ route('admin.manufacturers.index') }}"
           class="nav-item {{ request()->routeIs('admin.manufacturers.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Nhà cung cấp
        </a>

        @endif
<a href="{{ route('admin.products.index') }}"
           class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
            </svg>
            Sản phẩm
        </a>
        <div class="nav-section">Vận hành</div>
        <a href="{{ route('admin.orders.index') }}"
           class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            Đơn hàng
        </a>

        @if(in_array(Auth::user()->role, ['Admin', 'Owner']))
        <div class="nav-section">Marketing</div>
        <a href="{{ route('admin.discounts.index') }}"
           class="nav-item {{ request()->routeIs('admin.discounts.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/>
                <line x1="7.5" y1="7.5" x2="7.5" y2="7.5"/>
                <circle cx="7.5" cy="7.5" r="1.5"/>
            </svg>
            Mã giảm giá
        </a>
        <a href="{{ route('admin.product-discounts.index') }}"
           class="nav-item {{ request()->routeIs('admin.product-discounts.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z"/>
                <circle cx="7.5" cy="7.5" r="1.5"/>
            </svg>
            Giảm giá sản phẩm
        </a>


        @endif

        @if(in_array(Auth::user()->role, ['Admin', 'Owner']))
        <div class="nav-section">Quản trị</div>
        <a href="{{ route('admin.employees.index') }}"
           class="nav-item {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            Nhân viên
        </a>
        @endif
        
        @if(Auth::user()->role === 'Admin')
        <a href="{{ route('admin.users.index') }}"
           class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            Người dùng
        </a>
        @endif

    </nav>

    <div class="sidebar-foot">
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>

<!-- ═══ MAIN ═══ -->
<div class="main">

    <header class="topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="breadcrumb">@yield('breadcrumb', 'VELOUR / Admin')</div>
        </div>
        <div class="topbar-right">
            @yield('topbar-actions')
        </div>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="flash success">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="flash error">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script>
    // Close sidebar on outside click (mobile)
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 900 && !sidebar.contains(e.target) && !e.target.closest('.sidebar-toggle')) {
            sidebar.classList.remove('open');
        }
    });

    // Delete confirmation
    function confirmDelete(form, name) {
        if (confirm('Bạn chắc chắn muốn xóa "' + name + '"?')) {
            form.submit();
        }
    }

    // Modal helpers
    function openModal(id) { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }

    document.querySelectorAll('.modal-overlay').forEach(el => {
        el.addEventListener('click', function(e) {
            if (e.target === el) el.classList.remove('open');
        });
    });
</script>
@stack('scripts')
</body>
</html>
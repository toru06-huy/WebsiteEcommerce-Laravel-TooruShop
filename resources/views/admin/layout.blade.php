<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — VELOUR Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --ink:       #0e0c0a;
            --sidebar:   #111009;
            --sand:      #f5f0e8;
            --cream:     #faf8f4;
            --gold:      #b8955a;
            --gold-lt:   #d4b07a;
            --muted:     #8a8278;
            --border:    #e0d9ce;
            --surface:   #ffffff;
            --bg:        #f2ede4;
            --danger:    #c0392b;
            --success:   #27ae60;
            --warning:   #e67e22;
            --info:      #2980b9;
            --sidebar-w: 260px;
        }

        html, body { height: 100%; font-family: 'DM Sans', sans-serif; background: var(--bg); color: var(--ink); }

        /* ══════ SIDEBAR ══════ */
        .sidebar {
            position: fixed; top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar);
            display: flex; flex-direction: column;
            z-index: 100;
            transition: transform .3s ease;
        }

        .sidebar-head {
            padding: 28px 24px 20px;
            border-bottom: 1px solid rgba(184,149,90,.15);
            display: flex; align-items: center; gap: 12px;
        }

        .brand-icon {
            width: 34px; height: 34px;
            border: 1px solid var(--gold);
            display: grid; place-items: center;
            transform: rotate(45deg);
            flex-shrink: 0;
        }
        .brand-icon span {
            width: 12px; height: 12px;
            background: var(--gold);
            transform: rotate(-45deg) scale(.7);
            display: block;
        }

        .brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px; font-weight: 300;
            letter-spacing: .3em; text-transform: uppercase;
            color: var(--sand);
        }

        .sidebar-user {
            margin: 16px 16px 8px;
            padding: 14px 16px;
            background: rgba(184,149,90,.08);
            border: 1px solid rgba(184,149,90,.12);
            border-radius: 4px;
        }

        .sidebar-user .u-name {
            font-size: 13.5px; font-weight: 500;
            color: var(--sand);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .sidebar-user .u-role {
            font-size: 11px; font-weight: 500;
            letter-spacing: .12em; text-transform: uppercase;
            color: var(--gold);
            margin-top: 3px;
        }

        /* Employee badge */
        .sidebar-user .u-code {
            font-size: 11px; color: rgba(245,240,232,.4);
            margin-top: 2px;
        }

        /* ── NAV ── */
        .sidebar-nav {
            flex: 1; overflow-y: auto; padding: 12px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(184,149,90,.2) transparent;
        }

        .nav-section {
            padding: 16px 16px 6px;
            font-size: 10px; font-weight: 500;
            letter-spacing: .2em; text-transform: uppercase;
            color: rgba(245,240,232,.25);
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 16px;
            color: rgba(245,240,232,.55);
            text-decoration: none;
            font-size: 13.5px; font-weight: 400;
            transition: color .15s, background .15s;
            border-radius: 4px; margin: 1px 8px;
            position: relative;
        }

        .nav-item:hover {
            color: var(--sand);
            background: rgba(255,255,255,.05);
        }

        .nav-item.active {
            color: var(--gold-lt);
            background: rgba(184,149,90,.12);
        }

        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 20%; bottom: 20%;
            width: 2px; background: var(--gold);
            border-radius: 0 2px 2px 0;
            margin-left: -8px;
        }

        .nav-item svg { flex-shrink: 0; opacity: .8; }
        .nav-item.active svg { opacity: 1; }

        /* ── SIDEBAR FOOTER ── */
        .sidebar-foot {
            padding: 16px;
            border-top: 1px solid rgba(184,149,90,.12);
        }

        .btn-logout {
            display: flex; align-items: center; gap: 8px;
            width: 100%; padding: 10px 14px;
            background: rgba(192,57,43,.1);
            border: 1px solid rgba(192,57,43,.25);
            border-radius: 4px;
            color: #e8766a;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; font-weight: 500;
            cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .btn-logout:hover { background: rgba(192,57,43,.2); }

        /* ══════ MAIN ══════ */
        .main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── TOPBAR ── */
        .topbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 32px;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }

        .topbar-left h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-weight: 400;
            color: var(--ink);
        }

        .topbar-left .breadcrumb {
            font-size: 12px; color: var(--muted);
            margin-top: 1px;
        }

        .topbar-right { display: flex; align-items: center; gap: 12px; }

        .topbar-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 16px;
            background: var(--ink); color: var(--sand);
            border: none; border-radius: 2px;
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 500;
            letter-spacing: .1em; text-transform: uppercase;
            cursor: pointer; text-decoration: none;
            transition: background .2s;
        }
        .topbar-btn:hover { background: #1a1508; }

        .topbar-btn.secondary {
            background: transparent;
            color: var(--ink);
            border: 1px solid var(--border);
        }
        .topbar-btn.secondary:hover { background: var(--sand); }

        /* ── CONTENT ── */
        .content { flex: 1; padding: 32px; }

        /* ── FLASH MESSAGES ── */
        .flash {
            padding: 12px 18px;
            border-radius: 4px;
            font-size: 13.5px;
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
        }
        .flash.success { background: #edfaf3; border-left: 3px solid var(--success); color: #1e6e42; }
        .flash.error   { background: #fdf2f1; border-left: 3px solid var(--danger);  color: var(--danger); }
        .flash.warning { background: #fef9ec; border-left: 3px solid var(--warning); color: #935c00; }

        /* ══════ STAT CARDS ══════ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 24px;
            border-radius: 4px;
            position: relative;
            overflow: hidden;
            transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.07); }

        .stat-card::before {
            content: '';
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 3px;
        }
        .stat-card.gold::before   { background: var(--gold); }
        .stat-card.green::before  { background: var(--success); }
        .stat-card.blue::before   { background: var(--info); }
        .stat-card.red::before    { background: var(--danger); }

        .stat-label {
            font-size: 11px; font-weight: 500;
            letter-spacing: .15em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 10px;
        }

        .stat-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 42px; font-weight: 300;
            line-height: 1; color: var(--ink);
        }

        .stat-sub {
            font-size: 12px; color: var(--muted);
            margin-top: 6px;
        }

        .stat-icon {
            position: absolute; top: 20px; right: 20px;
            color: var(--border);
        }

        /* ══════ TABLE ══════ */
        .table-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .table-head {
            padding: 20px 24px;
            display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .table-head h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 20px; font-weight: 400;
        }

        .table-actions { display: flex; gap: 10px; align-items: center; }

        .search-box {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 14px;
            border: 1px solid var(--border);
            border-radius: 2px;
            background: var(--cream);
        }
        .search-box input {
            border: none; background: transparent;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px; outline: none; color: var(--ink);
            width: 180px;
        }
        .search-box input::placeholder { color: #c0b8ae; }

        table {
            width: 100%; border-collapse: collapse;
        }

        thead th {
            padding: 12px 16px;
            font-size: 10.5px; font-weight: 500;
            letter-spacing: .14em; text-transform: uppercase;
            color: var(--muted);
            text-align: left;
            background: var(--cream);
            border-bottom: 1px solid var(--border);
        }

        tbody tr {
            border-bottom: 1px solid #f0ebe3;
            transition: background .15s;
        }
        tbody tr:hover { background: #fdf9f4; }
        tbody tr:last-child { border-bottom: none; }

        td {
            padding: 14px 16px;
            font-size: 13.5px;
            color: var(--ink);
            vertical-align: middle;
        }

        .td-muted { color: var(--muted); font-size: 13px; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px; font-weight: 500;
            letter-spacing: .06em;
        }
        .badge.active   { background: #edfaf3; color: #1a7a45; }
        .badge.inactive { background: #fef2f2; color: #c0392b; }
        .badge.admin    { background: rgba(184,149,90,.15); color: #7a5c2a; }
        .badge.employee { background: rgba(41,128,185,.12); color: #1a5276; }
        .badge.customer { background: #f0f0f0; color: #555; }

        /* ── ACTION BUTTONS ── */
        .action-btns { display: flex; gap: 6px; }

        .btn-icon {
            width: 32px; height: 32px;
            display: grid; place-items: center;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            color: var(--muted);
            text-decoration: none;
            transition: all .15s;
        }
        .btn-icon:hover { background: var(--ink); color: #fff; border-color: var(--ink); }
        .btn-icon.danger:hover { background: var(--danger); border-color: var(--danger); color: #fff; }

        /* ── PAGINATION ── */
        .pagination-wrap {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            font-size: 13px; color: var(--muted);
        }

        .pagination { display: flex; gap: 4px; }

        .page-btn {
            width: 32px; height: 32px;
            display: grid; place-items: center;
            border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            color: var(--ink);
            background: #fff;
            transition: all .15s;
        }
        .page-btn:hover, .page-btn.active {
            background: var(--ink); color: #fff; border-color: var(--ink);
        }

        /* ══════ MODAL ══════ */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(14,12,10,.6);
            backdrop-filter: blur(4px);
            z-index: 200;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }

        .modal {
            background: var(--surface);
            border-radius: 4px;
            width: 560px; max-width: 95vw;
            max-height: 90vh; overflow-y: auto;
            animation: modalIn .25s ease;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(.96) translateY(10px); }
            to   { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-head {
            padding: 24px 28px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .modal-head h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px; font-weight: 400;
        }

        .modal-close {
            width: 32px; height: 32px;
            display: grid; place-items: center;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: transparent; cursor: pointer;
            color: var(--muted);
            transition: all .15s;
        }
        .modal-close:hover { background: var(--danger); color: #fff; border-color: var(--danger); }

        .modal-body { padding: 28px; }

        .modal-foot {
            padding: 20px 28px;
            border-top: 1px solid var(--border);
            display: flex; gap: 10px; justify-content: flex-end;
        }

        /* ══════ FORM ELEMENTS ══════ */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .form-grid.cols-1 { grid-template-columns: 1fr; }
        .span-2 { grid-column: span 2; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-group label {
            font-size: 11px; font-weight: 500;
            letter-spacing: .14em; text-transform: uppercase;
            color: var(--muted);
        }

        .form-control {
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 2px;
            background: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px; color: var(--ink);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(184,149,90,.1);
        }

        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        .form-control.error { border-color: var(--danger); }
        .form-hint { font-size: 12px; color: var(--muted); margin-top: 2px; }

        /* ══════ EMPTY STATE ══════ */
        .empty-state {
            text-align: center; padding: 64px 32px;
            color: var(--muted);
        }
        .empty-state svg { margin-bottom: 16px; opacity: .3; }
        .empty-state h3 { font-family: 'Cormorant Garamond', serif; font-size: 22px; color: var(--ink); margin-bottom: 6px; }
        .empty-state p { font-size: 13.5px; }

        /* ══════ UTILITY ══════ */
        .flex { display: flex; }
        .gap-2 { gap: 8px; }
        .items-center { align-items: center; }
        .ml-auto { margin-left: auto; }

        /* ══════ SIDEBAR TOGGLE (mobile) ══════ */
        .sidebar-toggle {
            display: none;
            width: 40px; height: 40px;
            background: none; border: 1px solid var(--border);
            border-radius: 4px;
            cursor: pointer;
            align-items: center; justify-content: center;
        }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main { margin-left: 0; }
            .sidebar-toggle { display: flex; }
        }
    </style>
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
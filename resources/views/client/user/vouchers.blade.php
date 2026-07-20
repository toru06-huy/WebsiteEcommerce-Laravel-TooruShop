@extends('layouts.client')

@section('title', 'Mã giảm giá của tôi')

@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

        :root {
            --cream: #faf8f5;
            --sand: #ede9e2;
            --stone: #c9c3b8;
            --charcoal: #2b2b2b;
            --ink: #1a1a1a;
            --sage: #7a9e87;
            --sage-light: #eef3f0;
            --gold: #c9a84c;
            --error: #c0392b;
            --radius: 10px;
        }

        body {
            background: var(--cream);
            color: var(--charcoal);
            font-family: 'DM Sans', sans-serif;
        }

        .profile-page {
            max-width: 1100px;
            margin: 0 auto;
            padding: 56px 24px 80px;
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 40px;
            align-items: start;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: sticky;
            top: 80px;
            background: #fff;
            border: 1px solid var(--sand);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .sidebar-avatar {
            background: var(--sand);
            padding: 36px 24px;
            text-align: center;
            border-bottom: 1px solid var(--sand);
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--sage);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            color: #fff;
            margin: 0 auto 12px;
        }

        .sidebar-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--ink);
        }

        .sidebar-role {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--stone);
            margin-top: 4px;
        }

        .sidebar-nav {
            padding: 12px 0;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            font-size: 0.875rem;
            color: var(--charcoal);
            text-decoration: none;
            transition: background 0.15s, color 0.15s;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: var(--sage-light);
            color: var(--sage);
        }

        .sidebar-nav a svg {
            opacity: 0.6;
            flex-shrink: 0;
        }

        /* ── Main ── */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .page-header {
            margin-bottom: 4px;
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--ink);
        }

        .page-sub {
            font-size: 0.85rem;
            color: var(--stone);
            margin-top: 4px;
        }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            gap: 4px;
            border-bottom: 1px solid var(--sand);
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 10px 20px;
            font-size: 0.875rem;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            color: var(--stone);
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s;
            margin-bottom: -1px;
        }

        .tab-btn.active {
            color: var(--sage);
            border-bottom-color: var(--sage);
            font-weight: 500;
        }

        /* ── Voucher Card ── */
        .voucher-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .voucher-card {
            background: #fff;
            border: 1px solid var(--sand);
            border-radius: var(--radius);
            display: flex;
            overflow: hidden;
            position: relative;
            transition: box-shadow 0.2s;
        }

        .voucher-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .voucher-card.used {
            opacity: 0.55;
        }

        .voucher-card.expired {
            opacity: 0.55;
        }

        /* Dải màu trái */
        .voucher-strip {
            width: 6px;
            flex-shrink: 0;
            background: var(--sage);
        }

        .voucher-card.used .voucher-strip {
            background: var(--stone);
        }

        .voucher-card.expired .voucher-strip {
            background: var(--stone);
        }

        /* Phần mã + icon */
        .voucher-left {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-width: 130px;
            border-right: 1.5px dashed var(--sand);
            gap: 6px;
        }

        .voucher-icon {
            font-size: 1.8rem;
        }

        .voucher-code {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--gold);
            letter-spacing: 1px;
            text-align: center;
            word-break: break-all;
        }

        .voucher-card.used .voucher-code {
            color: var(--stone);
        }

        .voucher-card.expired .voucher-code {
            color: var(--stone);
        }

        /* Phần nội dung */
        .voucher-body {
            padding: 18px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .voucher-name {
            font-weight: 500;
            font-size: 0.95rem;
            color: var(--ink);
        }

        .voucher-value {
            font-size: 1.1rem;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            color: var(--sage);
        }

        .voucher-card.used .voucher-value {
            color: var(--stone);
        }

        .voucher-card.expired .voucher-value {
            color: var(--stone);
        }

        .voucher-meta {
            font-size: 0.78rem;
            color: var(--stone);
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Badge trạng thái */
        .voucher-status {
            position: absolute;
            top: 14px;
            right: 16px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .status-active {
            background: var(--sage-light);
            color: var(--sage);
        }

        .status-used {
            background: #f0f0f0;
            color: #999;
        }

        .status-expired {
            background: #fdf2f2;
            color: var(--error);
        }

        /* Nút copy */
        .btn-copy {
            margin-top: auto;
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border: 1.5px solid var(--sage);
            border-radius: 6px;
            font-size: 0.8rem;
            color: var(--sage);
            background: none;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.15s;
        }

        .btn-copy:hover {
            background: var(--sage-light);
        }

        .btn-copy.copied {
            border-color: var(--stone);
            color: var(--stone);
            cursor: default;
        }

        /* Empty */
        .empty-voucher {
            text-align: center;
            padding: 64px 24px;
            color: var(--stone);
        }

        .empty-voucher svg {
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-voucher h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            color: var(--charcoal);
            margin-bottom: 8px;
        }

        .empty-voucher p {
            font-size: 0.875rem;
        }

        /* Tier badge */
        .tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .tier-bronze {
            background: #f0e6d9;
            color: #8b5e3c;
        }

        .tier-silver {
            background: #eaecef;
            color: #5a6472;
        }

        .tier-gold {
            background: #fdf3d0;
            color: #a07c10;
        }

        .tier-platinum {
            background: #e8f0f7;
            color: #2c5f8a;
        }

        @media (max-width: 768px) {
            .profile-page {
                grid-template-columns: 1fr;
                padding: 24px 16px;
            }

            .sidebar {
                position: static;
            }

            .voucher-left {
                min-width: 100px;
                padding: 16px;
            }
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
                @if ($user->membership)
                    @php $tier = $user->membership->tier; @endphp
                    <div class="tier-badge tier-{{ strtolower($tier) }}">
                        @if ($tier === 'Bronze')
                            🥉
                        @elseif($tier === 'Silver')
                            🥈
                        @elseif($tier === 'Gold')
                            🥇
                        @else
                            💎
                        @endif
                        {{ $tier }}
                    </div>
                @endif
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('client.profile', $user->userID) }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Hồ sơ cá nhân
                </a>
                <a href="{{ route('client.profile.orders', $user->userID) }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    Đơn hàng của tôi
                </a>
                <a href="{{ route('client.profile.vouchers', $user->userID) }}" class="active">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="1.8">
                        <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z" />
                        <circle cx="7.5" cy="7.5" r="1.5" />
                    </svg>
                    Mã giảm giá của tôi
                </a>
                <a href="{{ route('client.wishlist.index') }}"
                    class="{{ request()->routeIs('client.wishlist.index') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                    </svg>
                    Sản phẩm yêu thích
                </a>
            </nav>
        </aside>

        {{-- ── Main ── --}}
        <div class="main-content">

            <div class="page-header">
                <div class="page-title">Mã giảm giá của tôi</div>
                <div class="page-sub">Các mã giảm giá được thưởng khi lên hạng thành viên</div>
            </div>

            @php
                $available = $userDiscounts->filter(
                    fn($ud) => !$ud->isUsed &&
                        $ud->discount &&
                        $ud->discount->isActive &&
                        $ud->discount->endDate >= $now,
                );
                $used = $userDiscounts->filter(fn($ud) => $ud->isUsed);
                $expired = $userDiscounts->filter(
                    fn($ud) => !$ud->isUsed &&
                        $ud->discount &&
                        ($ud->discount->endDate < $now || !$ud->discount->isActive),
                );
            @endphp

            {{-- Tabs --}}
            <div class="tabs">
                <button class="tab-btn active" onclick="switchTab('available', this)">
                    Có thể dùng ({{ $available->count() }})
                </button>
                <button class="tab-btn" onclick="switchTab('used', this)">
                    Đã dùng ({{ $used->count() }})
                </button>
                <button class="tab-btn" onclick="switchTab('expired', this)">
                    Hết hạn ({{ $expired->count() }})
                </button>
            </div>

            {{-- Tab: Có thể dùng --}}
            <div id="tab-available" class="voucher-grid">
                @forelse($available as $ud)
                    @php $d = $ud->discount; @endphp
                    <div class="voucher-card">
                        <div class="voucher-strip"></div>
                        <div class="voucher-left">
                            <div class="voucher-icon">🎁</div>
                            <div class="voucher-code">{{ $d->discountCode }}</div>
                        </div>
                        <div class="voucher-body">
                            <div class="voucher-name">{{ $d->discountName }}</div>
                            <div class="voucher-value">
                                Giảm {{ number_format($d->discountValue, 0) }}%
                            </div>
                            <div class="voucher-meta">
                                @if ($d->minOrderValue > 0)
                                    <span>Đơn tối thiểu {{ number_format($d->minOrderValue, 0, ',', '.') }}đ</span>
                                @endif
                                <span>Hết hạn {{ $d->endDate->format('d/m/Y') }}</span>
                                <span>Còn {{ $d->endDate->diffInDays($now) + 1 }} ngày</span>
                            </div>
                            <button class="btn-copy" onclick="copyCode('{{ $d->discountCode }}', this)">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                </svg>
                                Sao chép mã
                            </button>
                        </div>
                        <span class="voucher-status status-active">Có thể dùng</span>
                    </div>
                @empty
                    <div class="empty-voucher">
                        <svg width="56" height="56" fill="none" stroke="currentColor" stroke-width="1"
                            viewBox="0 0 24 24">
                            <path d="M20.59 13.41L11 4H4v7l9.59 9.59a2 2 0 0 0 2.82 0l4.18-4.18a2 2 0 0 0 0-2.82z" />
                            <circle cx="7.5" cy="7.5" r="1.5" />
                        </svg>
                        <h3>Chưa có mã giảm giá</h3>
                        <p>Mua sắm và lên hạng thành viên để nhận mã giảm giá thưởng.</p>
                    </div>
                @endforelse
            </div>

            {{-- Tab: Đã dùng --}}
            <div id="tab-used" class="voucher-grid" style="display:none;">
                @forelse($used as $ud)
                    @php $d = $ud->discount; @endphp
                    <div class="voucher-card used">
                        <div class="voucher-strip"></div>
                        <div class="voucher-left">
                            <div class="voucher-icon">✅</div>
                            <div class="voucher-code">{{ $d->discountCode }}</div>
                        </div>
                        <div class="voucher-body">
                            <div class="voucher-name">{{ $d->discountName }}</div>
                            <div class="voucher-value">Giảm {{ number_format($d->discountValue, 0) }}%</div>
                            <div class="voucher-meta">
                                <span>Đã dùng {{ $ud->usedAt?->format('d/m/Y H:i') ?? '—' }}</span>
                            </div>
                        </div>
                        <span class="voucher-status status-used">Đã dùng</span>
                    </div>
                @empty
                    <div class="empty-voucher">
                        <h3>Chưa dùng mã nào</h3>
                    </div>
                @endforelse
            </div>

            {{-- Tab: Hết hạn --}}
            <div id="tab-expired" class="voucher-grid" style="display:none;">
                @forelse($expired as $ud)
                    @php $d = $ud->discount; @endphp
                    <div class="voucher-card expired">
                        <div class="voucher-strip"></div>
                        <div class="voucher-left">
                            <div class="voucher-icon">⏰</div>
                            <div class="voucher-code">{{ $d->discountCode }}</div>
                        </div>
                        <div class="voucher-body">
                            <div class="voucher-name">{{ $d->discountName }}</div>
                            <div class="voucher-value">Giảm {{ number_format($d->discountValue, 0) }}%</div>
                            <div class="voucher-meta">
                                <span>Hết hạn {{ $d->endDate->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <span class="voucher-status status-expired">Hết hạn</span>
                    </div>
                @empty
                    <div class="empty-voucher">
                        <h3>Không có mã hết hạn</h3>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function switchTab(tab, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            ['available', 'used', 'expired'].forEach(t => {
                document.getElementById('tab-' + t).style.display = t === tab ? 'flex' : 'none';
                document.getElementById('tab-' + t).style.flexDirection = 'column';
            });
        }

        function copyCode(code, btn) {
            navigator.clipboard.writeText(code).then(() => {
                btn.classList.add('copied');
                btn.innerHTML =
                    `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg> Đã sao chép`;
                setTimeout(() => {
                    btn.classList.remove('copied');
                    btn.innerHTML =
                        `<svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg> Sao chép mã`;
                }, 2000);
            });
        }
    </script>
@endpush

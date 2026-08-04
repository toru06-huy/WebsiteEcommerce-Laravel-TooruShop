@extends('layouts.client')

@section('title', 'Mã giảm giá của tôi')

@push('styles')
    @vite(['resources/css/client/user/voucher.css'])
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
                                @if($d->discountType=='percentage')
                                Giảm {{ number_format($d->discountValue, 0) }} %
                                @else
                                Giảm {{ number_format($d->discountValue, 0) }} vnđ  
                                @endif
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
                            <div class="voucher-value">
                                @if($d->discountType=='percentage')
                                Giảm {{ number_format($d->discountValue, 0) }} %
                                @else
                                Giảm {{ number_format($d->discountValue, 0) }} vnđ  
                                @endif
                            </div>
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
                            <div class="voucher-value">
                                @if($d->discountType=='percentage')
                                Giảm {{ number_format($d->discountValue, 0) }} %
                                @else
                                Giảm {{ number_format($d->discountValue, 0) }} vnđ  
                                @endif
                            </div>
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

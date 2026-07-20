{{-- resources/views/client/user/orders.blade.php --}}
@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

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
            --radius: 10px;
        }

        body {
            background: var(--cream);
            color: var(--charcoal);
            font-family: 'DM Sans', sans-serif;
        }

        .orders-page {
            max-width: 900px;
            margin: 0 auto;
            padding: 56px 24px 80px;
        }

        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 36px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--sand);
        }

        .page-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--ink);
        }

        .page-count {
            font-size: 0.82rem;
            color: var(--stone);
            margin-top: 4px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--stone);
            text-decoration: none;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: var(--sage);
        }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 28px;
            background: var(--sand);
            padding: 4px;
            border-radius: 8px;
            overflow-x: auto;
        }

        .tab {
            flex: 1;
            min-width: 100px;
            padding: 9px 16px;
            border-radius: 6px;
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            background: transparent;
            color: var(--stone);
            transition: all 0.2s;
            white-space: nowrap;
            text-align: center;
        }

        .tab.active {
            background: #fff;
            color: var(--ink);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        /* ── Order Card ── */
        .order-card {
            background: #fff;
            border: 1px solid var(--sand);
            border-radius: var(--radius);
            margin-bottom: 16px;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .order-card:hover {
            border-color: var(--stone);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        }

        .order-card-header {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sand);
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-id {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            font-weight: 600;
            color: var(--ink);
        }

        .order-date {
            font-size: 0.78rem;
            color: var(--stone);
            margin-top: 2px;
        }

        /* ── Status Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .badge-pending {
            background: #fef9ec;
            color: #b45309;
        }

        .badge-pending .badge-dot {
            background: #f59e0b;
        }

        .badge-cancel-requested {
            background: #fff1f0;
            color: #c0392b;
        }

        .badge-cancel-requested .badge-dot {
            background: #c0392b;
        }

        .badge-confirmed {
            background: #eff6ff;
            color: #1d4ed8;
        }

        .badge-confirmed .badge-dot {
            background: #3b82f6;
        }

        .badge-shipping {
            background: #f0f9ff;
            color: #0369a1;
        }

        .badge-shipping .badge-dot {
            background: #0ea5e9;
        }

        .badge-completed {
            background: var(--sage-light);
            color: #4a7a5a;
        }

        .badge-completed .badge-dot {
            background: var(--sage);
        }

        .badge-cancelled {
            background: #f9f9f9;
            color: #9ca3af;
        }

        .badge-cancelled .badge-dot {
            background: #d1d5db;
        }

        /* ── Order Body ── */
        .order-card-body {
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-meta {
            font-size: 0.82rem;
            color: var(--stone);
        }

        .order-amount {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--ink);
        }

        .order-discount-note {
            font-size: 0.75rem;
            color: var(--sage);
            margin-top: 1px;
        }

        /* ── Order Actions ── */
        .order-card-footer {
            padding: 12px 24px;
            border-top: 1px solid var(--sand);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--cream);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 7px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.82rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-ghost {
            background: transparent;
            color: var(--charcoal);
            border: 1.5px solid var(--sand);
        }

        .btn-ghost:hover {
            border-color: var(--stone);
        }

        .btn-danger-ghost {
            background: transparent;
            color: #c0392b;
            border: 1.5px solid #f4c3be;
        }

        .btn-danger-ghost:hover {
            background: #fff1f0;
        }

        .btn-sage {
            background: var(--sage);
            color: #fff;
        }

        .btn-sage:hover {
            background: #6a8e77;
        }

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
            color: var(--stone);
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            opacity: 0.35;
        }

        .empty-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem;
            color: var(--charcoal);
            margin-bottom: 8px;
        }

        .empty-text {
            font-size: 0.875rem;
        }

        /* ── Alert ── */
        .alert-success {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 13px 18px;
            border-radius: 8px;
            font-size: 0.875rem;
            background: var(--sage-light);
            color: #4a7a5a;
            border: 1px solid #b8d4c0;
            margin-bottom: 20px;
        }

        @media (max-width: 600px) {

            .order-card-header,
            .order-card-body,
            .order-card-footer {
                padding: 14px 16px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="orders-page">

        <div class="page-header">
            <div>
                <div class="page-title">Đơn hàng của tôi</div>
                <div class="page-count">{{ $orders->count() }} đơn hàng</div>
            </div>
            <a href="{{ route('client.profile', auth()->id()) }}" class="btn-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Quay về hồ sơ
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ── Filter Tabs ── --}}
        <div class="tabs">
            <button class="tab active" data-filter="all">Tất cả</button>
            <button class="tab" data-filter="Pending">Chờ xác nhận</button>
            <button class="tab" data-filter="Confirmed">Đã xác nhận</button>
            <button class="tab" data-filter="Shipping">Đang giao</button>
            <button class="tab" data-filter="Completed">Hoàn thành</button>
            <button class="tab" data-filter="Cancelled">Đã hủy</button>
        </div>

        {{-- ── Order List ── --}}
        <div id="order-list">
            @forelse($orders->get()->sortByDesc('orderDate') as $order)
                @php
                    $statusClass = match ($order->status) {
                        'Pending' => 'pending',
                        'CancelRequested' => 'cancel-requested',
                        'Confirmed' => 'confirmed',
                        'Shipping' => 'shipping',
                        'Completed' => 'completed',
                        'Cancelled' => 'cancelled',
                        default => 'pending',
                    };
                    $statusLabel = match ($order->status) {
                        'Pending' => 'Chờ xác nhận',
                        'CancelRequested' => 'Yêu cầu hủy',
                        'Confirmed' => 'Đã xác nhận',
                        'Shipping' => 'Đang giao hàng',
                        'Completed' => 'Hoàn thành',
                        'Cancelled' => 'Đã hủy',
                        default => $order->status,
                    };
                @endphp
                <a href="{{ route('client.profile.orderDetails', ['orderID' => $order->orderID]) }}">
                    <div class="order-card" data-status="{{ $order->status }}">
                        <div class="order-card-header">
                            <div>
                                <div class="order-id">
                                    #ĐH{{ str_pad($order->orderID, 5, '0', STR_PAD_LEFT) }}
                                </div>
                                <div class="order-date">{{ \Carbon\Carbon::parse($order->orderDate)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <span class="badge badge-{{ $statusClass }}">
                                <span class="badge-dot"></span>
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <div class="order-card-body">
                            <div class="order-meta">
                                {{ $order->details->count() }} sản phẩm
                                @if ($order->shippingAddress)
                                    · Giao đến: {{ Str::limit($order->shippingAddress, 40) }}
                                @endif
                            </div>
                            <div style="text-align:right;">
                                <div class="order-amount">{{ number_format($order->finalAmount, 0, ',', '.') }}₫</div>
                                @if ($order->discountAmount > 0)
                                    <div class="order-discount-note">Giảm
                                        {{ number_format($order->discountAmount, 0, ',', '.') }}₫</div>
                                @endif
                            </div>
                        </div>
                </a>
                {{-- <div class="order-card-footer"> --}}
                {{-- Yêu cầu hủy nếu đang Pending --}}
                {{-- @if ($order->status === 'Pending') --}}
                {{-- <form action="{{ route('order.requestCancel', $order->orderID) }}" method="POST" --}}
                {{-- onsubmit="return confirm('Bạn có chắc muốn gửi yêu cầu hủy đơn hàng này?')"> --}}
                {{-- @csrf --}}
                {{-- @method('PATCH')
                        <button type="submit" class="btn btn-danger-ghost">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            Yêu cầu hủy
                        </button>
                    </form>
                @elseif($order->status === 'CancelRequested')
                    <span style="font-size:0.78rem; color:#c0392b;">
                        ⏳ Đang chờ phản hồi từ cửa hàng...
                    </span>
                @else
                    <span></span>
                @endif

                <a href="{{ route('client.profile.orderDetails', ['userID' => $user->userID, 'orderID' => $order->orderID]) }}" class="btn btn-ghost">
                    Xem chi tiết
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                </a>--}}
            </div>
        </div>
    @empty
        <div class="empty-state">
            <svg class="empty-icon" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M48 8H16a4 4 0 0 0-4 4v40a4 4 0 0 0 4 4h32a4 4 0 0 0 4-4V12a4 4 0 0 0-4-4z" />
                <line x1="24" y1="24" x2="40" y2="24" />
                <line x1="24" y1="32" x2="40" y2="32" />
                <line x1="24" y1="40" x2="32" y2="40" />
            </svg>
            <div class="empty-title">Chưa có đơn hàng nào</div>
            <div class="empty-text">Hãy khám phá sản phẩm và bắt đầu mua sắm!</div>
        </div>
        @endforelse
    </div>

    </div>

    @push('scripts')
        <script>
            const tabs = document.querySelectorAll('.tab');
            const cards = document.querySelectorAll('.order-card');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    const filter = tab.dataset.filter;

                    cards.forEach(card => {
                        if (filter === 'all' || card.dataset.status === filter) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection

{{-- resources/views/client/user/orders.blade.php --}}
@extends('layouts.client')

@section('title', 'Đơn hàng của tôi')

@push('styles')
    @vite(['resources/css/client/user/order.css'])
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

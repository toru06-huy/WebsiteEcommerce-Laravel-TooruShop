{{-- resources/views/client/user/order_details.blade.php --}}
@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . str_pad($order->orderID, 5, '0', STR_PAD_LEFT))

@push('styles')
    @vite(['resources/css/client/user/order_detail.css'])
@endpush

@section('content')
@php
    $statusClass = match($order->status) {
        'Pending'          => 'pending',
        'CancelRequested'  => 'cancel-requested',
        'Confirmed'        => 'confirmed',
        'Shipping'         => 'shipping',
        'Completed'        => 'completed',
        'Cancelled'        => 'cancelled',
        default            => 'pending',
    };
    $statusLabel = match($order->status) {
        'Pending'          => 'Chờ xác nhận',
        'CancelRequested'  => 'Yêu cầu hủy',
        'Confirmed'        => 'Đã xác nhận',
        'Shipping'         => 'Đang giao hàng',
        'Completed'        => 'Hoàn thành',
        'Cancelled'        => 'Đã hủy',
        default            => $order->status,
    };
@endphp

<div class="detail-page">

    {{-- ── Top Bar ── --}}
    <div class="topbar">
        <div class="topbar-left">
            <a href="{{ route('client.profile.orders', auth()->id()) }}" class="btn-back">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
                Đơn hàng
            </a>
            <div class="order-title">#ĐH{{ str_pad($order->orderID, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <span class="badge badge-{{ $statusClass }}">
            <span class="badge-dot"></span>
            {{ $statusLabel }}
        </span>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Yêu cầu hủy (Pending) ── --}}
    {{-- @if($order->status === 'Pending')
    <div class="cancel-box">
        <div class="cancel-box-title">⚠ Muốn hủy đơn hàng này?</div>
        <div class="cancel-box-text">
            Yêu cầu hủy sẽ được gửi đến đội ngũ hỗ trợ. Chúng tôi sẽ xem xét và phản hồi trong thời gian sớm nhất.
        </div>
        <form action="{{ route('order.requestCancel', $order->orderID) }}" method="POST"
              onsubmit="return confirm('Xác nhận gửi yêu cầu hủy đơn hàng này?')">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-danger">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                Gửi yêu cầu hủy đơn
            </button>
        </form>
    </div>
    @endif

    @if($order->status === 'CancelRequested')
    <div class="cancel-box">
        <div class="cancel-box-title">⏳ Yêu cầu hủy đang được xử lý</div>
        <div class="cancel-box-text">
            Yêu cầu hủy của bạn đã được ghi nhận. Cửa hàng sẽ phản hồi trong thời gian sớm nhất.
        </div>
    </div>
    @endif --}}

    <div class="detail-grid">

        {{-- ── Left Column ── --}}
        <div>
            {{-- Sản phẩm --}}
            <div class="card">
                <div class="card-header">Sản phẩm đã đặt</div>
                <div class="product-list">
                    @foreach($details as $item)
                    <div class="product-row">
                        <div class="product-thumb">
                            @if($item->variant->product->images->isNotEmpty())
                                <img src="{{ asset('storage/' . $item->variant->product->images->first()->imageURL) }}" alt="">
                            @else
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                            @endif
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ $item->variant->product->productName ?? '—' }}</div>
                            <div class="product-variant">
                                Size: {{ $item->variant->size->sizeCode ?? '—' }} &nbsp;·&nbsp;
                                Màu: {{ $item->variant->color->colorName ?? '—' }}
                            </div>
                        </div>
                        <div class="product-qty">x{{ $item->quantity }}</div>
                        <div class="product-price">{{ number_format($item->unitPrice * $item->quantity, 0, ',', '.') }}₫</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Thông tin giao hàng --}}
            <div class="card">
                <div class="card-header">Thông tin giao hàng</div>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Địa chỉ</span>
                        <span class="info-value">{{ $order->shippingAddress ?: '—' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ngày đặt</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($order->orderDate)->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($order->processedBy)
                    <div class="info-row">
                        <span class="info-label">Xử lý bởi</span>
                        <span class="info-value">{{ $order->employee->user->fullName ?? '—' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Right Column ── --}}
        <div>
            {{-- Tổng tiền --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header">Tóm tắt đơn hàng</div>
                <div class="summary-list">
                    <div class="summary-row">
                        <span>Tạm tính</span>
                        <span>{{ number_format($order->totalAmount, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discountAmount > 0)
                    <div class="summary-row">
                        <span>Giảm giá</span>
                        <span class="discount-val">- {{ number_format($order->discountAmount, 0, ',', '.') }}₫</span>
                    </div>
                    @endif
                    <div class="summary-row total">
                        <span>Thành tiền</span>
                        <span>{{ number_format($order->finalAmount, 0, ',', '.') }}₫</span>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">Trạng thái đơn hàng</div>
                <div class="timeline">
                    @php
                        $steps = [
                            ['key' => 'Pending',   'label' => 'Chờ xác nhận'],
                            ['key' => 'Confirmed', 'label' => 'Đã xác nhận'],
                            ['key' => 'Shipping',  'label' => 'Đang giao hàng'],
                            ['key' => 'Completed', 'label' => 'Hoàn thành'],
                        ];
                        $order_steps = ['Pending','Confirmed','Shipping','Completed'];
                        $current_idx = array_search($order->status, $order_steps);
                    @endphp

                    @if($order->status === 'Cancelled' || $order->status === 'CancelRequested')
                        <div class="tl-item">
                            <div class="tl-dot active" style="border-color:#c0392b; background:#c0392b;"></div>
                            <div class="tl-content">
                                <div class="tl-label" style="color:#c0392b;">{{ $statusLabel }}</div>
                                <div class="tl-date">{{ \Carbon\Carbon::parse($order->orderDate)->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    @else
                        @foreach($steps as $i => $step)
                        <div class="tl-item">
                            <div class="tl-dot {{ $current_idx !== false && $i <= $current_idx ? 'active' : '' }}"></div>
                            <div class="tl-content">
                                <div class="tl-label">{{ $step['label'] }}</div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
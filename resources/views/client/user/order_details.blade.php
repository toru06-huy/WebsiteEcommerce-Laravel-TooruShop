{{-- resources/views/client/user/order_details.blade.php --}}
@extends('layouts.client')

@section('title', 'Chi tiết đơn hàng #' . str_pad($order->orderID, 5, '0', STR_PAD_LEFT))

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --cream: #faf8f5; --sand: #ede9e2; --stone: #c9c3b8;
        --charcoal: #2b2b2b; --ink: #1a1a1a; --sage: #7a9e87; --sage-light: #eef3f0;
        --error: #c0392b; --radius: 10px;
    }

    body { background: var(--cream); color: var(--charcoal); font-family: 'DM Sans', sans-serif; }

    .detail-page { max-width: 820px; margin: 0 auto; padding: 48px 24px 80px; }

    /* ── Top Bar ── */
    .topbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 32px; flex-wrap: wrap; gap: 14px;
    }

    .topbar-left { display: flex; align-items: center; gap: 14px; }

    .btn-back {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 0.82rem; color: var(--stone); text-decoration: none; transition: color 0.2s;
    }
    .btn-back:hover { color: var(--sage); }

    .order-title {
        font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; font-weight: 600; color: var(--ink);
    }

    /* ── Status Badge ── */
    .badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 14px; border-radius: 50px;
        font-size: 0.72rem; font-weight: 500; letter-spacing: 0.5px;
    }
    .badge-dot { width: 6px; height: 6px; border-radius: 50%; }

    .badge-pending         { background: #fef9ec; color: #b45309; }
    .badge-pending .badge-dot { background: #f59e0b; }
    .badge-cancel-requested { background: #fff1f0; color: #c0392b; }
    .badge-cancel-requested .badge-dot { background: #c0392b; }
    .badge-confirmed       { background: #eff6ff; color: #1d4ed8; }
    .badge-confirmed .badge-dot { background: #3b82f6; }
    .badge-shipping        { background: #f0f9ff; color: #0369a1; }
    .badge-shipping .badge-dot { background: #0ea5e9; }
    .badge-completed       { background: var(--sage-light); color: #4a7a5a; }
    .badge-completed .badge-dot { background: var(--sage); }
    .badge-cancelled       { background: #f9f9f9; color: #9ca3af; }
    .badge-cancelled .badge-dot { background: #d1d5db; }

    /* ── Layout ── */
    .detail-grid {
        display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start;
    }

    /* ── Card ── */
    .card {
        background: #fff; border: 1px solid var(--sand); border-radius: var(--radius); overflow: hidden;
        margin-bottom: 20px;
    }

    .card-header {
        padding: 18px 24px 14px; border-bottom: 1px solid var(--sand);
        font-family: 'Cormorant Garamond', serif; font-size: 1.05rem; font-weight: 600; color: var(--ink);
    }

    /* ── Product Table ── */
    .product-list { padding: 8px 0; }

    .product-row {
        display: flex; align-items: center; gap: 16px;
        padding: 16px 24px; border-bottom: 1px solid var(--sand);
    }
    .product-row:last-child { border-bottom: none; }

    .product-thumb {
        width: 56px; height: 56px; border-radius: 8px;
        background: var(--sand); flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; color: var(--stone);
        overflow: hidden;
    }
    .product-thumb img { width: 100%; height: 100%; object-fit: cover; }

    .product-info { flex: 1; min-width: 0; }

    .product-name {
        font-size: 0.9rem; font-weight: 500; color: var(--ink);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .product-variant { font-size: 0.75rem; color: var(--stone); margin-top: 3px; }

    .product-qty { font-size: 0.78rem; color: var(--stone); white-space: nowrap; }

    .product-price {
        font-family: 'Cormorant Garamond', serif; font-size: 1rem; font-weight: 600; color: var(--ink);
        white-space: nowrap;
    }

    /* ── Info List ── */
    .info-list { padding: 6px 0; }

    .info-row {
        display: flex; align-items: flex-start;
        padding: 12px 24px; border-bottom: 1px solid var(--sand);
        gap: 16px;
    }
    .info-row:last-child { border-bottom: none; }

    .info-label { font-size: 0.75rem; color: var(--stone); flex-shrink: 0; width: 120px; padding-top: 1px; }
    .info-value { font-size: 0.875rem; color: var(--ink); flex: 1; }

    /* ── Summary ── */
    .summary-list { padding: 16px 24px; }

    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 7px 0; font-size: 0.875rem; color: var(--charcoal);
    }

    .summary-row.total {
        border-top: 1px solid var(--sand); margin-top: 10px; padding-top: 14px;
        font-family: 'Cormorant Garamond', serif; font-size: 1.25rem; font-weight: 600; color: var(--ink);
    }

    .summary-row .discount-val { color: var(--sage); }

    /* ── Cancel Action ── */
    .cancel-box {
        background: #fff1f0; border: 1px solid #f4c3be; border-radius: var(--radius);
        padding: 20px 24px; margin-bottom: 20px;
    }

    .cancel-box-title { font-weight: 500; color: #c0392b; font-size: 0.9rem; margin-bottom: 6px; }
    .cancel-box-text  { font-size: 0.8rem; color: #9b2335; line-height: 1.5; margin-bottom: 14px; }

    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 10px 20px; border-radius: 7px;
        font-family: 'DM Sans', sans-serif; font-size: 0.85rem; font-weight: 500;
        cursor: pointer; border: none; text-decoration: none; transition: all 0.2s;
    }

    .btn-danger { background: #c0392b; color: #fff; }
    .btn-danger:hover { background: #a93226; }

    /* ── Timeline ── */
    .timeline { padding: 20px 24px; }

    .tl-item {
        display: flex; gap: 14px; padding-bottom: 20px; position: relative;
    }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-item:not(:last-child)::before {
        content: ''; position: absolute; left: 9px; top: 20px;
        width: 2px; height: calc(100% - 12px); background: var(--sand);
    }

    .tl-dot {
        width: 20px; height: 20px; border-radius: 50%; flex-shrink: 0;
        border: 2px solid var(--sand); background: #fff; margin-top: 1px; position: relative; z-index: 1;
        display: flex; align-items: center; justify-content: center;
    }
    .tl-dot.active { border-color: var(--sage); background: var(--sage); }
    .tl-dot.active::after { content: ''; width: 6px; height: 6px; background: #fff; border-radius: 50%; }

    .tl-content { flex: 1; }
    .tl-label { font-size: 0.875rem; color: var(--charcoal); font-weight: 500; }
    .tl-date  { font-size: 0.75rem; color: var(--stone); margin-top: 2px; }

    .alert-success {
        display: flex; align-items: center; gap: 10px;
        padding: 13px 18px; border-radius: 8px; font-size: 0.875rem;
        background: var(--sage-light); color: #4a7a5a; border: 1px solid #b8d4c0;
        margin-bottom: 20px;
    }

    @media (max-width: 720px) {
        .detail-grid { grid-template-columns: 1fr; }
        .product-row { padding: 14px 16px; }
        .info-row, .summary-list { padding: 12px 16px; }
        .card-header { padding: 14px 16px 12px; }
    }
</style>
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
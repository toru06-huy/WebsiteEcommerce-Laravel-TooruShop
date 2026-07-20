@extends('admin.layout')

@section('title', 'Đơn hàng')
@section('page-title', 'Đơn hàng')
@section('breadcrumb', 'VELOUR / Đơn hàng')

@section('content')

<div class="table-card">
    <div class="table-head">
        <h2>Danh sách đơn hàng ({{ $orders->total() }})</h2>
        <div class="table-actions">
            <form method="GET" action="{{ route('admin.orders.index') }}" style="display:flex;gap:8px;">
                <select name="status" class="form-control" style="padding:8px 12px;font-size:13px;">
                    <option value="">Tất cả trạng thái</option>
                    @foreach(['Pending'=>'Chờ xử lý','Confirmed'=>'Đã xác nhận','Shipping'=>'Đang giao','Completed'=>'Hoàn thành','Cancelled'=>'Đã hủy'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $lbl }}</option>
                    @endforeach
                </select>
                <div class="search-box">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" name="employee_search" list="employee-datalist"
                        placeholder="Tìm theo tên hoặc mã nhân viên xử lý..."
                        value="{{ request('employee_search') }}">
                </div>
                <datalist id="employee-datalist">
                    @foreach($employees as $emp)
                        <option value="{{ $emp->user->fullName ?? '' }}">{{ $emp->employeeCode }}</option>
                    @endforeach
                </datalist>
                <button type="submit" class="topbar-btn secondary" style="padding:8px 14px;">Lọc</button>
                @if(request('status') || request('employee_search'))
                <a href="{{ route('admin.orders.index') }}" class="topbar-btn secondary" style="padding:8px 14px;">Xóa lọc</a>
                @endif
            </form>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Xử lý bởi</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
                <th style="text-align:center;width:100px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            @php
                $statusClass = match($order->status) {
                    'Completed' => 'active',
                    'Cancelled' => 'inactive',
                    'Pending'   => 'customer',
                    default     => 'employee',
                };
                $statusLabel = match($order->status) {
                    'Pending'   => 'Chờ xử lý',
                    'Confirmed' => 'Đã xác nhận',
                    'Shipping'  => 'Đang giao',
                    'Completed' => 'Hoàn thành',
                    'Cancelled' => 'Giao dịch hủy',
                    default     => $order->status,
                };
                $nextLabel = match($order->status) {
                    'Pending'   => 'Xác nhận',
                    'Confirmed' => 'Giao hàng',
                    'Shipping'  => 'Hoàn thành',
                    default     => null,
                };
                $canAdvance = $nextLabel !== null;
                $canCancel  = !in_array($order->status, ['Completed','Cancelled']);
            @endphp
            <tr onclick="openOrderDetail({{ $order->orderID }})" style="cursor:pointer;">
                <td>
                    <span class="order-id-link"
                        style="font-family:'Cormorant Garamond',serif;font-size:16px;font-weight:600;color:var(--gold);text-decoration:underline;text-underline-offset:3px;">
                        #{{ $order->orderID }}
                    </span>
                </td>
                <td>
                    <strong>{{ $order->name ?? $order->user?->fullName ?? 'Khách vãng lai' }}</strong>
                    <div style="font-size:12px;color:var(--muted);">{{ $order->phone ?? $order->user?->phone ?? '' }}</div>
                </td>
                <td class="td-muted">
                    @if($order->processor)
                        {{ $order->processor->user->fullName ?? '—' }}
                        <div style="font-size:11px;color:var(--gold);">{{ $order->processor->employeeCode }}</div>
                    @else
                        <span style="color:var(--muted);">—</span>
                    @endif
                </td>
                <td><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></td>
                <td>
                    <span style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:600;">
                        {{ number_format($order->finalAmount ?? $order->totalAmount, 0, ',', '.') }}đ
                    </span>
                </td>
                <td class="td-muted">{{ $order->orderDate?->format('d/m/Y') ?? '—' }}</td>
                <td onclick="event.stopPropagation()">
                    <div style="display:flex;gap:6px;justify-content:center;">

                        {{-- ✓ Xác nhận / Chuyển bước --}}
                        @if($canAdvance)
                        <form method="POST" action="{{ route('admin.orders.advance', $order->orderID) }}" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit"
                                title="{{ $nextLabel }}"
                                style="width:32px;height:32px;border-radius:6px;border:none;background:#edfaf3;color:#27ae60;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                ✓
                            </button>
                        </form>
                        @endif

                        @if($canCancel && $order->status == 'Shipping')
                        <form method="POST" action="{{ route('admin.orders.cancel', $order->orderID) }}" style="display:inline;"
                            id="cancel-form-{{ $order->orderID }}">
                            @csrf @method('PATCH')
                            <button type="button"
                                title="Hủy đơn hàng"
                                onclick="confirmCancel({{ $order->orderID }})"
                                style="width:32px;height:32px;border-radius:6px;border:none;background:#fdf2f2;color:#c0392b;font-size:15px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-weight:700;">
                                ✕
                            </button>
                        </form>
                        @endif

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                        </svg>
                        <h3>Chưa có đơn hàng</h3>
                        <p>Đơn hàng sẽ xuất hiện khi khách mua sắm.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
    <div class="pagination-wrap">
        <span>Hiển thị {{ $orders->firstItem() }}–{{ $orders->lastItem() }} / {{ $orders->total() }}</span>
        <div class="pagination">
            @foreach($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-btn {{ $page == $orders->currentPage() ? 'active' : '' }}">{{ $page }}</a>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
function confirmCancel(orderId) {
    if (confirm('Bạn chắc chắn muốn hủy đơn #' + orderId + '?\nTồn kho sẽ được hoàn lại tự động.')) {
        document.getElementById('cancel-form-' + orderId).submit();
    }
}

async function openOrderDetail(orderId) {
    const modal   = document.getElementById('order-detail-modal');
    const body    = document.getElementById('order-detail-body');
    const title   = document.getElementById('order-detail-title');

    title.textContent = 'Đơn hàng #' + orderId;
    body.innerHTML = '<div style="text-align:center;padding:40px;color:var(--muted);">Đang tải...</div>';
    modal.classList.add('open');

    try {
        const res  = await fetch(`/admin/orders/${orderId}/detail`);
        const data = await res.json();
        const o    = data.order;

        const statusLabel = {
            Pending: 'Chờ xử lý', Confirmed: 'Đã xác nhận',
            Shipping: 'Đang giao', Completed: 'Hoàn thành', Cancelled: 'Đã hủy'
        };
        const statusClass = {
            Completed:'active', Cancelled:'inactive', Pending:'customer',
            Confirmed:'employee', Shipping:'employee'
        };

        body.innerHTML = `
            {{-- Thông tin đơn --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px;font-size:13px;">
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Khách hàng</div>
                    <div style="font-weight:500;">${o.name || 'Khách vãng lai'}</div>
                    <div style="color:var(--muted);">${o.phone || ''}</div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Trạng thái</div>
                    <span class="badge ${statusClass[o.status] || 'employee'}">${statusLabel[o.status] || o.status}</span>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Địa chỉ giao</div>
                    <div>${o.shippingAddress || '—'}</div>
                </div>
                <div>
                    <div style="color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Thanh toán</div>
                    <div style="white-space:pre-line;">${o.payment || '—'}</div>
                </div>
            </div>

            {{-- Danh sách sản phẩm --}}
            <div style="border:1px solid var(--border);border-radius:8px;overflow:hidden;">
                <div style="padding:12px 16px;background:var(--cream);border-bottom:1px solid var(--border);font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);">
                    Sản phẩm trong đơn (${data.details.length})
                </div>
                ${data.details.map(d => `
                <div style="display:grid;grid-template-columns:56px 1fr auto;gap:14px;align-items:center;padding:14px 16px;border-bottom:1px solid var(--sand);">
                    <div style="width:56px;height:68px;border-radius:6px;overflow:hidden;background:var(--cream);">
                        ${d.imageURL
                            ? `<img src="${d.imageURL}" style="width:100%;height:100%;object-fit:cover;">`
                            : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:20px;">👗</div>`}
                    </div>
                    <div>
                        <div style="font-weight:500;font-size:13px;color:var(--ink);">${d.productName}</div>
                        <div style="font-size:12px;color:var(--muted);margin-top:2px;">${[d.sizeName, d.colorName].filter(Boolean).join(' / ')}</div>
                        <div style="font-size:12px;color:var(--muted);margin-top:2px;">x${d.quantity}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-family:'Cormorant Garamond',serif;font-size:15px;font-weight:600;color:var(--gold);">
                            ${Number(d.unitPrice * d.quantity).toLocaleString('vi-VN')}đ
                        </div>
                        <div style="font-size:11px;color:var(--muted);">${Number(d.unitPrice).toLocaleString('vi-VN')}đ/sp</div>
                    </div>
                </div>`).join('')}
                <div style="padding:14px 16px;display:flex;flex-direction:column;gap:6px;align-items:flex-end;">
                    ${o.discountAmount > 0 ? `
                    <div style="font-size:13px;color:var(--muted);">
                        Tạm tính: <span style="font-weight:500;">${Number(o.totalAmount).toLocaleString('vi-VN')}đ</span>
                    </div>
                    <div style="font-size:13px;color:#27ae60;">
                        Giảm giá: <span style="font-weight:500;">-${Number(o.discountAmount).toLocaleString('vi-VN')}đ</span>
                    </div>` : ''}
                    <div style="font-size:15px;font-family:'Cormorant Garamond',serif;font-weight:600;color:var(--ink);">
                        Tổng cộng: ${Number(o.finalAmount || o.totalAmount).toLocaleString('vi-VN')}đ
                    </div>
                </div>
            </div>
        `;
    } catch(e) {
        body.innerHTML = '<div style="text-align:center;padding:40px;color:#c0392b;">Không thể tải dữ liệu.</div>';
    }
}
</script>
@endpush

{{-- Modal chi tiết đơn hàng --}}
<div class="modal-overlay" id="order-detail-modal">
    <div class="modal" style="max-width:680px;width:100%;">
        <div class="modal-head">
            <h3 id="order-detail-title">Chi tiết đơn hàng</h3>
            <button class="modal-close" onclick="closeModal('order-detail-modal')">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
        <div class="modal-body" id="order-detail-body" style="max-height:70vh;overflow-y:auto;">
        </div>
    </div>
</div>
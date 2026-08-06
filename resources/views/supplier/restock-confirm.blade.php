<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xác nhận nhập hàng - VELOUR</title>

    @vite(['resources/css/app.css', 'resources/css/manufacturer/restock.css'])
    @stack('styles')
</head>

<body>
    <div class="card">
        <div class="card-head">VELOUR — Yêu cầu nhập hàng</div>
        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <h2>{{ $restockRequest->product->productName ?? '—' }}</h2>
            <p class="muted">
                Nhà cung cấp: {{ $restockRequest->manufacturer->manufacturerName ?? '—' }}<br>
                Ngày tạo yêu cầu: {{ $restockRequest->created_at->format('d/m/Y H:i') }}
            </p>

            @php
                $badgeClass = $restockRequest->isCompleted()
                    ? 'badge-completed'
                    : ($restockRequest->isCancelled()
                        ? 'badge-cancelled'
                        : ($restockRequest->isSupplierConfirmed()
                            ? 'badge-supplier-confirmed'
                            : 'badge-pending'));
                $badgeText = $restockRequest->isCompleted()
                    ? 'Đã nhập kho'
                    : ($restockRequest->isCancelled()
                        ? 'Đã hủy'
                        : ($restockRequest->isSupplierConfirmed()
                            ? 'Bạn đã xác nhận — chờ kiểm hàng'
                            : 'Đang chờ xác nhận'));
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>

            <table>
                <thead>
                    <tr>
                        <td class="muted" style="text-transform:uppercase;font-size:11px;letter-spacing:.08em;">Biến
                            thể</td>
                        <td class="muted" align="right"
                            style="text-transform:uppercase;font-size:11px;letter-spacing:.08em;">Số lượng</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($restockRequest->items as $item)
                        <tr>
                            <td>{{ $item->variant->size->sizeName ?? '?' }} /
                                {{ $item->variant->color->colorName ?? '?' }}</td>
                            <td align="right" style="font-weight:600;">{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($restockRequest->isCompleted())
                <p class="muted" style="margin-top:18px;">
                    Yêu cầu này đã được nhân viên cửa hàng kiểm hàng và nhập kho chính thức
                    @if ($restockRequest->receivedAt)
                        lúc {{ $restockRequest->receivedAt->format('d/m/Y H:i') }}
                    @endif.
                    Không cần thao tác thêm.
                </p>
            @elseif($restockRequest->isCancelled())
                <p class="muted" style="margin-top:18px;">
                    @if ($restockRequest->isCancelledByStaff())
                        Yêu cầu này đã bị cửa hàng từ chối nhận hàng sau khi kiểm tra thực tế.
                        @if ($restockRequest->cancelReason)
                            <br><strong>Lý do:</strong> {{ $restockRequest->cancelReason }}
                        @endif
                    @else
                        Bạn đã từ chối yêu cầu này
                        @if ($restockRequest->cancelledAt)
                            lúc {{ $restockRequest->cancelledAt->format('d/m/Y H:i') }}
                        @endif.
                    @endif
                </p>
            @elseif($restockRequest->isSupplierConfirmed())
                <p class="muted" style="margin-top:18px;">
                    Bạn đã xác nhận yêu cầu này
                    @if ($restockRequest->confirmedAt)
                        lúc {{ $restockRequest->confirmedAt->format('d/m/Y H:i') }}
                    @endif.
                    Cửa hàng sẽ kiểm hàng khi nhận được và nhập kho chính thức. Không cần thao tác thêm.
                </p>
            @else
                <form method="POST" action="{{ route('supplier.restock.confirm', $restockRequest->token) }}">
                    @csrf
                    <button type="submit" class="btn-confirm">Xác nhận nhập hàng</button>
                </form>

                <div class="decline-block">
                    <button type="button" class="decline-toggle"
                        onclick="document.getElementById('decline-form').style.display='block';this.style.display='none';">
                        Không thể cung cấp lô hàng này?
                    </button>
                    <form id="decline-form" method="POST"
                        action="{{ route('supplier.restock.decline', $restockRequest->token) }}" style="display:none;">
                        @csrf
                        <textarea name="reason" class="decline-reason" placeholder="Lý do từ chối (không bắt buộc)"></textarea>
                        <button type="submit" class="btn-decline">Từ chối yêu cầu</button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</body>

</html>

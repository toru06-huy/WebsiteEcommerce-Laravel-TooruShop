<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Xác nhận nhập hàng - VELOUR</title>
<style>
    * { box-sizing: border-box; }
    body {
        margin: 0; font-family: 'DM Sans', Arial, sans-serif;
        background: #f4f1ec; color: #2b2b2b; padding: 32px 16px;
    }
    .card {
        max-width: 560px; margin: 0 auto; background: #fff;
        border: 1px solid #e5ddd0; border-radius: 6px; overflow: hidden;
    }
    .card-head { background: #b8955a; padding: 20px 28px; color: #fff; font-weight: 700; letter-spacing: .04em; }
    .card-body { padding: 26px 28px; }
    h2 { margin: 0 0 6px; font-size: 19px; }
    .muted { color: #888; font-size: 13px; }
    table { width: 100%; border-collapse: collapse; margin-top: 14px; }
    td { padding: 9px 0; border-bottom: 1px solid #f0ebe3; font-size: 13.5px; }
    .alert {
        padding: 12px 14px; border-radius: 4px; font-size: 13.5px; margin-bottom: 16px;
    }
    .alert-success { background: #f0f9f0; border: 1px solid rgba(46,125,50,.25); color: #2e7d32; }
    .alert-info    { background: #eef4fa; border: 1px solid rgba(30,90,150,.2); color: #1e5a96; }
    .badge {
        display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;
    }
    .badge-pending            { background: #fff5f5; color: #c0392b; border: 1px solid rgba(192,57,43,.25); }
    .badge-supplier-confirmed { background: #fff8ec; color: #b8730a; border: 1px solid rgba(184,115,10,.25); }
    .badge-completed          { background: #f0f9f0; color: #2e7d32; border: 1px solid rgba(46,125,50,.25); }
    .badge-cancelled          { background: #f2f2f2; color: #666; border: 1px solid #ddd; }
    .btn-decline {
        display: inline-block; width: 100%; text-align: center; padding: 11px 0;
        background: #fff; color: #c0392b; border: 1px solid rgba(192,57,43,.4); border-radius: 4px;
        font-size: 13.5px; font-weight: 600; letter-spacing: .03em; cursor: pointer; margin-top: 10px;
    }
    .btn-decline:hover { background: #fff5f5; }
    .decline-reason {
        width: 100%; margin-top: 10px; padding: 10px; border: 1px solid #e5ddd0; border-radius: 4px;
        font-family: inherit; font-size: 13px; resize: vertical; min-height: 60px;
    }
    .decline-block { margin-top: 22px; padding-top: 18px; border-top: 1px dashed #e5ddd0; }
    .decline-toggle { background: none; border: none; color: #999; font-size: 12.5px; text-decoration: underline; cursor: pointer; padding: 0; }
    .btn-confirm {
        display: inline-block; width: 100%; text-align: center; padding: 13px 0;
        background: #b8955a; color: #fff; border: none; border-radius: 4px;
        font-size: 14px; font-weight: 600; letter-spacing: .03em; cursor: pointer; margin-top: 20px;
    }
    .btn-confirm:hover { background: #a3814c; }
</style>
</head>
<body>
<div class="card">
    <div class="card-head">VELOUR — Yêu cầu nhập hàng</div>
    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <h2>{{ $restockRequest->product->productName ?? '—' }}</h2>
        <p class="muted">
            Nhà cung cấp: {{ $restockRequest->manufacturer->manufacturerName ?? '—' }}<br>
            Ngày tạo yêu cầu: {{ $restockRequest->created_at->format('d/m/Y H:i') }}
        </p>

        @php
            $badgeClass = $restockRequest->isCompleted() ? 'badge-completed'
                : ($restockRequest->isCancelled() ? 'badge-cancelled'
                : ($restockRequest->isSupplierConfirmed() ? 'badge-supplier-confirmed' : 'badge-pending'));
            $badgeText = $restockRequest->isCompleted() ? 'Đã nhập kho'
                : ($restockRequest->isCancelled() ? 'Đã hủy'
                : ($restockRequest->isSupplierConfirmed() ? 'Bạn đã xác nhận — chờ kiểm hàng' : 'Đang chờ xác nhận'));
        @endphp
        <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>

        <table>
            <thead>
                <tr>
                    <td class="muted" style="text-transform:uppercase;font-size:11px;letter-spacing:.08em;">Biến thể</td>
                    <td class="muted" align="right" style="text-transform:uppercase;font-size:11px;letter-spacing:.08em;">Số lượng</td>
                </tr>
            </thead>
            <tbody>
            @foreach($restockRequest->items as $item)
                <tr>
                    <td>{{ $item->variant->size->sizeName ?? '?' }} / {{ $item->variant->color->colorName ?? '?' }}</td>
                    <td align="right" style="font-weight:600;">{{ $item->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @if($restockRequest->isCompleted())
            <p class="muted" style="margin-top:18px;">
                Yêu cầu này đã được nhân viên cửa hàng kiểm hàng và nhập kho chính thức
                @if($restockRequest->receivedAt) lúc {{ $restockRequest->receivedAt->format('d/m/Y H:i') }} @endif.
                Không cần thao tác thêm.
            </p>
        @elseif($restockRequest->isCancelled())
            <p class="muted" style="margin-top:18px;">
                @if($restockRequest->isCancelledByStaff())
                    Yêu cầu này đã bị cửa hàng từ chối nhận hàng sau khi kiểm tra thực tế.
                    @if($restockRequest->cancelReason)
                        <br><strong>Lý do:</strong> {{ $restockRequest->cancelReason }}
                    @endif
                @else
                    Bạn đã từ chối yêu cầu này
                    @if($restockRequest->cancelledAt) lúc {{ $restockRequest->cancelledAt->format('d/m/Y H:i') }} @endif.
                @endif
            </p>
        @elseif($restockRequest->isSupplierConfirmed())
            <p class="muted" style="margin-top:18px;">
                Bạn đã xác nhận yêu cầu này
                @if($restockRequest->confirmedAt) lúc {{ $restockRequest->confirmedAt->format('d/m/Y H:i') }} @endif.
                Cửa hàng sẽ kiểm hàng khi nhận được và nhập kho chính thức. Không cần thao tác thêm.
            </p>
        @else
            <form method="POST" action="{{ route('supplier.restock.confirm', $restockRequest->token) }}">
                @csrf
                <button type="submit" class="btn-confirm">Xác nhận nhập hàng</button>
            </form>

            <div class="decline-block">
                <button type="button" class="decline-toggle" onclick="document.getElementById('decline-form').style.display='block';this.style.display='none';">
                    Không thể cung cấp lô hàng này?
                </button>
                <form id="decline-form" method="POST" action="{{ route('supplier.restock.decline', $restockRequest->token) }}" style="display:none;">
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

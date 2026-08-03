<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Từ chối nhận hàng</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ec;font-family:'DM Sans', Arial, sans-serif;color:#2b2b2b;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ec;padding:32px 0;">
<tr>
<td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;border:1px solid #e5ddd0;">

    <tr>
        <td style="background:#c0392b;padding:22px 32px;">
            <span style="color:#fff;font-size:18px;font-weight:700;letter-spacing:.04em;">VELOUR</span>
        </td>
    </tr>

    <tr>
        <td style="padding:28px 32px 8px;">
            <h2 style="margin:0 0 6px;font-size:20px;">Đơn hàng bị từ chối nhận</h2>
            <p style="margin:0;font-size:13.5px;color:#666;line-height:1.6;">
                Kính gửi <strong>{{ $restockRequest->manufacturer->manufacturerName ?? 'Quý nhà cung cấp' }}</strong>,<br>
                Sau khi kiểm tra hàng thực nhận cho yêu cầu nhập hàng dưới đây, cửa hàng VELOUR rất tiếc phải từ chối nhận lô hàng này.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:16px 32px;">
            <div style="padding:14px 16px;background:#faf7f2;border:1px solid #e5ddd0;border-radius:4px;margin-bottom:14px;">
                <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:#8a8a8a;margin-bottom:4px;">Sản phẩm</div>
                <div style="font-size:15px;font-weight:600;">{{ $restockRequest->product->productName ?? '—' }}</div>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;margin-bottom:14px;">
                <thead>
                    <tr>
                        <td style="padding:8px 0;border-bottom:1px solid #e5ddd0;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#8a8a8a;">Biến thể</td>
                        <td align="right" style="padding:8px 0;border-bottom:1px solid #e5ddd0;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#8a8a8a;">Số lượng đã giao</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($restockRequest->items as $item)
                    <tr>
                        <td style="padding:9px 0;border-bottom:1px solid #f0ebe3;font-size:13.5px;">
                            {{ $item->variant->size->sizeName ?? '?' }} / {{ $item->variant->color->colorName ?? '?' }}
                        </td>
                        <td align="right" style="padding:9px 0;border-bottom:1px solid #f0ebe3;font-size:13.5px;font-weight:600;">
                            {{ $item->quantity }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="padding:14px 16px;background:#fff5f5;border:1px solid rgba(192,57,43,.25);border-radius:4px;">
                <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:#c0392b;margin-bottom:6px;">Lý do từ chối</div>
                <div style="font-size:13.5px;line-height:1.6;color:#5a2a24;white-space:pre-line;">{{ $restockRequest->cancelReason }}</div>
            </div>
        </td>
    </tr>

    <tr>
        <td style="padding:12px 32px 28px;">
            <p style="margin:0;font-size:12.5px;color:#666;line-height:1.7;">
                Vui lòng liên hệ lại với cửa hàng để trao đổi thêm về lô hàng này (đổi trả, giao lại, hoặc điều chỉnh đơn).
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:16px 32px;background:#faf7f2;border-top:1px solid #e5ddd0;">
            <p style="margin:0;font-size:11px;color:#999;">© {{ date('Y') }} VELOUR. Email này được gửi tự động, vui lòng không trả lời trực tiếp.</p>
        </td>
    </tr>

</table>
</td>
</tr>
</table>
</body>
</html>

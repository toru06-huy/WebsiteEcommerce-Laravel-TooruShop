<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Yêu cầu nhập hàng</title>
</head>
<body style="margin:0;padding:0;background:#f4f1ec;font-family:'DM Sans', Arial, sans-serif;color:#2b2b2b;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f1ec;padding:32px 0;">
<tr>
<td align="center">
<table width="560" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;border:1px solid #e5ddd0;">

    <tr>
        <td style="background:#b8955a;padding:22px 32px;">
            <span style="color:#fff;font-size:18px;font-weight:700;letter-spacing:.04em;">VELOUR</span>
        </td>
    </tr>

    <tr>
        <td style="padding:28px 32px 8px;">
            <h2 style="margin:0 0 6px;font-size:20px;">Yêu cầu nhập hàng mới</h2>
            <p style="margin:0;font-size:13.5px;color:#666;line-height:1.6;">
                Kính gửi <strong>{{ $restockRequest->manufacturer->manufacturerName ?? 'Quý nhà cung cấp' }}</strong>,<br>
                Cửa hàng VELOUR có nhu cầu nhập thêm hàng cho sản phẩm dưới đây. Vui lòng kiểm tra chi tiết và bấm nút xác nhận để hoàn tất.
            </p>
        </td>
    </tr>

    <tr>
        <td style="padding:16px 32px;">
            <div style="padding:14px 16px;background:#faf7f2;border:1px solid #e5ddd0;border-radius:4px;margin-bottom:14px;">
                <div style="font-size:11px;letter-spacing:.1em;text-transform:uppercase;color:#8a8a8a;margin-bottom:4px;">Sản phẩm</div>
                <div style="font-size:15px;font-weight:600;">{{ $restockRequest->product->productName ?? '—' }}</div>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <thead>
                    <tr>
                        <td style="padding:8px 0;border-bottom:1px solid #e5ddd0;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#8a8a8a;">Biến thể</td>
                        <td align="right" style="padding:8px 0;border-bottom:1px solid #e5ddd0;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#8a8a8a;">Số lượng cần nhập</td>
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
        </td>
    </tr>

    <tr>
        <td align="center" style="padding:12px 32px 32px;">
            <a href="{{ $confirmUrl }}"
               style="display:inline-block;background:#b8955a;color:#ffffff;text-decoration:none;
                      padding:13px 28px;border-radius:4px;font-size:14px;font-weight:600;letter-spacing:.03em;">
                Xem &amp; Xác nhận nhập hàng
            </a>
            <p style="margin:14px 0 0;font-size:11.5px;color:#999;line-height:1.6;">
                Liên kết chỉ dùng để xác nhận một lần. Nếu nút không hoạt động, sao chép và dán liên kết sau vào trình duyệt:<br>
                <span style="color:#b8955a;word-break:break-all;">{{ $confirmUrl }}</span>
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

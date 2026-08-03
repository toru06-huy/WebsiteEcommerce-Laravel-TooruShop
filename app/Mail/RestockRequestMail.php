<?php

namespace App\Mail;

use App\Models\RestockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public RestockRequest $restockRequest;

    public function __construct(RestockRequest $restockRequest)
    {
        // Nạp sẵn quan hệ cần dùng trong view để tránh N+1 khi queue chạy
        $this->restockRequest = $restockRequest->load([
            'items.variant.size',
            'items.variant.color',
            'product',
            'manufacturer',
        ]);
    }

    public function build()
    {
        $confirmUrl = route('supplier.restock.show', $this->restockRequest->token);

        return $this->subject('Yêu cầu nhập hàng - ' . ($this->restockRequest->product->productName ?? 'Sản phẩm'))
            ->view('emails.restock-request')
            ->with([
                'restockRequest' => $this->restockRequest,
                'confirmUrl'     => $confirmUrl,
            ]);
    }
}

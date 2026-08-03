<?php

namespace App\Mail;

use App\Models\RestockRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public RestockRequest $restockRequest;

    public function __construct(RestockRequest $restockRequest)
    {
        $this->restockRequest = $restockRequest->load([
            'items.variant.size',
            'items.variant.color',
            'product',
            'manufacturer',
        ]);
    }

    public function build()
    {
        return $this->subject('Từ chối nhận hàng - ' . ($this->restockRequest->product->productName ?? 'Sản phẩm'))
            ->view('emails.restock-rejected')
            ->with([
                'restockRequest' => $this->restockRequest,
            ]);
    }
}

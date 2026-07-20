<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cartItems;
    public $shippingInfo;

    /**
     * Khởi tạo đối tượng Mail và gán dữ liệu đơn hàng
     */
    public function __construct($order, $cartItems, $shippingInfo)
    {
        $this->order = $order;
        $this->cartItems = $cartItems;
        $this->shippingInfo = $shippingInfo;
    }

    /**
     * Thiết lập Tiêu đề (Subject) cho Email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🛍️ [VELOUR] Xác Nhận Đơn Hàng Thành Công #' . $this->order->orderID,
        );
    }

    /**
     * Chỉ định file Giao diện hiển thị
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-success',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
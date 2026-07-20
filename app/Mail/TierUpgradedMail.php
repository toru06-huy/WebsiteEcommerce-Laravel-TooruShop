<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TierUpgradedMail extends Mailable
{
    use Queueable, SerializesModels;

    // Khai báo các biến public để giao diện Blade/Markdown có thể tự động nhận được
    public $user;
    public $newTier;
    public $code;
    public $discountValue;
    public $endDate;

    /**
     * Khởi tạo đối tượng Mail và nhận dữ liệu truyền vào
     */
    public function __construct($user, $newTier, $code, $discountValue, $endDate)
    {
        $this->user = $user;
        $this->newTier = $newTier;
        $this->code = $code;
        $this->discountValue = $discountValue;
        $this->endDate = $endDate;
    }

    /**
     * Thiết lập Tiêu đề (Subject) cho Email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '👑 [VELOUR] Chúc Mừng Bạn Đã Thăng Hạng Thành Viên ' . strtoupper($this->newTier),
        );
    }

    /**
     * Chỉ định file Giao diện hiển thị
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tier-upgraded',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
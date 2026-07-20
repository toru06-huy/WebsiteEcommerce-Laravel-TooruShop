<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    // Khai báo biến public để giao diện Blade/Markdown có thể tự động nhận được
    public $otpCode;

    /**
     * Khởi tạo đối tượng Mail và truyền mã OTP vào
     */
    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    /**
     * Thiết lập Tiêu đề (Subject) cho Email
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[VELOUR] Mã Xác Thực Đăng Ký Tài Khoản',
        );
    }

    /**
     * Chỉ định file Giao diện hiển thị
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.register-otp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
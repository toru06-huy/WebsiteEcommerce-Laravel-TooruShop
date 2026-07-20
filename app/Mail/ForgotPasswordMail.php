<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword;

    public function __construct($newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function build()
    {
        return $this->subject('[VELOUR] Khôi phục mật khẩu tài khoản của bạn')
                    ->html("
                        <div style='padding: 20px; font-family: sans-serif; line-height: 1.6; color: #111;'>
                            <h2 style='color: #bc9c6a;'>Chào bạn,</h2>
                            <p>Yêu cầu cấp lại mật khẩu cho tài khoản tại <strong>VELOUR</strong> đã được xử lý.</p>
                            <p>Mật khẩu đăng nhập mới ngẫu nhiên của bạn là: <strong style='font-size: 16px; color: #c0392b; background: #f9f6f0; padding: 4px 8px; border: 1px solid #e5e5e5;'>{$this->newPassword}</strong></p>
                            <p>Vui lòng đăng nhập bằng mật khẩu này và thay đổi lại mật khẩu mới ngay tại trang quản lý tài khoản để đảm bảo tính bảo mật.</p>
                            <hr style='border: none; border-top: 1px solid #e5e5e5; margin: 20px 0;'>
                            <p style='font-size: 12px; color: #767676;'>Đây là email tự động, vui lòng không phản hồi lại email này.</p>
                        </div>
                    ");
    }
}
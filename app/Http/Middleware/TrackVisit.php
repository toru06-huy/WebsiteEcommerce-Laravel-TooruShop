<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    // Các prefix không cần track
    private const SKIP_PREFIXES = [
        '/admin',
        '/api',
        '/_ignition',
        '/livewire',
        '/up',
        '/trang-ca-nhan', // Trang cá nhân của user, không track
        '/trang-ca-nhan/*', // Các route con của trang cá nhân
        '/gio-hang', // Giỏ hàng, không track
        '/gio-hang/*', // Các route con của giỏ hàng
        '/gio-hang/so-luong', // Cập nhật số lượng, không track
        '/thanh-toan', // Thanh toán, không track
        '/thanh-toan/*', // Các route con của thanh toán
        '/yeu-thich', // Yêu thích, không track
        '/yeu-thich/*', // Các route con của yêu thích
    ];

    // Các extension file tĩnh không cần track
    private const SKIP_EXTENSIONS = [
        'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg',
        'webp', 'ico', 'woff', 'woff2', 'ttf', 'map',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Chỉ track GET request thành công, bỏ qua AJAX
        if (
            $request->isMethod('GET') &&
            !$request->ajax() &&
            $response->getStatusCode() === 200 &&
            !$this->shouldSkip($request)
        ) {
            try {
                PageView::create([
                    'userID'     => Auth::guard('web')->id(),   // null nếu chưa đăng nhập
                    'session_id' => $request->session()->getId(),
                    'ip'         => $request->ip(),
                    'path'       => '/' . ltrim($request->path(), '/'),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                ]);
            } catch (\Throwable) {
                // Không để lỗi tracking làm hỏng request
            }
        }

        return $response;
    }

    private function shouldSkip(Request $request): bool
    {
        $path = '/' . ltrim($request->path(), '/');

        foreach (self::SKIP_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) return true;
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, self::SKIP_EXTENSIONS, true)) return true;

        return false;
    }
}

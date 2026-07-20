<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('client.home')
                             ->with('error', 'Bạn không thể truy cập trang này');
        }

        $user = Auth::user();

        if (!$user->IsActive) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('client.home')
                             ->with('error', 'Tài khoản của bạn đã bị khóa.');
        }

        if (!in_array($user->role, ['Admin', 'Owner', 'Employee'])) {
            Auth::logout();
            return redirect()->route('client.home')
                             ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
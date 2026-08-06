<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Chưa đăng nhập -> đá về trang login
        if (!Auth::check()) {
            return redirect()->route('client.login');
        }

        $routeUserId = $request->route('userID');
        if ($routeUserId === null) {
            return $next($request);
        }

        if ((int) $routeUserId !== (int) Auth::id()) {
            abort(403, 'Lỗi.');
        }

        return $next($request);
    }
}
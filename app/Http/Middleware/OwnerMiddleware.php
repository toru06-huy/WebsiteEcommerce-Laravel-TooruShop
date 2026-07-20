<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OwnerMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $role = Auth::user()?->role;

        if (!in_array($role, ['Admin', 'Owner'])) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        return $next($request);
    }
}
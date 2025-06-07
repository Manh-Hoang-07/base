<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Nếu đã đăng nhập qua web session, cho phép truy cập API
        if (Auth::check()) {
            return $next($request);
        }

        // Nếu không đăng nhập, trả về lỗi JSON
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Please login first.',
            'error' => 'Authentication required'
        ], 401);
    }
}

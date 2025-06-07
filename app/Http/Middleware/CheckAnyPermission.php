<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckAnyPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        try {
            $user = Auth::user();

            if (!$user) {
                Log::warning('CheckAnyPermission: User not authenticated', [
                    'url' => $request->url(),
                    'permissions' => $permissions
                ]);
                abort(403, 'Bạn chưa đăng nhập.');
            }

            if (!$user->canAny($permissions)) {
                Log::warning('CheckAnyPermission: User lacks required permissions', [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'required_permissions' => $permissions,
                    'url' => $request->url()
                ]);
                abort(403, 'Bạn không có quyền truy cập.');
            }

            return $next($request);
        } catch (\Exception $e) {
            Log::error('CheckAnyPermission middleware error: ' . $e->getMessage(), [
                'url' => $request->url(),
                'permissions' => $permissions,
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Có lỗi xảy ra khi kiểm tra quyền.');
        }
    }
}

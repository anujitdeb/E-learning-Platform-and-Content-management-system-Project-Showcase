<?php

namespace App\Http\Middleware\Authentication;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin-api')->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = auth('admin-api')->user();
        if (!$user->is_active) {
            auth('admin-api')->user()->token()->revoke();
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

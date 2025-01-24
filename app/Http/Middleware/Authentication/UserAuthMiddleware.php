<?php

namespace App\Http\Middleware\Authentication;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('api')->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user = auth('api')->user();
        if (!$user->is_active || (!$user->number_verified_at && !$user->email_verified_at)) {
            auth('api')->user()->token()->revoke();
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

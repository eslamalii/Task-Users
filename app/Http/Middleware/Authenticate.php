<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        // First, ensure the user is authenticated
        if (Auth::guard($guards)->guest()) {
            return $this->unauthenticated($request, $guards);
        }

        // Check if the authenticated user is verified
        if (!Auth::user()->is_verified) {
            return response()->json(['message' => 'Your account is not verified.'], 403);
        }

        return $next($request);
    }

    /**
     * Handle unauthenticated users.
     *
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthenticated($request, array $guards)
    {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return null;
        }
    }
}

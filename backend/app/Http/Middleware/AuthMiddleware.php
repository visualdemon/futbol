<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if ($token !== base64_encode('admin:' . env('ADMIN_PASSWORD'))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}

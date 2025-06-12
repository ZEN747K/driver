<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (! $token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $accessToken = PersonalAccessToken::findToken($token);
        if (! $accessToken || ! $accessToken->tokenable instanceof Admin) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $admin = $accessToken->tokenable;
        $request->setUserResolver(fn () => $admin);

        return $next($request);
    }
}


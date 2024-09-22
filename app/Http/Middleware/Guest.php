<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

readonly class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (null !== $token) {
            $accessToken = PersonalAccessToken::findToken($token);
            if (null !== $accessToken) {
                return response()->json(['error' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enum\Language;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('Accept-Language', config('app.fallback_locale'));
        if (null === Language::tryFrom($locale)) {
            $locale = Language::Russian->value;
        }
        app()->setLocale($locale);

        return $next($request);
    }
}

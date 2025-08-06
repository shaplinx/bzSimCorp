<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\RateLimiter;

class EnsureCanExport
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'export-limit:' . $request->user()->id;

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $retryAfter = RateLimiter::availableIn($key);

            throw new ThrottleRequestsException(
                'Too many export attempts. Try again in ' . ceil($retryAfter / 60) . ' minutes.',
                null,
                ['Retry-After' => $retryAfter]
            );
        }
        RateLimiter::hit($key, 3600); // 1 hour decay
        return $next($request);
    }
}

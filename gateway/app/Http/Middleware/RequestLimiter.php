<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RequestLimiter
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($this->tooManyFailedAttempts()) {
            return response()->json([
                'error_message' => 'Server is busy. Please try again later.',
            ], ResponseAlias::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit($this->throttleKey(), 60);

        return $next($request);
    }

    public function throttleKey(): string
    {
        return request()->ip();
    }

    public function tooManyFailedAttempts(): bool
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 10)) {
            return false;
        }

        return true;
    }
}

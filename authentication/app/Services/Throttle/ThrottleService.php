<?php

namespace App\Services\Throttle;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ThrottleService implements ThrottleServiceInterface
{
    private int $maxAttempts;

    public function __construct(private readonly Request $request)
    {
        $this->maxAttempts = 5;
    }

    public function throttleKey(): string
    {
        return $this->request->ip();
    }

    public function tooManyFailedAttempts(): bool
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxAttempts)) {
            return false;
        }

        return true;
    }

    public function hit(): void
    {
        RateLimiter::hit($this->throttleKey());
    }

    public function clear(): void
    {
        RateLimiter::clear($this->throttleKey());
    }
}

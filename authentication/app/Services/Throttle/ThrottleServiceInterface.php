<?php

namespace App\Services\Throttle;

interface ThrottleServiceInterface
{
    public function throttleKey(): string;

    public function tooManyFailedAttempts(): bool;

    public function hit(): void;

    public function clear(): void;
}

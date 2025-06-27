<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class TooManyFailedAttemptsException extends Exception
{
    public function __construct(string $message = "", int $code = ResponseAlias::HTTP_TOO_MANY_REQUESTS, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

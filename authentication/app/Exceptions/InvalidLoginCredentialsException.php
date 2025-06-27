<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class InvalidLoginCredentialsException extends Exception
{
    public function __construct(string $message = "", int $code = ResponseAlias::HTTP_UNAUTHORIZED, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

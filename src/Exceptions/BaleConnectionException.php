<?php

namespace LaravelIran\BaleMessenger\Exceptions;

use Exception;
use Throwable;

/**
 * استثنای خطای ارتباط با سرور بله
 */
class BaleConnectionException extends Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

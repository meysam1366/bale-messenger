<?php

namespace meysammaghsoudi\BaleMessenger\Exceptions;

use Exception;

/**
 * استثنای خطای API بله
 */
class BaleApiException extends Exception
{
    protected array $data = [];

    public function __construct(string $message = '', int $code = 0, array $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function isInvalidToken(): bool
    {
        return $this->code === 401 || $this->code === 403;
    }

    public function isChatNotFound(): bool
    {
        return $this->code === 400 && str_contains($this->message, 'chat not found');
    }

    public function isBlocked(): bool
    {
        return $this->code === 403 && str_contains($this->message, 'blocked');
    }
}

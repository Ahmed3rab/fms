<?php

namespace App\Gateway\Protocol\Exceptions;

use RuntimeException;

abstract class ProtocolException extends RuntimeException
{
    public function __construct(
        string $message,
        protected readonly string $codeName,
    ) {
        parent::__construct($message);
    }

    public function codeName(): string
    {
        return $this->codeName;
    }
}

<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class InvalidPayloadException extends ProtocolException
{
    public function __construct(string $reason)
    {
        parent::__construct(
            $reason,
            "invalid_payload",
        );
    }
}

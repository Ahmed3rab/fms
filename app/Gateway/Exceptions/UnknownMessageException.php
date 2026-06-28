<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class UnknownMessageException extends ProtocolException
{
    public function __construct(string $type)
    {
        parent::__construct(
            "Unknown websocket message type [{$type}].",
            "unknown_message",
        );
    }
}

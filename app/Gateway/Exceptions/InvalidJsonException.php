<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class InvalidJsonException extends ProtocolException
{
    public function __construct()
    {
        parent::__construct(
            "Malformed JSON.",
            "invalid_json",
        );
    }
}

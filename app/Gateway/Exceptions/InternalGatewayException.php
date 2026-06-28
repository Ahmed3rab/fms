<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class InternalGatewayException extends ProtocolException
{
    public function __construct()
    {
        parent::__construct(
            'An internal gateway error occurred.',
            'internal_error',
        );
    }
}

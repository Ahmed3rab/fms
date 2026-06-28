<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class UnauthorizedException extends ProtocolException
{
    public function __construct()
    {
        parent::__construct(
            'You must authenticate first.',
            'unauthorized',
        );
    }
}

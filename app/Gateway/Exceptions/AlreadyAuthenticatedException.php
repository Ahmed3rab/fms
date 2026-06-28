<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class AlreadyAuthenticatedException extends ProtocolException
{
    public function __construct()
    {
        parent::__construct(
            'Client is already authenticated.',
            'already_authenticated',
        );
    }
}

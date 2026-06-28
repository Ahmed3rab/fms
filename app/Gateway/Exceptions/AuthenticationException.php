<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class AuthenticationException extends ProtocolException
{
    public function __construct(
        string $message = 'Authentication failed.',
    ) {
        parent::__construct(
            $message,
            'authentication_failed',
        );
    }
}

<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Protocol\Exceptions\ProtocolException;

class ForbiddenException extends ProtocolException
{
    public function __construct(
        string $message = 'You are not authorized to perform this action.',
    ) {
        parent::__construct(
            $message,
            'forbidden',
        );
    }
}

<?php

namespace App\Gateway\Exceptions;

use App\Enums\GatewayPermission;
use App\Gateway\Protocol\Exceptions\ProtocolException;

class ForbiddenException extends ProtocolException
{
    public function __construct(GatewayPermission $permission)
    {
        parent::__construct(
            "Missing permission [{$permission->value}].",
            'forbidden',
        );
    }
}

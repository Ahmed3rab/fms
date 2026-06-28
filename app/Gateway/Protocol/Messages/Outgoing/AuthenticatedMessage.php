<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;

final readonly class AuthenticatedMessage extends OutgoingMessage
{
    public static function type(): string
    {
        return 'authenticated';
    }

    protected function data(): array
    {
        return [];
    }
}

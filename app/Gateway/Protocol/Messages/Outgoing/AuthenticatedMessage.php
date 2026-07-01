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
        return [
            "gateway" => [
                "version" =>  config('tracking.gateway.version'),
                'heartbeat' => [
                    'idle_timeout' => (int) config('tracking.gateway.heartbeat_idle_time'),
                ],
            ],
        ];
    }
}

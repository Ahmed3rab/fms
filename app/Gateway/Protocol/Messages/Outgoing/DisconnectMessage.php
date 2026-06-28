<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;

final readonly class DisconnectMessage extends OutgoingMessage
{
    public function __construct() {}

    public static function type(): string
    {
        return 'disconnected';
    }

    /**
     * @return array<string,Carbon>
     */
    protected function data(): array
    {
        return [
            "reason" => "heartbeat_timeout",
            "message" => "Connection closed due to inactivity.",
        ];
    }
}

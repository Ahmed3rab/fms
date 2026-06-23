<?php

namespace App\Services\WebSocket\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\OutgoingMessage;
use Carbon\Carbon;

final readonly class PongMessage extends OutgoingMessage
{
    public function __construct(public Carbon $timestamp)
    {
        parent::__construct(
            WebSocketMessageType::Pong,
        );
    }

    protected function payload(): array
    {
        return [
            'timestamp' => $this->timestamp,
        ];
    }
}

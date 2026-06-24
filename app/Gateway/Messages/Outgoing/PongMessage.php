<?php

namespace App\Gateway\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Gateway\Messages\Contracts\OutgoingMessage;
use Carbon\Carbon;

final readonly class PongMessage extends OutgoingMessage
{
    public function __construct(public Carbon $timestamp)
    {
        parent::__construct(
            WebSocketMessageType::Pong,
        );
    }
    /**
     * @return array<string,Carbon>
     */
    protected function payload(): array
    {
        return [
            'timestamp' => $this->timestamp,
        ];
    }
}

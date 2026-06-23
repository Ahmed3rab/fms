<?php

namespace App\Services\WebSocket\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Services\WebSocket\Messages\Contracts\OutgoingMessage;

final readonly class ErrorMessage extends OutgoingMessage
{
    public function __construct(public string $code, public string $message)
    {
        parent::__construct(
            WebSocketMessageType::Error,
        );
    }

    protected function payload(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}

<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;

final readonly class ErrorMessage extends OutgoingMessage
{
    public function __construct(public string $code, public string $message)
    {
        parent::__construct(
            WebSocketMessageType::Error,
        );
    }
    /**
     * @return array<string,string>
     */
    protected function payload(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
}

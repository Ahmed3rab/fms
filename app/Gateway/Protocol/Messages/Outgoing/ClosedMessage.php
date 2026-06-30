<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Enums\CloseReason;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;

final readonly class ClosedMessage extends OutgoingMessage
{
    public function __construct(protected CloseReason $reason, protected string $message) {}

    public static function type(): string
    {
        return 'connection_closed';
    }

    /**
     * @return array<string,mixed>
     */
    protected function data(): array
    {
        return [
            "reason" => $this->reason,
            "message" => $this->message,
        ];
    }
}

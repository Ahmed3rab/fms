<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use Carbon\Carbon;

final readonly class PongMessage extends OutgoingMessage
{
    public function __construct(public Carbon $timestamp) {}

    public static function type(): string
    {
        return 'pong';
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

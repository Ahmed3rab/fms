<?php

namespace App\Gateway\Messages\Outgoing;

use App\Data\ResolvedDeviceState;
use App\Enums\WebSocketMessageType;
use App\Gateway\Messages\Contracts\OutgoingMessage;
use App\Gateway\Subscriptions\Subscription;

final readonly class TelemetryMessage extends OutgoingMessage
{
    public function __construct(public Subscription $subscription, public ResolvedDeviceState $state)
    {
        parent::__construct(
            WebSocketMessageType::Telemetry,
        );
    }
    /**
     * @return array<string,mixed>
     */
    protected function payload(): array
    {
        return [
            'subscription' => $this->subscription->key(),
            'state' => $this->state,
        ];
    }
}

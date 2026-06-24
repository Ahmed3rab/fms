<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Data\ResolvedDeviceState;
use App\Enums\WebSocketMessageType;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Protocol\Subscriptions\Subscription;

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

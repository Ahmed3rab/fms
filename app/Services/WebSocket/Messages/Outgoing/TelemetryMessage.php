<?php

namespace App\Services\WebSocket\Messages\Outgoing;

use App\Data\ResolvedDeviceState;
use App\Services\WebSocket\Messages\Contracts\OutgoingMessage;
use App\Services\WebSocket\Subscriptions\Subscription;
use App\Enums\WebSocketMessageType;

final readonly class TelemetryMessage extends OutgoingMessage
{
    public function __construct(public Subscription $subscription, public ResolvedDeviceState $state)
    {
        parent::__construct(
            WebSocketMessageType::Telemetry,
        );
    }

    protected function payload(): array
    {
        return [
            'subscription' => $this->subscription->key(),
            'state' => $this->state,
        ];
    }
}

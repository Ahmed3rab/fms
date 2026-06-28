<?php

namespace App\Gateway\Protocol\Messages\Outgoing;

use App\Data\ResolvedDeviceState;
use App\Gateway\Protocol\Messages\Contracts\OutgoingMessage;
use App\Gateway\Subscriptions\Subscription;

final readonly class TelemetryMessage extends OutgoingMessage
{
    public function __construct(public Subscription $subscription, public ResolvedDeviceState $state) {}

    public static function type(): string
    {
        return 'telemetry';
    }

    /**
     * @return array<string,mixed>
     */
    protected function data(): array
    {
        return [
            'subscription' => [
                'topic' => $this->subscription->topic->value,
                'identifier' => $this->subscription->identifier,
            ],
            'state' => $this->state,
        ];
    }
}

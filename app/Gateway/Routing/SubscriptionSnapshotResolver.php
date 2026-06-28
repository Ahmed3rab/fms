<?php

namespace App\Gateway\Routing;

use App\Data\ResolvedDeviceState;
use App\Enums\WebSocketTopic;
use App\Gateway\Subscriptions\Subscription;
use App\Services\Tracking\CurrentStateService;

class SubscriptionSnapshotResolver
{
    public function __construct(
        protected CurrentStateService $currentStateService,
    ) {}

    public function snapshot(Subscription $subscription): ?ResolvedDeviceState
    {
        return match ($subscription->topic) {
            WebSocketTopic::Vehicle => $this->currentStateService->currentState($subscription->identifier),

            default => null,
        };
    }
}

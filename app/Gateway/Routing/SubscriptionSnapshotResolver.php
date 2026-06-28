<?php

namespace App\Gateway\Routing;

use App\Data\ResolvedDeviceState;
use App\Enums\WebSocketTopic;
use App\Gateway\Exceptions\InvalidSubscriptionException;
use App\Gateway\Subscriptions\Subscription;
use App\Services\Tracking\CurrentStateService;
use Illuminate\Database\QueryException;

class SubscriptionSnapshotResolver
{
    public function __construct(
        protected CurrentStateService $currentStateService,
    ) {}

    public function snapshot(Subscription $subscription): ?ResolvedDeviceState
    {
        return match ($subscription->topic) {
            WebSocketTopic::Vehicle => $this->vehicleSnapshot($subscription),

            default => null,
        };
    }

    private function vehicleSnapshot(Subscription $subscription): ?ResolvedDeviceState
    {
        try {
            return $this->currentStateService->currentState(
                $subscription->identifier,
            );
        } catch (QueryException) {
            throw new InvalidSubscriptionException(
                $subscription,
            );
        }
    }
}

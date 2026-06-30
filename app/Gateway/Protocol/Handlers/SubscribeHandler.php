<?php

namespace App\Gateway\Protocol\Handlers;

use App\Gateway\Connections\Connection;
use App\Gateway\Exceptions\ForbiddenException;
use App\Gateway\Exceptions\InvalidSubscriptionException;
use App\Gateway\Gateway;
use App\Gateway\Protocol\Handlers\Contracts\MessageHandler;
use App\Gateway\Protocol\Messages\Contracts\IncomingMessage;
use App\Gateway\Protocol\Messages\Outgoing\SubscribedMessage;
use App\Gateway\Protocol\Messages\Outgoing\TelemetryMessage;
use App\Gateway\Subscriptions\SubscriptionManager;
use App\Models\Vehicle;
use App\Services\Tracking\CurrentStateService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class SubscribeHandler implements MessageHandler
{
    public function __construct(
        protected SubscriptionManager $subscriptions,
        protected CurrentStateService $currentStateService
    ) {}

    public function handle(Gateway $gateway, Connection $connection, IncomingMessage $message): void
    {
        /** @var SubscribeMessage $message */
        foreach ($message->subscriptions as $subscription) {
            $vehicle = Vehicle::query()
                ->visibleTo($connection->client()->user())
                ->with('device.state')
                ->whereUuid($subscription->identifier)
                ->first();

            if ($vehicle === null) {
                throw new InvalidSubscriptionException($subscription);
            }

            try {
                Gate::forUser($connection->client()->user())->authorize('view', $vehicle);
            } catch (AuthorizationException) {
                throw new ForbiddenException();
            }

            $snapshot = $this->currentStateService->currentState($vehicle);

            $this->subscriptions->subscribe(
                $connection->client(),
                $subscription,
            );

            $gateway->send(
                $connection,
                new SubscribedMessage($subscription),
            );


            if ($snapshot !== null) {
                $gateway->send(
                    $connection,
                    new TelemetryMessage(
                        $subscription,
                        $snapshot,
                        $subscription->identifier
                    ),
                );
            }

        }
    }
}

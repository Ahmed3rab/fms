<?php

namespace App\Gateway\Protocol\Subscriptions;

use App\Gateway\Connections\Client;
use App\Gateway\Protocol\Subscriptions\Subscription;
use Illuminate\Support\Collection;

class SubscriptionManager
{
    public function subscribe(Client $client, Subscription $subscription): void
    {
        if ($client->subscriptions->contains(fn(Subscription $item) => $item->equals($subscription))) {
            return;
        }

        $client->subscriptions->push($subscription);
    }

    public function unsubscribe(Client $client, Subscription $subscription): void
    {
        $client->subscriptions = $client->subscriptions
            ->reject(fn(Subscription $item) => $item->equals($subscription))
            ->values();
    }

    /**
     * @param iterable<Subscription> $subscriptions
     */
    public function subscribeMany(Client $client, iterable $subscriptions): void
    {
        foreach ($subscriptions as $subscription) {
            $this->subscribe($client, $subscription);
        }
    }

    /**
     * @param iterable<Subscription> $subscriptions
     */
    public function unsubscribeMany(Client $client, iterable $subscriptions): void
    {
        foreach ($subscriptions as $subscription) {
            $this->unsubscribe($client, $subscription);
        }
    }

    /**
     * @return Collection<int,Subscription>
     */
    public function subscriptions(Client $client): Collection
    {
        return $client->subscriptions;
    }

    public function subscribed(Client $client, Subscription $subscription): bool
    {
        return $client->subscriptions->contains(fn(Subscription $item) => $item->equals($subscription));
    }
}

<?php

namespace App\Gateway\Subscriptions;

use App\Gateway\Connections\Client;
use Illuminate\Support\Collection;

class SubscriptionManager
{
    /**
     * @var Collection<string, Collection<int, Client>>
     */
    protected Collection $clientsBySubscription;

    public function __construct()
    {
        $this->clientsBySubscription = collect();
    }

    public function subscribe(Client $client, Subscription $subscription): void
    {
        if ($client->isSubscribed($subscription)) {
            return;
        }
        $key = $subscription->key();

        $clients = $this->clientsBySubscription->get($key, collect());

        $clients->put(
            $client->connection()->id(),
            $client,
        );

        $this->clientsBySubscription->put(
            $key,
            $clients,
        );

        $client->subscribe($subscription);

        logger()->info('WS SUBSCRIBE', [
            'connection' => $client->connection()->id(),
            'subscription' => $subscription->key(),
        ]);
    }

    public function unsubscribe(Client $client, Subscription $subscription): void
    {
        $client->unsubscribe($subscription);

        $key = $subscription->key();

        $clients = $this->clientsBySubscription->get($key);

        if ($clients === null) {
            return;
        }

        $clients->forget($client->connection()->id());

        if ($clients->isEmpty()) {
            $this->clientsBySubscription->forget($key);

            return;
        }

        $this->clientsBySubscription->put($key, $clients);
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

    public function subscribed(Client $client, Subscription $subscription): bool
    {
        return $client->isSubscribed($subscription);
    }

    /**
     * @return iterable<Client>
     */
    public function subscribers(Subscription $subscription): iterable
    {
        yield from $this->clientsBySubscription->get($subscription->key(), collect());
    }

    public function forget(Client $client): void
    {
        $subscriptions = iterator_to_array($client->subscriptions());

        foreach ($subscriptions as $subscription) {
            $this->unsubscribe($client, $subscription);
        }
    }
}

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
        logger()->info('SubscriptionManager', [
            'object' => spl_object_id($this),
        ]);
        logger()->info('Registering subscription', [
            'key' => $subscription->key(),
        ]);
        if ($client->subscriptions->contains(fn(Subscription $item) => $item->equals($subscription))) {
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
        $client->subscriptions->push($subscription);
    }

    public function unsubscribe(Client $client, Subscription $subscription): void
    {
        $client->subscriptions = $client->subscriptions
            ->reject(fn(Subscription $item) => $item->equals($subscription))
            ->values();

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

    /**
     * @return iterable<Client>
     */
    public function subscribers(Subscription $subscription): iterable
    {
        yield from $this->clientsBySubscription->get($subscription->key(), collect());
    }

    public function forget(Client $client): void
    {
        foreach ($client->subscriptions as $subscription) {
            $this->unsubscribe($client, $subscription);
        }
    }
}

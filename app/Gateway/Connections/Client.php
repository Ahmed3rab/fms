<?php

namespace App\Gateway\Connections;

use App\Enums\GatewayPermission;
use App\Models\Company;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Gateway\Subscriptions\Subscription;
use LogicException;

class Client
{
    protected ?PersonalAccessToken $token = null;
    protected ?Company $company = null;
    protected Carbon $connectedAt;
    protected Carbon $lastHeartbeat;
    protected array $permissions = [];
    protected Collection $subscriptions;
    protected bool $authenticated = false;

    /**
     * @param list<string> $permissions
     * @param Collection<int, Subscription> $subscriptions
     */
    public function __construct(
        protected Connection $connection,
    ) {
        $this->connectedAt = now();
        $this->lastHeartbeat = now();
        $this->subscriptions = collect();
    }

    public function connection(): Connection
    {
        return $this->connection;
    }

    public function token(): ?PersonalAccessToken
    {
        return $this->token;
    }

    public function company(): ?Company
    {
        return $this->company;
    }

    public function permissions(): array
    {
        return $this->permissions;
    }

    public function connectedAt(): Carbon
    {
        return $this->connectedAt;
    }

    public function lastHeartbeat(): Carbon
    {
        return $this->lastHeartbeat;
    }

    public function authenticated(): bool
    {
        return $this->authenticated;
    }

    public function authenticate(PersonalAccessToken $token): void
    {
        $user = $token->tokenable;

        if (! $user instanceof User) {
            throw new LogicException();
        }

        $this->authenticated = true;
        $this->token = $token;
        $this->company = $user->company;
        $this->permissions = $token->abilities;
    }

    public function heartbeat(): void
    {
        $this->lastHeartbeat = now();
    }

    public function can(GatewayPermission $permission): bool
    {
        return in_array($permission->value, $this->permissions, true);
    }

    public function subscribe(Subscription $subscription): void
    {
        if (! $this->isSubscribed($subscription)) {
            $this->subscriptions->push($subscription);
        }
    }

    public function unsubscribe(Subscription $subscription): void
    {
        $this->subscriptions = $this->subscriptions
            ->reject(fn(Subscription $item) => $item->equals($subscription))
            ->values();
    }

    /**
     * @return iterable<Subscription>
     */
    public function subscriptions(): iterable
    {
        yield from $this->subscriptions;
    }

    public function isSubscribed(Subscription $subscription): bool
    {
        return $this->subscriptions->contains(fn(Subscription $item) => $item->equals($subscription));
    }
}

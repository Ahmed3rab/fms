<?php

namespace App\Services\WebSocket;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\PersonalAccessToken;
use React\Socket\ConnectionInterface;

class WebSocketClient
{
    /**
     * @var array<string,true>
     */
    private array $permissions = [];

    public function __construct(
        public readonly ConnectionInterface $connection,
        public readonly Carbon $connectedAt,
        public readonly SubscriptionBag $subscriptions,
        public ?Carbon $lastHeartbeat = null,
        public ?User $user = null,
        public ?Company $company = null,
        public ?PersonalAccessToken $token = null,
    ) {}

    public static function connect(ConnectionInterface $connection): self
    {
        return new self(
            connection: $connection,
            connectedAt: now(),
            subscriptions: new SubscriptionBag(),
        );
    }

    public function heartbeat(): void
    {
        $this->lastHeartbeat = now();
    }

    /**
     * @param list<string> $permissions
     */
    public function authorize(array $permissions): void
    {
        foreach ($permissions as $permission) {
            $this->permissions[$permission] = true;
        }
    }

    public function can(string $permission): bool
    {
        return isset($this->permissions[$permission]);
    }

    public function authenticated(): bool
    {
        return $this->user !== null;
    }
}

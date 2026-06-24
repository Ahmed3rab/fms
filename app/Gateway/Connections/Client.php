<?php

namespace App\Gateway\Connections;

use App\Models\Company;
use App\Models\PersonalAccessToken;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Gateway\Subscriptions\Subscription;

class Client
{
    /**
     * @param list<string> $permissions
     * @param Collection<int, Subscription> $subscriptions
     */
    public function __construct(
        protected Connection $connection,
        public ?PersonalAccessToken $token = null,
        public ?Company $company = null,
        public ?Carbon $connectedAt = null,
        public ?Carbon $lastHeartbeat = null,
        public array $permissions = [],
        public ?Collection $subscriptions = null,
    ) {
        $this->connectedAt ??= now();
        $this->lastHeartbeat ??= now();
        $this->subscriptions ??= collect();
    }

    public function connection(): Connection
    {
        return $this->connection;
    }
}

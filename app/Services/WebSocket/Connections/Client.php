<?php

namespace App\Services\WebSocket\Connections;

use App\Models\PersonalAccessToken;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Client
{
    /**
     * @param Collection<int,string> $subscriptions
     * @param array<int,mixed> $permissions
     */
    public function __construct(
        public ?PersonalAccessToken $token = null,
        public ?Company $company = null,
        public ?Carbon $connectedAt = null,
        public ?Carbon $lastHeartbeat = null,
        /**
         * @var list<string>
         */
        public array $permissions = [],
        /**
         * @var Collection<int,Subscription>
         */
        public ?Collection $subscriptions = null,
    ) {
        $this->connectedAt ??= now();
        $this->lastHeartbeat ??= now();
        $this->subscriptions ??= collect();
    }
}

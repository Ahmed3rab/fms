<?php

namespace App\Gateway\Connections;

use App\Models\User;
use OpenSwoole\Http\Request;

class Connection
{
    /**
     * @param array<string,mixed> $headers
     */
    public function __construct(protected int $id, protected string $ip, protected array $headers = [], protected ?Client $client = null, protected ?User $user = null)
    {
        $this->client ??= new Client($this);
    }

    /**
     * @return Connection
     */
    public static function fromRequest(Request $request): Connection
    {
        return new self(
            id: $request->fd,
            ip: $request->server['remote_addr'] ?? 'unknown',
            headers: $request->header ?? []
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function ip(): string
    {
        return $this->ip;
    }

    /**
     * @return array<string,mixed>
     */
    public function headers(): array
    {
        return $this->headers;
    }

    public function client(): Client
    {
        return $this->client;
    }

    public function user(): ?User
    {
        return $this->user;
    }
    public function authenticate(User $user): void
    {
        $this->user = $user;
    }

    public function authenticated(): bool
    {
        return $this->user !== null;
    }
}

<?php

namespace App\Gateway\Connections;

use App\Models\User;

class Connection
{
    /**
     * @param array<string,mixed> $headers
     */
    public function __construct(protected int $id, protected string $ip, protected array $headers = [], protected ?User $user = null) {}

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

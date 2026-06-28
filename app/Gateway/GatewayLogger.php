<?php

namespace App\Gateway;

use App\Gateway\Connections\Connection;

class GatewayLogger
{
    /**
     * @param array<string,mixed> $context
     */
    public function info(string $message, ?Connection $connection = null, array $context = []): void
    {
        logger()->info(
            $message,
            $this->context($connection, $context),
        );
    }

    /**
     * @param array<string,mixed> $context
     */
    public function warning(string $message, ?Connection $connection = null, array $context = []): void
    {
        logger()->warning(
            $message,
            $this->context($connection, $context),
        );
    }

    /**
     * @param array<string,mixed> $context
     */
    public function error(string $message, ?Connection $connection = null, array $context = []): void
    {
        logger()->error(
            $message,
            $this->context($connection, $context),
        );
    }

    /**
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     */
    protected function context(?Connection $connection, array $context): array
    {
        if ($connection === null) {
            return $context;
        }

        $client = $connection->client();

        return array_merge([
            'connection' => $connection->id(),
            'company_id' => $client->company()?->id,
            'user_id' => $client->user()->id ?? null,
        ], $context);
    }
}

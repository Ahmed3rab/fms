<?php

namespace App\Gateway\Transport;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Transport\Contracts\GatewayTransport;
use App\Gateway\Connections\ConnectionFactory;
use OpenSwoole\WebSocket\Server;

class OpenSwooleTransport implements GatewayTransport
{
    protected ?Server $server = null;

    public function __construct(protected ConnectionFactory $connections) {}

    protected function createServer(): void
    {
        $this->server = new Server(
            config('tracking.gateway.host'),
            config('tracking.gateway.port'),
            SWOOLE_PROCESS,
            SWOOLE_SOCK_TCP,
        );
        $this->configureServer();
    }

    public function start(Gateway $gateway): void
    {
        $this->createServer();
        $this->server->start();
    }

    public function stop(): void {}

    public function send(Connection $connection, string $payload): void {}

    public function disconnect(Connection $connection): void {}

    protected function configureServer(): void
    {
        $this->server->set([
            'worker_num' => 1,
            'daemonize' => false,
            'log_file' => storage_path('logs/openswoole.log'),
            'heartbeat_idle_time' => 120,
            'heartbeat_check_interval' => 30,
        ]);
    }
}

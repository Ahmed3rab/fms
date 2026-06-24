<?php

namespace App\Gateway\Transport;

use App\Gateway\Connections\Connection;
use App\Gateway\Gateway;
use App\Gateway\Transport\Contracts\GatewayTransport;
use App\Gateway\Connections\ConnectionFactory;
use OpenSwoole\Http\Request;
use OpenSwoole\WebSocket\Frame;
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

        $this->registerEventHandlers($gateway);

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

    protected function registerEventHandlers(Gateway $gateway): void
    {
        $this->server->on('Open', function (Server $server, Request $request) use ($gateway): void {
            $this->handleOpen($gateway, $server, $request);
        });

        $this->server->on('Message', function (Server $server, Frame $frame) use ($gateway): void {
            $this->handleMessage($gateway, $server, $frame);
        });

        $this->server->on('Close', function (Server $server, int $fd) use ($gateway): void {
            $this->handleClose($gateway, $server, $fd);
        });
    }

    protected function handleOpen(
        Gateway $gateway,
        Server $server,
        Request $request,
    ): void {}

    protected function handleMessage(
        Gateway $gateway,
        Server $server,
        Frame $frame,
    ): void {}

    protected function handleClose(
        Gateway $gateway,
        Server $server,
        int $fd,
    ): void {}
}

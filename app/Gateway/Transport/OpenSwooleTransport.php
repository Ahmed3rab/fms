<?php

namespace App\Gateway\Transport;

use App\Gateway\Gateway;
use App\Gateway\Connections\Connection;
use App\Gateway\Heartbeat\HeartbeatMonitor;
use App\Gateway\Transport\Contracts\GatewayTransport;
use OpenSwoole\Http\Request;
use OpenSwoole\Timer;
use OpenSwoole\WebSocket\Frame;
use OpenSwoole\WebSocket\Server;

class OpenSwooleTransport implements GatewayTransport
{
    protected Server $server;

    public function __construct(protected HeartbeatMonitor $heartbeat) {}

    protected function createServer(): void
    {
        $this->server = new Server(
            config('tracking.gateway.host'),
            config('tracking.gateway.port'),
            SWOOLE_PROCESS,
        );
    }

    public function start(Gateway $gateway, callable $boot): void
    {
        $this->createServer();

        $this->configureServer();

        $this->registerEventHandlers($gateway, $boot);

        $this->server->start();
    }

    public function stop(): void {}


    protected function configureServer(): void
    {
        $this->server->set([
            'worker_num' => config('tracking.gateway.worker_num'),
            'max_connection' => config('tracking.gateway.max_connections'),
            'heartbeat_idle_time' => config('tracking.gateway.heartbeat_idle_time'),
            'heartbeat_check_interval' => config('tracking.gateway.heartbeat_check_interval'),
        ]);
    }

    /**
     * @param callable(): mixed $boot
     */
    protected function registerEventHandlers(Gateway $gateway, callable $boot): void
    {
        $this->server->on(
            'WorkerStart',
            function (Server $server, int $workerId) use ($boot, $gateway): void {

                if ($workerId !== 0) {
                    return;
                }

                $boot();

                Timer::tick(
                    config('tracking.gateway.heartbeat_check_interval'),
                    fn() => $this->heartbeat->sweep($gateway),
                );
            },
        );

        $this->server->on('Open', function (Server $server, Request $request) use ($gateway): void {
            $this->handleOpen($gateway, $request);
        });

        $this->server->on('Message', function (Server $server, Frame $frame) use ($gateway): void {
            $this->handleMessage($gateway, $frame);
        });

        $this->server->on('Close', function (Server $server, int $fd) use ($gateway): void {
            $this->handleClose($gateway, $fd);
        });
    }

    protected function handleOpen(Gateway $gateway, Request $request): void
    {
        $gateway->connect($request);
    }

    protected function handleMessage(Gateway $gateway, Frame $frame): void
    {
        $gateway->receive(
            $frame->fd,
            $frame->data,
        );
    }

    protected function handleClose(Gateway $gateway, int $fd): void
    {
        $gateway->disconnect($fd);
    }

    public function send(Connection $connection, string $payload): void
    {
        if (! $this->server->isEstablished($connection->id())) {
            logger()->warning('FD NOT ESTABLISHED', [
                'fd' => $connection->id(),
            ]);
            return;
        }

        $result = $this->server->push(
            $connection->id(),
            $payload,
        );
    }

    public function disconnect(Connection $connection): bool
    {
        if (! $this->server->isEstablished($connection->id())) {
            return false;
        }

        return (bool) $this->server->disconnect($connection->id());
    }
}

<?php

namespace App\Services\ICruise\Realtime;

use App\Services\ICruise\Mappers\RealtimeStateMapper;
use App\Services\Tracking\RealtimeIngestionService;
use Illuminate\Support\Facades\Cache;
use OpenSwoole\Coroutine;
use OpenSwoole\Coroutine\Http\Client;

class ICruiseRealtimeClient
{
    public function __construct(
        protected RealtimeStateMapper $mapper,
        protected RealtimeIngestionService $ingestionService,
    ) {}

    public function connect(): void
    {
        Coroutine::create(function () {

            while (true) {

                try {

                    $this->runSession();

                } catch (\Throwable $e) {

                    logger()->error(
                        'ICruise realtime client crashed.',
                        [
                            'exception' => $e->getMessage(),
                        ],
                    );
                }

                logger()->info(
                    'Retrying ICruise connection in 5 seconds.',
                );

                Coroutine::sleep(5);
            }
        });
    }

    protected function runSession(): void
    {
        $server = Cache::get('server-info');

        if (! is_array($server)) {
            logger()->warning(
                'ICruise server information not found.',
            );

            return;
        }

        $host = $server['websocket']['domain'];
        $port = (int) $server['websocket']['port'];

        logger()->info(
            'Connecting to ICruise realtime...',
            [
                'host' => $host,
                'port' => $port,
            ],
        );

        $client = new Client(
            $host,
            $port,
        );

        try {

            if (! $client->upgrade('/')) {

                logger()->error(
                    'ICruise websocket upgrade failed.',
                    [
                        'error' => $client->errMsg,
                    ],
                );

                return;
            }

            logger()->info(
                'Connected to ICruise realtime.',
            );

            if (! $client->push($this->loginPayload())) {

                logger()->error(
                    'Failed to send ICruise login payload.',
                );

                return;
            }

            $this->receiveLoop($client);

        } finally {

            $client->close();

        }
    }

    protected function receiveLoop(Client $client): void
    {
        while (true) {

            $frame = $client->recv();

            if ($frame === false) {

                logger()->warning(
                    'ICruise websocket disconnected.',
                    [
                        'error' => $client->errMsg,
                    ],
                );

                return;
            }

            if (! $frame || ! $frame->data) {
                continue;
            }

            $this->handleMessage(
                $frame->data,
            );
        }
    }

    protected function loginPayload(): string
    {
        return json_encode([
            'ClientID' => Cache::get('icruise.session_id'),
            'SignalName' => '00',
            'LoginType' => '0',
            'UserID' => config('icruise.username'),
            'Password' => Cache::get('icruise.ws_password'),
            'ClientType' => '4',
            'DataTypeReq' => ['80', '82', '85', '8E'],
        ]) . '#';
    }

    protected function handleMessage(string $message): void
    {
        $message = trim($message, '#');

        $payload = json_decode(
            $message,
            true,
        );

        if (! is_array($payload)) {
            return;
        }

        match ($payload['SignalName'] ?? null) {
            '80' => $this->handlePosition($payload),
            default => null,
        };
    }

    /**
     * @param array<int,mixed> $payload
     */
    protected function handlePosition(array $payload): void
    {
        $this->ingestionService->ingestRealTimeState(
            $this->mapper->map($payload),
        );
    }
}

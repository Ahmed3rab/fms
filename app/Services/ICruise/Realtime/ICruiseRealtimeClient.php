<?php

namespace App\Services\ICruise\Realtime;

use App\Data\RealtimeDeviceState;
use App\Services\ICruise\Mappers\RealtimeStateMapper;
use App\Services\Tracking\TrackingManager;
use Illuminate\Support\Facades\Cache;
use OpenSwoole\Coroutine\Http\Client;
use OpenSwoole\Coroutine;

class ICruiseRealtimeClient
{
    public function __construct(
        protected RealtimeStateMapper $mapper,
        protected TrackingManager $trackingManager
    ) {}

    public function connect(): void
    {
        // Fire this off in an asynchronous coroutine so it doesn't block the worker boot!
        Coroutine::create(function () {
            $server = Cache::get('server-info');
            $host = $server['websocket']['domain'];
            $port = (int) $server['websocket']['port'];

            dump("CONNECTING TO ICRUISE ASYNCHRONOUSLY via OpenSwoole Coroutine...");

            // OpenSwoole's HTTP/WebSocket client
            $client = new Client($host, $port);

            // Upgrade the connection to a WebSocket
            $ret = $client->upgrade('/');

            if (!$ret) {
                dump('ICRUISE WEBSOCKET CONNECTION FAILED: ' . $client->errMsg);
                logger()->error('ICruise websocket connection failed', ['error' => $client->errMsg]);
                return;
            }

            dump('CONNECTED TO ICRUISE');

            // Send login payload
            $payload = $this->loginPayload();
            dump($payload);
            $client->push($payload);

            // The non-blocking continuous read loop
            while (true) {
                // This yield/suspends execution automatically until a message arrives,
                // allowing OpenSwoole to handle other connected clients in the meantime!
                $frame = $client->recv();

                if ($frame === false) {
                    dump('ICRUISE CONNECTION CLOSED/ERROR: ' . $client->errMsg);
                    logger()->warning('ICruise websocket disconnected');
                    $client->close();
                    break;
                }

                if ($frame && $frame->data) {
                    dump('MESSAGE RECEIVED');
                    $this->handleMessage($frame->data);
                }
            }
        });
    }

    /**
     * @return string
     */
    protected function loginPayload(): string
    {
        return json_encode([
            'ClientID' => Cache::get('icruise.session_id'),
            'SignalName' => '00',
            'LoginType' => '0',
            'UserID' => config('icruise.username'),
            'Password' => Cache::get('icruise.ws_password'),
            'ClientType' => '4',
            'DataTypeReq' => ["80", "82", "85", "8E"],
        ]) . "#";
    }

    protected function handleMessage(string $message): void
    {
        $message = trim($message, '#');
        $payload = json_decode($message, true);

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
        $this->ingestRealTimeState($this->mapper->map($payload));
    }

    protected function ingestRealTimeState(RealtimeDeviceState $state): void
    {
        $this->trackingManager->ingestRealTimeState($state);
    }
}

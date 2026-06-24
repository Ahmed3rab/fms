<?php

namespace App\Services\ICruise\Realtime;

use App\Data\Coordinates;
use App\Services\Geocoding\Contracts\Geocoder;
use App\Services\ICruise\Mappers\RealtimeStateMapper;
use App\Services\Tracking\DeviceStateStore;
use Illuminate\Support\Facades\Cache;
use Ratchet\Client\Connector;
use React\EventLoop\Loop;

class ICruiseRealtimeClient
{
    public function __construct(protected RealtimeStateMapper $mapper) {}

    public function connect(): void
    {
        $server = Cache::get('server-info');

        $url = sprintf(
            'ws://%s:%s',
            $server['websocket']['domain'],
            $server['websocket']['port'],
        );

        $loop = Loop::get();

        $connector = new Connector($loop);

        $connector($url)
            ->then(
                function ($conn) {

                    $payload = $this->loginPayload();

                    dump('CONNECTED');
                    dump($payload);

                    $conn->send($payload);

                    $conn->on('message', function ($message) {

                        dump('MESSAGE');
                        dump((string) $message);

                        $this->handleMessage(
                            (string) $message
                        );
                    });

                    $conn->on('close', function ($code = null, $reason = null) {

                        dump('CLOSED');
                        dump($code);
                        dump($reason);

                        logger()->warning(
                            'ICruise websocket disconnected'
                        );
                    });
                },
                function ($e) {

                    dump('FAILED');
                    dump($e->getMessage());
                }
            );

        $loop->run();
    }

    /**
     * @return array<string,mixed>
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
            'DataTypeReq' => [
                "80",
                "82",
                "85",
                "8E",
            ],
        ]) . "#";
    }

    protected function handleMessage(string $message): void
    {
        $message = trim($message, '#');

        $payload = json_decode(
            $message,
            true
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
        $geoAddress = app(Geocoder::class)->reverse(new Coordinates(
            $payload['Latitude'],
            $payload['Longitude'],
        ));
        $payload['geo_address'] = $geoAddress;
        $payload['received_at'] = now();

        app(DeviceStateStore::class)->put(
            $this->mapper->map($payload),
        );
    }
}

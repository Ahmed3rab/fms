<?php

namespace App\Services\ICruise;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ICruiseClient
{
    public function authenticate(): array
    {
        $data = $this->request(
            'User',
            'SignIn',
            [
                'UserName' => config('icruise.username'),
                'Password' => config('icruise.password'),
            ],
            ''
        );

        Cache::forever('icruise.token', $data['Token']);
        Cache::forever('icruise.session_id', $data['Data']['SessionID']);
        Cache::forever('icruise.ws_password', $data['Data']['Password']);

        return $data;
    }

    public function token(): string
    {
        return Cache::rememberForever('icruise.token', fn() => $this->authenticate()['Token']);
    }

    public function trackers(): array
    {
        $data = $this->request(
            'Product',
            'GetMyTracker',
            [
                "TrackerType" => 1,
            ],
            $this->token(),
        );

        return $data['Data'];
    }

    public function trackerProfile(string $productId): array
    {
        $data = $this->request(
            'Product',
            'GetTrackerProfile',
            [
                'ProductID' => $productId,
            ],
            $this->token(),
        );

        return $data['Data'];
    }

    public function companies(): array
    {
        $data = $this->request(
            informationType: 'Company',
            operationType: 'Query',
            arguments: [],
            token: $this->token(),
        );

        return $data['Data'];
    }

    /**
     * @return void
     */
    public function history(string $productId, Carbon $from, Carbon $to): array
    {
        $data = $this->request(
            informationType: 'HistoricalLocation',
            operationType: 'Query',
            arguments: [
                'ProductID' => $productId,
                'DbID'      => app(ServerInfo::class)->dbId(),
                'Speed'     => '-1',
                'StartTime' => $from->format('Y-m-d H:i:s'),
                'EndTime'   => $to->format('Y-m-d H:i:s'),
            ],
            token: $this->token(),
        );
        return $data;
    }

    public function vehicles(int $page = 1, int $pageSize = 100): array
    {
        $data = $this->request(
            informationType: 'Vehicle',
            operationType: 'Query',
            arguments: [
                'PlateNo' => '',
                'IMEI' => '',
                'SystemNo' => '',
                'PhoneNumber' => '',
                'VIN' => '',
                'UserName' => '',
                'ModelID' => '',
                'CompanyID' => '',
                'GroupID' => '',
                'Status' => '0',
                'FromActiveTime' => '',
                'ToActiveTime' => '',
            ],
            token: $this->token(),
            pageArguments: [
                'PageSize' => (string) $pageSize,
                'PageIndex' => (string) $page,
            ],
        );

        return $data;
    }
    /**
     * @return \Generator<int, array<string,mixed>>
     */
    public function allVehicles(): \Generator
    {
        $page = 1;

        do {
            $response = $this->vehicles($page);

            foreach ($response['Data'] as $vehicle) {
                yield $vehicle;
            }

            $page++;

        } while ($page <= (int) $response['TotalPage']);
    }
    /**
     *
     * @param array<int,mixed> $arguments
     * @param array<int,mixed> $pageArguments
     */
    protected function request(string $informationType, string $operationType, array $arguments = [], ?string $token = null, ?array $pageArguments = []): array
    {
        $payload = [
            'Token' => $token ?? Cache::get('icruise.token'),
            'InformationType' => $informationType,
            'OperationType' => $operationType,
            'LanguageType' => config('icruise.language_type'),
            'Arguments' => $arguments ? json_encode($arguments) : "{}",
        ];

        if (!empty($pageArguments)) {
            $payload['PageArguments'] = json_encode($pageArguments);
        }

        $response = Http::asForm()
            ->withHeaders([
                'Origin' => config('icruise.origin'),
            ])
            ->send('GET', config('icruise.url'), [
                'body' => http_build_query($payload),
            ]);

        if (($response->json()['State'] ?? null) !== '0') {
            throw new \Exception(
                $response->json()['State'] ?? 'ICruise authentication failed'
            );
        }
        return $response->json();
    }
}

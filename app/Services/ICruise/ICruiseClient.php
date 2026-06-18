<?php

namespace App\Services\ICruise;

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

        return $data;
    }

    /**
     *
     * @param array<int,mixed> $arguments
     */
    protected function request(string $informationType, string $operationType, array $arguments = [], ?string $token = null): array
    {
        $response = Http::asForm()
            ->withHeaders([
                'Origin' => config('icruise.origin'),
            ])
            ->send('GET', config('icruise.url'), [
                'body' => http_build_query([
                    'Token' => $token ?? Cache::get('icruise.token'),
                    'InformationType' => $informationType,
                    'OperationType' => $operationType,
                    'LanguageType' => config('icruise.language_type'),
                    'Arguments' => json_encode($arguments),
                ]),
            ]);

        if (($response->json()['State'] ?? null) !== '0') {
            throw new \Exception(
                $response->json()['State'] ?? 'ICruise authentication failed'
            );
        }
        return $response->json();
    }
}

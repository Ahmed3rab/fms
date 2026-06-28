<?php

namespace App\Gateway\Authentication;

use App\Gateway\Connections\Client;
use App\Models\PersonalAccessToken;

class GatewayAuthenticator
{
    public function authenticate(Client $client, string $accessToken): bool
    {
        $token = PersonalAccessToken::findToken($accessToken);

        if ($token === null) {
            return false;
        }

        $user = $token->tokenable;

        $client->token = $token;
        $client->company = $user->company;
        $client->permissions = $token->abilities;

        return true;
    }
}

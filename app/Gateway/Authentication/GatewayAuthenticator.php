<?php

namespace App\Gateway\Authentication;

use App\Gateway\Connections\Client;
use App\Gateway\Exceptions\AlreadyAuthenticatedException;
use App\Gateway\Exceptions\AuthenticationException;
use App\Models\PersonalAccessToken;
use App\Models\User;

class GatewayAuthenticator
{
    public function authenticate(Client $client, string $accessToken): AuthenticationResult
    {
        if ($client->authenticated()) {
            throw new AlreadyAuthenticatedException();
        }

        $token = PersonalAccessToken::findToken($accessToken);

        if ($token === null) {
            throw new AuthenticationException(
                'Invalid access token.'
            );
        }

        if (! $token->tokenable instanceof User) {
            throw new AuthenticationException(
                'Invalid token owner.'
            );
        }

        $client->authenticate($token);

        return new AuthenticationResult($token);
    }
}

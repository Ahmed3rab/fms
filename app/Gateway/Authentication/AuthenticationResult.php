<?php

namespace App\Gateway\Authentication;

use App\Models\PersonalAccessToken;

final readonly class AuthenticationResult
{
    public function __construct(
        public PersonalAccessToken $token,
    ) {}
}

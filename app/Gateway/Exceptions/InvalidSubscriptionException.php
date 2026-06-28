<?php

namespace App\Gateway\Exceptions;

use App\Gateway\Subscriptions\Subscription;
use App\Gateway\Protocol\Exceptions\ProtocolException;

class InvalidSubscriptionException extends ProtocolException
{
    public function __construct(Subscription $subscription)
    {
        parent::__construct(
            "Invalid subscription identifier.",
            "invalid_subscription",
        );
    }
}

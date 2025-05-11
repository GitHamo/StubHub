<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Domain\SubscriptionConstraints;

enum SubscriptionType: string
{
    case FREE = 'free';
    case UNLIMITED = 'unlimited';

    public function constraints(): SubscriptionConstraints
    {
        return SubscriptionConstraints::fromSubscription($this);
    }
}

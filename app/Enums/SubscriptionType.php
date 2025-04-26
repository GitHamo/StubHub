<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Data\SystemConstraints;

enum SubscriptionType: string
{
    case FREE = 'free';
    case UNLIMITED = 'unlimited';

    public function constraints(): SystemConstraints
    {
        return SystemConstraints::fromSubscription($this);
    }
}

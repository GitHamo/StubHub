<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionType: string
{
    case FREE = 'free';
    case UNLIMITED = 'unlimited';
}

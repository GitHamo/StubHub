<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Enums\SubscriptionType;

readonly class SystemConstraints
{
    private const string CONSTRAINTS_MAX_ENDPOINTS = 'maxEndpoints';

    public static function fromSubscription(SubscriptionType $subscriptionType): self
    {
        $configs = match ($subscriptionType) {
            SubscriptionType::UNLIMITED => [
                self::CONSTRAINTS_MAX_ENDPOINTS => 100,
            ],
            // SubscriptionType::FREE
            default => [
                self::CONSTRAINTS_MAX_ENDPOINTS => 5,
            ],
        };

        return new self(
            $configs[self::CONSTRAINTS_MAX_ENDPOINTS],
        );
    }

    private function __construct(
        private int $maxEndpointsTotal,
    ) {
    }

    public function maxEndpointsTotal(): int
    {
        return $this->maxEndpointsTotal;
    }
}

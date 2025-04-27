<?php

declare(strict_types=1);

namespace App\Models\Data;

use App\Enums\SubscriptionType;

readonly class SystemConstraints
{
    private const string MAX_ENDPOINTS = 'maxEndpoints';
    private const string MAX_STUB_SIZE = 'maxStubSize';

    public static function fromSubscription(SubscriptionType $subscriptionType): self
    {
        $configs = match ($subscriptionType) {
            SubscriptionType::UNLIMITED => [
                self::MAX_ENDPOINTS => 100,
                self::MAX_STUB_SIZE => 40960,
            ],
            // SubscriptionType::FREE
            default => [
                self::MAX_ENDPOINTS => 5,
                self::MAX_STUB_SIZE => 2048,
            ],
        };

        return new self(
            $configs[self::MAX_ENDPOINTS],
            $configs[self::MAX_STUB_SIZE],
        );
    }

    private function __construct(
        private int $maxEndpointsTotal,
        private int $maxStubSize,
    ) {
    }

    public function maxEndpointsTotal(): int
    {
        return $this->maxEndpointsTotal;
    }

    public function maxStubSize(): int
    {
        return $this->maxStubSize;
    }
}

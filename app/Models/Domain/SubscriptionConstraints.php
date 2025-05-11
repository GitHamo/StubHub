<?php

declare(strict_types=1);

namespace App\Models\Domain;

use App\Enums\SubscriptionType;

readonly class SubscriptionConstraints
{
    private const string MAX_ENDPOINTS = 'maxEndpoints';
    private const string MAX_STUB_SIZE = 'maxStubSize';
    private const string MAX_OBJ_REPEAT = 'maxObjectRepeat';
    private const string MAX_OBJ_DEPTH = 'maxObjectDepth';

    public static function fromSubscription(SubscriptionType $subscriptionType): self
    {
        $configs = match ($subscriptionType) {
            SubscriptionType::UNLIMITED => [
                self::MAX_ENDPOINTS => 100,
                self::MAX_STUB_SIZE => 40960,
                self::MAX_OBJ_REPEAT => 50,
                self::MAX_OBJ_DEPTH => 10,
            ],
            // SubscriptionType::FREE
            default => [
                self::MAX_ENDPOINTS => 5,
                self::MAX_STUB_SIZE => 2048,
                self::MAX_OBJ_REPEAT => 10,
                self::MAX_OBJ_DEPTH => 3,
            ],
        };

        return new self(
            $configs[self::MAX_ENDPOINTS],
            $configs[self::MAX_STUB_SIZE],
            $configs[self::MAX_OBJ_REPEAT],
            $configs[self::MAX_OBJ_DEPTH],
        );
    }

    private function __construct(
        private int $maxEndpointsTotal,
        private int $maxStubSize,
        private int $maxObjectRepeat,
        private int $maxObjectDepth,
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

    public function maxObjectRepeat(): int
    {
        return $this->maxObjectRepeat;
    }

    public function maxObjectDepth(): int
    {
        return $this->maxObjectDepth;
    }
}

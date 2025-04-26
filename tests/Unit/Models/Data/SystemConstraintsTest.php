<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Enums\SubscriptionType;
use App\Models\Data\SystemConstraints;
use PHPUnit\Framework\TestCase;

class SystemConstraintsTest extends TestCase
{
    public function testCreatesInstanceFromFreeSubscription(): void
    {
        $constraints = SystemConstraints::fromSubscription(SubscriptionType::FREE);

        static::assertInstanceOf(SystemConstraints::class, $constraints);
        static::assertSame(5, $constraints->maxEndpointsTotal());
    }

    public function testCreatesInstanceFromUnlimitedSubscription(): void
    {
        $constraints = SystemConstraints::fromSubscription(SubscriptionType::UNLIMITED);

        static::assertInstanceOf(SystemConstraints::class, $constraints);
        static::assertSame(100, $constraints->maxEndpointsTotal());
    }
}
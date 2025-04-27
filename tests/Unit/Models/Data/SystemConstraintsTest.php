<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Enums\SubscriptionType;
use App\Models\Data\SystemConstraints;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class SystemConstraintsTest extends TestCase
{
    #[DataProvider('subscriptionTypeDataProvider')]
    public function testCreatesInstanceFromSubscriptionType(
        SubscriptionType $subscriptionType,
        int $maxEndpointsTotal,
        int $maxStubSize,
        int $maxObjectRepeat,
    ): void {
        $constraints = SystemConstraints::fromSubscription($subscriptionType);

        static::assertInstanceOf(SystemConstraints::class, $constraints);
        static::assertSame($maxEndpointsTotal, $constraints->maxEndpointsTotal());
        static::assertSame($maxStubSize, $constraints->maxStubSize());
        static::assertSame($maxObjectRepeat, $constraints->maxObjectRepeat());
    }

    /**
     * @return array<int|SubscriptionType>[]
     */
    public static function subscriptionTypeDataProvider(): array
    {
        return [
            SubscriptionType::FREE->value => [
                SubscriptionType::FREE,
                5,
                2048,
                10,
            ],
            SubscriptionType::UNLIMITED->value => [
                SubscriptionType::UNLIMITED,
                100,
                40960,
                50,
            ],
        ];
    }
}

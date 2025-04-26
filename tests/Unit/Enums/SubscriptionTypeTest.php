<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\SubscriptionType;
use App\Models\Data\SystemConstraints;
use PHPUnit\Framework\TestCase;

class SubscriptionTypeTest extends TestCase
{
    public function testReturnsSubscriptionTypeConstraints(): void
    {
        $actual = SubscriptionType::UNLIMITED->constraints();

        static::assertInstanceOf(SystemConstraints::class, $actual);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\ContextEnum;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ContextEnumTest extends TestCase
{
    public function testResolvesEnumFromValidLowercaseName(): void
    {
        static::assertSame(ContextEnum::PRICE, ContextEnum::fromName('price'));
        static::assertSame(ContextEnum::PHONE, ContextEnum::fromName('phone'));
    }

    public function testResolvesEnumFromValidUppercaseOrMixedCase(): void
    {
        static::assertSame(ContextEnum::PRICE, ContextEnum::fromName('PrIcE'));
        static::assertSame(ContextEnum::PHONE, ContextEnum::fromName('phOnE'));
    }

    public function testTrimsWhitespaceBeforeParsing(): void
    {
        static::assertSame(ContextEnum::PRICE, ContextEnum::fromName('  price '));
        static::assertSame(ContextEnum::PHONE, ContextEnum::fromName("\tphone\n"));
    }

    public function testThrowsExceptionForInvalidName(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Invalid context: invalid');

        ContextEnum::fromName('invalid');
    }
}

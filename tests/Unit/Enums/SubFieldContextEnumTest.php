<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\SubFieldContextEnum;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SubFieldContextEnumTest extends TestCase
{
    public function testResolvesEnumFromValidLowercaseName(): void
    {
        static::assertSame(SubFieldContextEnum::FLOAT, SubFieldContextEnum::fromName('float'));
        static::assertSame(SubFieldContextEnum::PHONE, SubFieldContextEnum::fromName('phone'));
    }

    public function testResolvesEnumFromValidUppercaseOrMixedCase(): void
    {
        static::assertSame(SubFieldContextEnum::FLOAT, SubFieldContextEnum::fromName('FlOaT'));
        static::assertSame(SubFieldContextEnum::PHONE, SubFieldContextEnum::fromName('phOnE'));
    }

    public function testTrimsWhitespaceBeforeParsing(): void
    {
        static::assertSame(SubFieldContextEnum::FLOAT, SubFieldContextEnum::fromName('  float '));
        static::assertSame(SubFieldContextEnum::PHONE, SubFieldContextEnum::fromName("\tphone\n"));
    }

    public function testThrowsExceptionForInvalidName(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Invalid context: invalid');

        SubFieldContextEnum::fromName('invalid');
    }
}

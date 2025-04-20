<?php

declare(strict_types=1);

namespace Tests\Unit\Enums;

use App\Enums\StubFieldContext;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SubFieldContextTest extends TestCase
{
    public function testResolvesEnumFromValidLowercaseName(): void
    {
        static::assertSame(StubFieldContext::FLOAT, StubFieldContext::fromName('float'));
        static::assertSame(StubFieldContext::PHONE, StubFieldContext::fromName('phone'));
    }

    public function testResolvesEnumFromValidUppercaseOrMixedCase(): void
    {
        static::assertSame(StubFieldContext::FLOAT, StubFieldContext::fromName('FlOaT'));
        static::assertSame(StubFieldContext::PHONE, StubFieldContext::fromName('phOnE'));
    }

    public function testTrimsWhitespaceBeforeParsing(): void
    {
        static::assertSame(StubFieldContext::FLOAT, StubFieldContext::fromName('  float '));
        static::assertSame(StubFieldContext::PHONE, StubFieldContext::fromName("\tphone\n"));
    }

    public function testThrowsExceptionForInvalidName(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Invalid context: invalid');

        StubFieldContext::fromName('invalid');
    }
}

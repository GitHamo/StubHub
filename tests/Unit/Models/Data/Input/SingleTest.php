<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data\Input;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Single;
use PHPUnit\Framework\TestCase;

class SingleTest extends TestCase
{
    public function testIsSerializable(): void
    {
        $context = StubFieldContext::cases()[mt_rand(0, count(StubFieldContext::cases()) - 1)];

        $expected = ['key' => $key = 'foo', 'context' => $context->value];

        $actual = new Single($key, $context);

        static::assertSame($expected, $actual->jsonSerialize());
    }
}

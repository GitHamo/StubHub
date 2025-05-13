<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data\Input;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use PHPUnit\Framework\TestCase;

class NestedTest extends TestCase
{
    public function testIsSerializableIntoArray(): void
    {
        $context = StubFieldContext::cases()[mt_rand(0, count(StubFieldContext::cases()) - 1)];

        $expected = [
            'key' => $key = 'foo',
            'inputs' => [
                [
                    'key' => $subKey = 'bar',
                    'context' => $context->value,
                ],
            ],
            'repeat' => $repeat = mt_rand(),
        ];

        $actual = new Nested(
            $key,
            [
                new Single($subKey, $context),
            ],
            $repeat,
        );

        static::assertSame($expected, $actual->toArray());
    }
}

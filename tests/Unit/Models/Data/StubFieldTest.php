<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\StubField;
use App\Models\Data\Stub;
use PHPUnit\Framework\TestCase;

class StubFieldTest extends TestCase
{
    public function testConvertSimpleValueToArray(): void
    {
        $field = new StubField('name', 'Alice');

        $expected = [
            'name' => 'Alice',
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNumericValueToArray(): void
    {
        $field = new StubField('score', 100);

        $expected = [
            'score' => 100,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertBooleanValueToArray(): void
    {
        $field = new StubField('active', true);

        $expected = [
            'active' => true,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNullValueToArray(): void
    {
        $field = new StubField('missing', null);

        $expected = [
            'missing' => null,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertArrayValueToArray(): void
    {
        $field = new StubField('tags', ['php', 'testing']);

        $expected = [
            'tags' => ['php', 'testing'],
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNestedOutputToArray(): void
    {
        $nested = new Stub([
            new StubField('city', 'Berlin'),
            new StubField('country', 'Germany'),
        ]);

        $field = new StubField('location', $nested);

        $expected = [
            'location' => [
                'city' => 'Berlin',
                'country' => 'Germany',
            ],
        ];

        static::assertSame($expected, $field->toArray());
    }
}

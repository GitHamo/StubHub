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
            'key' => 'name',
            'value' => 'Alice',
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNumericValueToArray(): void
    {
        $field = new StubField('score', 100);

        $expected = [
            'key' => 'score',
            'value' => 100,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertBooleanValueToArray(): void
    {
        $field = new StubField('active', true);

        $expected = [
            'key' => 'active',
            'value' => true,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNullValueToArray(): void
    {
        $field = new StubField('missing', null);

        $expected = [
            'key' => 'missing',
            'value' => null,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertArrayValueToArray(): void
    {
        $field = new StubField('tags', ['php', 'testing']);

        $expected = [
            'key' => 'tags',
            'value' => ['php', 'testing'],
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
            'key' => 'location',
            'value' => [
                ['key' => 'city', 'value' => 'Berlin'],
                ['key' => 'country', 'value' => 'Germany'],
            ],
        ];

        static::assertSame($expected, $field->toArray());
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\Field;
use App\Models\Data\Stub;
use PHPUnit\Framework\TestCase;

class FieldTest extends TestCase
{
    public function testConvertSimpleValueToArray(): void
    {
        $field = new Field('name', 'Alice');

        $expected = [
            'key' => 'name',
            'value' => 'Alice',
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNumericValueToArray(): void
    {
        $field = new Field('score', 100);

        $expected = [
            'key' => 'score',
            'value' => 100,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertBooleanValueToArray(): void
    {
        $field = new Field('active', true);

        $expected = [
            'key' => 'active',
            'value' => true,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNullValueToArray(): void
    {
        $field = new Field('missing', null);

        $expected = [
            'key' => 'missing',
            'value' => null,
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertArrayValueToArray(): void
    {
        $field = new Field('tags', ['php', 'testing']);

        $expected = [
            'key' => 'tags',
            'value' => ['php', 'testing'],
        ];

        static::assertSame($expected, $field->toArray());
    }

    public function testConvertNestedOutputToArray(): void
    {
        $nested = new Stub([
            new Field('city', 'Berlin'),
            new Field('country', 'Germany'),
        ]);

        $field = new Field('location', $nested);

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

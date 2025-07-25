<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\StubField;
use JsonException;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class StubFieldTest extends TestCase
{
    public function testIsSerializableIntoArray(): void
    {
        $expected = [
            $key = 'foo' => $value = 'bar',
        ];

        $actual = new StubField($key, $value);

        static::assertSame($expected, $actual->toArray());
    }

    public function testThrowsExceptionOnUnserializableObject(): void
    {
        $invalid = new \stdClass();
        $invalid->file = tmpfile(); // resource is not serializable

        $field = new StubField('bad', $invalid);

        static::expectException(JsonException::class);

        $field->toArray();
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('valueDataProvider')]
    public function testSerializesValue(mixed $value, mixed $expected): void
    {
        $actual = new StubField(
            'foo',
            $value
        );

        static::assertEquals($expected, $actual->toArray()['foo']);
    }

    public static function valueDataProvider(): array
    {
        return [
            // primitives
            'string' => ['bar', 'bar'],
            'int' => [1, 1],
            'float' => [1.2, 1.2],
            'bool' => [true, true],
            'null' => [null, null],
            'array_of_mixed' => [[null, 1, 'foo'], [null, 1, 'foo']],
            'array_associative' => [[
                'foo' => 'bar',
                'baz' => 'qux',
            ], [
                'foo' => 'bar',
                'baz' => 'qux',
            ]],
            'json_serializable' => [
                new class() implements JsonSerializable {
                    public function jsonSerialize(): mixed
                    {
                        return ['x' => '🚀', 'n' => '123'];
                    }
                },
                ['x' => '🚀', 'n' => '123'],
            ],
            'nested_self' => [
                new StubField('inner', 'value'),
                ['inner' => 'value'],
            ],
            'nested_array_self' => [
                [
                    new StubField('inner-one', 'value-one'),
                    new StubField('inner-two', 'value-two'),
                ],
                [
                    ['inner-one' => 'value-one'],
                    ['inner-two' => 'value-two'],
                ],
            ],
            'object_arrayable' => [
                new class() {
                    public function toArray(): array
                    {
                        return ['foo' => 'bar'];
                    }
                },
                ['foo' => 'bar'],
            ],
            'object_unknown' => [
                new \stdClass(),
                '{}',
            ],
        ];
    }
}

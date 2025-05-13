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
            'key' => $key = 'foo',
            'value' => $value = 'bar',
        ];

        $actual = new StubField($key, $value);

        static::assertSame($expected, $actual->toArray());
    }

    public function testThrowsExceptionIncaseOfInvalidJsonSerializableValue(): void
    {
        $invalid = new class () implements JsonSerializable {
            public function jsonSerialize(): mixed
            {
                return tmpfile(); // resource is not serializable
            }
        };

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

        static::assertEquals($expected, $actual->toArray()['value']);
    }

    public static function valueDataProvider(): array
    {
        return [
            // primitives
            'string' => ['foo', 'foo'],
            'int' => [1, 1],
            'float' => [1.2, 1.2],
            'bool' => [true, true],
            'null' => [null, null],
            'array_of_mixed' => [[null, 1, 'foo'], [null, 1, 'foo']],

            'json_serializable' => [
                new class () implements JsonSerializable {
                    public function jsonSerialize(): mixed
                    {
                        return ['x' => '🚀', 'n' => '123'];
                    }
                },
                json_encode(['x' => '🚀', 'n' => '123'], JSON_THROW_ON_ERROR | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE)
            ],
            'nested_self' => [
                new StubField('inner', 'value'),
                ['key' => 'inner', 'value' => 'value']
            ],
            'nested_array_self' => [
                [
                    new StubField('inner-one', 'value-one'),
                    new StubField('inner-two', 'value-two'),
                ],
                [
                    ['key' => 'inner-one', 'value' => 'value-one'],
                    ['key' => 'inner-two', 'value' => 'value-two'],
                ],
            ],
            'object_arrayable' => [
                new class () {
                    public function toArray(): array
                    {
                        return ['foo' => 'bar'];
                    }
                },
                ['foo' => 'bar'],
            ],
            'object_unknown' => [
                new \stdClass(),
                new \stdClass(),
            ],
        ];
    }
}

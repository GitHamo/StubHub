<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\StubField;
use App\Models\Data\Stub;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{
    /**
     * @param array<string, mixed> $expected
     */
    #[DataProvider('serializeIntoArrayDataProvider')]
    public function testSerializeIntoArray(
        Stub $stub,
        array $expected,
    ): void {
        static::assertEquals($expected, $stub->toArray());
    }

    /**
     * @return array<string, array<Stub|array<string, mixed>>>
     */
    public static function serializeIntoArrayDataProvider(): array
    {
        return [
            'simple_stub' => [
                new Stub([
                    new StubField('name', 'John'),
                    new StubField('age', 30),
                ]),
                [
                    'name' => 'John',
                    'age' => 30,
                ]
            ],
            'nested_stub' => [
                new Stub([
                    new StubField('user', new Stub([
                        new StubField('name', 'John'),
                        new StubField('age', 30),
                        new StubField('is_active', true),
                    ])),
                    new StubField('foo', 'bar'),
                ]),
                [
                    'user' => [
                        'name' => 'John',
                        'age' => 30,
                        'is_active' => true
                    ],
                    'foo' => 'bar',
                ],
            ],
            // 'case' => [
            //     new Stub([
            //         new StubField('name', 'John'),
            //         new StubField('age', 30),
            //     ]),
            //     [
            //         'name' => 'John',
            //         'age' => 30,
            //     ]
            // ],
        ];
    }

    public function testSerializeIntoJson(): void
    {
        $stub = new Stub([
            new StubField('name', 'John'),
            new StubField('age', 30),
        ]);

        static::assertEquals('{"name":"John","age":30}', $stub->toJson());
    }

    public function testFromArrayThrowsExceptionWhenFieldMissingKey(): void
    {
        // Test that an exception is thrown when a field is missing the key
        $array = [['value' => 'baz']]; // Missing the "key" field in the array

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory field: "key"');

        Stub::fromArray($array);
    }

    public function testFromArrayThrowsExceptionWhenFieldMissingValue(): void
    {
        // Test that an exception is thrown when a field is missing the value
        $array = [['key' => 'foobar']]; // Missing the "value" field in the array

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory field: "value"');

        Stub::fromArray($array);
    }

    public function testFromArraySuccessfullyCreatesOutput(): void
    {
        // Test that the Output is created successfully when a valid array is passed
        $array = [['key' => 'foobar', 'value' => 'baz']]; // Valid array with fields

        $expected = new Stub([new StubField('foobar', 'baz')]);

        $actual = Stub::fromArray($array);

        static::assertEquals($expected, $actual);
    }

    public function testFromArraySuccessfullyCreatesNestedOutput(): void
    {
        // Test that the Output can handle nested fields
        $array = [
            ['key' => 'parent', 'value' => [['key' => 'child', 'value' => 'nested']]]  // Nested array
        ];

        $expected = new Stub([
            new StubField('parent', new Stub([new StubField('child', 'nested')])),
        ]);

        $actual = Stub::fromArray($array);

        static::assertEquals($expected, $actual);
    }

    public function testFromArrayThrowsExceptionForInvalidNestedArray(): void
    {
        // Test that an exception is thrown for invalid nested array (missing value for nested object)
        $array = [['key' => 'parent', 'value' => [['key' => 'child']]]]; // Invalid nested array, missing value for child

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Missing mandatory field: "value"');

        Stub::fromArray($array);
    }
}

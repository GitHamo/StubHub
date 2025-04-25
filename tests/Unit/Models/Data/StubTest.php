<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Data;

use App\Models\Data\StubField;
use App\Models\Data\Stub;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{
    public function testConvertsToArrayReturnsCorrectStructure(): void
    {
        $fields = [
            new StubField('name', 'John'),
            new StubField('age', 30),
            new StubField('is_active', true),
        ];

        $output = new Stub($fields);

        $expected = [
            'name' => 'John',
            'age' => 30,
            'is_active' => true,
        ];

        static::assertSame($expected, $output->toArray());
    }

    public function testConvertsToJsonReturnsCorrectJson(): void
    {
        $fields = [
            new StubField('name', 'John'),
            new StubField('score', 99.5),
        ];

        $output = new Stub($fields);

        $expectedJson = json_encode([
            'name' => 'John',
            'score' => 99.5,
        ]);

        static::assertSame($expectedJson, $output->toJson());
    }

    public function testSupportsNestedOutputObjects(): void
    {
        $innerFields = [
            new StubField('phone', '123-456-7890'),
            new StubField('price', 49.99),
        ];

        $outerFields = [
            new StubField('user', new Stub($innerFields))
        ];

        $output = new Stub($outerFields);

        $expected = [
            'user' => [
                'phone' => '123-456-7890',
                'price' => 49.99,
            ],
        ];

        static::assertSame($expected, $output->toArray());
    }

    public function testReturnsEmptyOutputOnEmptyArray(): void
    {
        $output = new Stub([]);

        static::assertSame([], $output->toArray());
        static::assertSame('[]', $output->toJson());
    }

    public function testFromArrayThrowsExceptionWhenArrayIsNotList(): void
    {
        // Test that an exception is thrown when the array is not a list
        $array = ["key" => "value"]; // Invalid array format (associative array instead of list)

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Array must decode to a list of fields.');

        // @phpstan-ignore-next-line
        Stub::fromArray($array);
    }

    public function testFromArrayThrowsExceptionWhenFieldIsNotAssociative(): void
    {
        // Test that an exception is thrown when a field in the array is not an associative array
        $array = [["key1", "value1"], ["key2", "value2"]]; // Invalid field format (list of lists instead of associative array)

        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Field array must decode to an associative array.');

        // @phpstan-ignore-next-line
        Stub::fromArray($array);
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

<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Domain;

use App\Models\Data\StubField;
use App\Models\Domain\Stub;
use PHPUnit\Framework\TestCase;

class StubTest extends TestCase
{
    public function testCreatesStructureFromInput(): void
    {
        $field1 = $this->createMock(StubField::class);
        $field2 = $this->createMock(StubField::class);
        $actual = Stub::create($field1, $field2);

        static::assertInstanceOf(Stub::class, $actual);
    }

    public function testCreatesEmptyStructure(): void
    {
        $stub = Stub::create(...[]);
        $actual = iterator_to_array($stub);

        static::assertCount(0, $actual);
        static::assertEmpty($actual);
        static::assertSame('[]', json_encode($stub, JSON_THROW_ON_ERROR));
    }

    public function testIsIterable(): void
    {
        $field = $this->createMock(StubField::class);
        $stub = Stub::create($field);
        $actual = iterator_to_array($stub);

        static::assertCount(1, $actual);
        static::assertSame($field, $actual[0]);
    }

    public function testIsJsonSerializable(): void
    {
        $field = $this->createConfiguredMock(StubField::class, [
            'toArray' => $fieldData = ['key' => 'foo'],
        ]);
        $stub = Stub::create($field);
        $expected = json_encode([$fieldData], JSON_THROW_ON_ERROR);
        $actual = json_encode($stub, JSON_THROW_ON_ERROR);

        static::assertJson($actual);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }
}

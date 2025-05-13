<?php

declare(strict_types=1);

namespace Tests\Unit\Models\Domain;

use App\Models\Data\Input;
use App\Modules\Structure\Domain\Structure;
use PHPUnit\Framework\TestCase;

final class StructureTest extends TestCase
{
    public function testCreatesStructureFromInput(): void
    {
        $input1 = $this->createMock(Input::class);
        $input2 = $this->createMock(Input::class);
        $actual = Structure::create($input1, $input2);

        static::assertInstanceOf(Structure::class, $actual);
    }

    public function testCreatesEmptyStructure(): void
    {
        $structure = Structure::create(...[]);
        $actual = iterator_to_array($structure);

        static::assertCount(0, $actual);
        static::assertEmpty($actual);
        static::assertSame('[]', json_encode($structure, JSON_THROW_ON_ERROR));
    }

    public function testIsIterable(): void
    {
        $input = $this->createMock(Input::class);
        $structure = Structure::create($input);
        $actual = iterator_to_array($structure);

        static::assertCount(1, $actual);
        static::assertSame($input, $actual[0]);
    }

    public function testIsJsonSerializable(): void
    {
        $input = $this->createConfiguredMock(Input::class, [
            'toArray' => $inputData = ['key' => 'foo'],
        ]);
        $structure = Structure::create($input);
        $expected = json_encode([$inputData], JSON_THROW_ON_ERROR);
        $actual = json_encode($structure, JSON_THROW_ON_ERROR);

        static::assertJson($actual);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }
}

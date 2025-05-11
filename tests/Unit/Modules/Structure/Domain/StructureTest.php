<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Structure\Domain;

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

    public function testSerializesInputsToJson(): void
    {
        $input = new class ($key = 'foo') extends Input {};
        $structure = Structure::create($input);
        $expected = json_encode([['key' => $key]], JSON_THROW_ON_ERROR);

        $actual = $structure->toJson();

        static::assertJson($actual);
        static::assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testExposesInputsAsArrayViaJsonSerialize(): void
    {

        $input = $this->createConfiguredMock(Input::class, [
            'jsonSerialize' => $inputData = ['key' => 'foo'],
        ]);

        $structure = Structure::create($input);

        $actual = $structure->jsonSerialize();

        static::assertSame(
            [
                $inputData,
            ],
            $actual
        );
    }
}

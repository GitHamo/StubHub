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
        $input = new class ('foo') extends Input {};
        $structure = Structure::create($input);

        $actual = $structure->toJson();

        static::assertJson($actual);
        static::assertJsonStringEqualsJsonString(
            json_encode([['key' => 'foo']], JSON_THROW_ON_ERROR),
            $actual
        );
    }

    public function testExposesInputsAsArrayViaJsonSerialize(): void
    {
        $createInput = fn (string $key, string $type): Input => new class ($key, $type, mt_rand()) extends Input {
            /** @phpstan-ignore-next-line */
            public function __construct(string $key, public string $type, private int $rand)
            {
                parent::__construct($key);
            }
        };

        $structure = Structure::create(
            $createInput('slug', 'string'),
            $createInput('is_active', 'boolean'),
        );

        $actual = $structure->jsonSerialize();

        static::assertSame(
            [
                ['key' => 'slug', 'type' => 'string'],
                ['key' => 'is_active', 'type' => 'boolean'],
            ],
            $actual
        );
    }
}

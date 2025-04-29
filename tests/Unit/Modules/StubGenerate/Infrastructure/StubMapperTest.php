<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\StubGenerate\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Inputs\Input;
use App\Models\Data\Inputs\Nested;
use App\Models\Data\Inputs\Single;
use App\Models\Data\Stub;
use App\Models\Data\StubField;
use App\Modules\StubGenerate\Infrastructure\StubMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class StubMapperTest extends TestCase
{
    private StubMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new class () extends StubMapper {
            protected function parseContext(StubFieldContext $context): mixed
            {
                return $context->value;
            }
        };
    }

    public function testCreatesStubFromSingleInput(): void
    {
        [$context] = $this->getRandomContexts(1);
        $input = new Single($key = 'foo', $context);

        $expected = new Stub([
            new StubField($key, $context->value),
        ]);

        $actual = $this->mapper->parseInputs($input);

        static::assertEquals($expected, $actual);
    }

    public function testCreatesStubFromNestedInputWithoutRepeat(): void
    {
        [$contextOne, $contextTwo] = $this->getRandomContexts(2);

        $input = new Nested($key = 'foo', [
            new Single('bar', $contextOne),
            new Single('baz', $contextTwo),
        ], 0);

        $expected = new Stub([
            new StubField($key, new Stub([
                new StubField('bar', $contextOne->value),
                new StubField('baz', $contextTwo->value),
            ])),
        ]);

        $actual = $this->mapper->parseInputs($input);

        static::assertEquals($expected, $actual);
    }

    public function testCreatesStubFromNestedInputWithtRepeat(): void
    {
        [$contextOne, $contextTwo] = $this->getRandomContexts(2);

        $input = new Nested($key = 'foo', [
            new Single('bar', $contextOne),
            new Single('baz', $contextTwo),
        ], 3);

        $expected = new Stub([
            new StubField($key, [
                new Stub([
                    new StubField('bar', $contextOne->value),
                    new StubField('baz', $contextTwo->value),
                ]),
                new Stub([
                    new StubField('bar', $contextOne->value),
                    new StubField('baz', $contextTwo->value),
                ]),
                new Stub([
                    new StubField('bar', $contextOne->value),
                    new StubField('baz', $contextTwo->value),
                ]),
            ]),
        ]);

        $actual = $this->mapper->parseInputs($input);

        static::assertEquals($expected, $actual);
    }

    public function testThrowsErrorOnInvalidInput(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage('Invalid input');

        $this->mapper->parseInputs(new class ('foo') extends Input {});
    }

    /**
     * @return \App\Enums\StubFieldContext[]
     */
    private function getRandomContexts(int $count = 1): array
    {
        return fake()->randomElements(StubFieldContext::cases(), $count);
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Support\InputRepeatMapper;
use PHPUnit\Framework\TestCase;

class InputRepeatMapperTest extends TestCase
{
    private InputRepeatMapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mapper = new InputRepeatMapper();
    }

    public function testFindsHighestInputRepeat(): void
    {
        $expected = 123456789;
        $context = StubFieldContext::cases()[0];
        $inputs = [
            new Nested('foo', [
                new Single('foo-one', $context),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 123)
                ], 0),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 321)
                ], 456),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 654)
                ], 789),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 987)
                ], $expected),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 10)
                ], 20),
                new Nested('bar', [
                    new Nested('baz', [
                        new Single('baz', $context),
                    ], 15)
                ], 2),
            ], 0),
        ];

        $actual = $this->mapper->max(...$inputs);

        static::assertSame($expected, $actual);
    }

    public function testHandlesEmptyInputs(): void
    {
        static::assertSame(0, $this->mapper->max());
    }
}

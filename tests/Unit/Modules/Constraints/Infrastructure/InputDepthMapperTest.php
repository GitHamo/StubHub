<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Constraints\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Modules\Constraints\Infrastructure\InputDepthMapper;
use PHPUnit\Framework\TestCase;

class InputDepthMapperTest extends TestCase
{
    private InputDepthMapper $inputdepthMapper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inputdepthMapper = new InputDepthMapper();
    }

    public function testFindsHighestDepth(): void
    {
        $input = new Nested('foo', [
            new Nested('bar', [
                new Nested('baz', [
                    new Nested('qux', [
                        new Nested('quux', [
                            new Nested('corge', [
                                new Nested('grault', [
                                    new Nested('garply', [
                                        new Single(
                                            'grault',
                                            self::getRandomContext(),
                                        ),
                                    ], 0),
                                ], 0),
                            ], 0),
                        ], 0),
                    ], 0),
                ], 0),
            ], 0),
        ], 0);

        $inputs = [
            $input,
            new Single('bar', self::getRandomContext()),
            new Nested('baz', [
                new Single('qux', self::getRandomContext()),
            ], 0),
        ];

        $actual = $this->inputdepthMapper->highest(...$inputs);

        static::assertSame(9, $actual);
    }

    private static function getRandomContext(): StubFieldContext
    {
        return StubFieldContext::cases()[random_int(0, count(StubFieldContext::cases()) - 1)];
    }
}

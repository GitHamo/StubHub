<?php

declare(strict_types=1);

namespace Tests\Unit\Modules\Content\Infrastructure;

use App\Enums\StubFieldContext;
use App\Models\Data\Input;
use App\Models\Data\Input\Nested;
use App\Models\Data\Input\Single;
use App\Models\Data\Stub;
use App\Models\Data\StubField;
use App\Modules\Content\Infrastructure\ContentFaker;
use App\Modules\Content\Infrastructure\ContentGeneratorService;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ContentGeneratorServiceTest extends TestCase
{
    private ContentGeneratorService $service;
    private ContentFaker&MockObject $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new ContentGeneratorService(
            $this->faker = $this->createMock(ContentFaker::class)
        );
    }

    public function testCreatesStubWithSingleInput(): void
    {
        $this->faker->method('parse')->willReturn('fake-value');

        $input = new Single('username', self::getRandomContext());
        $stub = $this->service->generate($input);

        $expected = new Stub([
            new StubField('username', 'fake-value')
        ]);

        static::assertEquals($expected, $stub);
    }

    public function testCreatesStubFromNestedInputWithNoRepeat(): void
    {
        $this->faker->method('parse')->willReturn('value');

        $child = new Single('email', self::getRandomContext());
        $nested = new Nested('user', [$child], 0);

        $stub = $this->service->generate($nested);

        $expected = new Stub([
            new StubField('user', new Stub([
                new StubField('email', 'value')
            ]))
        ]);

        static::assertEquals($expected, $stub);
    }

    public function testCreatesStubWithRepeatedNestedInput(): void
    {
        $this->faker->method('parse')->willReturn('value');

        $child = new Single('email', self::getRandomContext());
        $nested = new Nested('contacts', [$child], 2);

        $stub = $this->service->generate($nested);

        $expected = new Stub([
            new StubField('contacts', [
                new Stub([
                    new StubField('email', 'value')
                ]),
                new Stub([
                    new StubField('email', 'value')
                ]),
            ])
        ]);

        static::assertEquals($expected, $stub);
    }

    public function testCreatesThrowsForEmptyNestedInput(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage("Nested input 'empty' must contain child inputs.");

        $emptyNested = new Nested('empty', [], 0);
        $this->service->generate($emptyNested);
    }

    public function testCreatesThrowsForNegativeRepeat(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessage("Repeat must be 0 or greater.");

        $child = new Single('foo', self::getRandomContext());
        $badNested = new Nested('bad', [$child], -2);

        $this->service->generate($badNested);
    }

    public function testCreatesThrowsForUnsupportedInputType(): void
    {
        static::expectException(InvalidArgumentException::class);
        static::expectExceptionMessageMatches('/Unsupported input class:/');

        $unknownInput = new class ('test') extends Input {};

        $this->service->generate($unknownInput);
    }

    private static function getRandomContext(): StubFieldContext
    {
        return StubFieldContext::cases()[random_int(0, count(StubFieldContext::cases()) - 1)];
    }
}
